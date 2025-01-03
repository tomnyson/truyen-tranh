<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PostViewHistory;
use Carbon\Carbon;


class PostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = intval($request->input('limit', 5));
            $page = intval($request->input('page', 1));

            if ($limit <= 0 || $page <= 0) {
                return response()->json([
                    'message' => 'Invalid pagination parameters. Limit and page must be positive integers.',
                ], 400);
            }

            $posts = Post::orderBy('created_at', 'desc')
                ->with(['category', 'tags'])
                ->paginate($limit, ['*'], 'page', $page);

            return response()->json($posts, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve posts.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPosts(Request $request)
    {
        try {
            // Base query
            $query = Post::with('category', 'tags', 'user')
                ->withCount('views');
    
            // Apply filters
            if ($request->filled('categoryId')) {
                $query->where('category_id', $request->input('categoryId'));
            }
    
            if ($request->filled('tagId')) {
                $tagId = $request->input('tagId');
                $query->whereHas('tags', function ($q) use ($tagId) {
                    $q->where('tags.id', $tagId);
                });
            }
    
            if ($request->filled('userId')) {
                $query->where('user_id', $request->input('userId'));
            }
    
            if ($request->filled('keyword')) {
                $keyword = $request->input('keyword');
                $query->where('title', 'LIKE', '%' . $keyword . '%');
            }
    
            if ($request->filled('type')) {
                $query->where('type', $request->input('type'));
            }
    
            if ($request->filled('limit')) {
                $limit = $request->input('limit');
                $query->limit($limit);
            }
    
            if ($request->filled('is_pin')) {
                $query->where('is_pin', $request->input('is_pin'));
            }
    
            if ($request->filled('orderBy')) {
                $orderBy = $request->input('orderBy');
                $orderDirection = $request->input('orderDirection', 'desc'); // Default to descending order
    
                if ($orderBy === 'views') {
                    $query->orderBy('views_count', $orderDirection); // Order by view count
                } elseif ($orderBy === 'created_at') {
                    $query->orderBy('created_at', $orderDirection); // Order by creation date
                }
            }
    
            // Retrieve the posts
            $posts = $query->get();
    
            // Filter views by time (day, week, month, all)
            if ($request->filled('viewTime')) {
                $viewTime = $request->input('viewTime');
                $postIds = $posts->pluck('id');
    
                // Query view counts for all posts at once
                $viewsQuery = PostViewHistory::selectRaw('post_id, COUNT(*) as views_count')
                    ->whereIn('post_id', $postIds)
                    ->groupBy('post_id');
    
                if ($viewTime === 'day') {
                    $viewsQuery->whereDate('viewed_at', now()->toDateString());
                } elseif ($viewTime === 'week') {
                    $viewsQuery->whereBetween('viewed_at', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($viewTime === 'month') {
                    $viewsQuery->whereMonth('viewed_at', now()->month)
                        ->whereYear('viewed_at', now()->year);
                }
    
                $viewCounts = $viewsQuery->get()->keyBy('post_id');
    
                // Assign view counts to posts
                foreach ($posts as $post) {
                    $post->views_count = $viewCounts->get($post->id)->views_count ?? 0;
                }
            }
    
            return response()->json([
                'message' => 'Posts retrieved successfully',
                'data' => $posts,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve posts',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {

            $request->merge([
                'is_pin' => filter_var($request->is_pin, FILTER_VALIDATE_BOOLEAN)
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                "type" => "required|string|max:255|in:free,paid",
                "image" => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'tags' => 'array',
                'tags.*' => 'exists:tags,id',
                'meta_title' => 'nullable|string|max:60',
                'meta_description' => 'nullable|string|max:160',
                'meta_keywords' => 'nullable|string|max:255',
                'is_pin' => 'required|boolean',
                'status' => 'required|in:draft,review,published',
            ]);

            $user = $request->user();
            $validated['user_id'] = $user->id;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public'); // Save to 'storage/app/public/images'
                $validated['image'] = $imagePath;
            }


            $post = Post::create($validated);
            $post->tags()->attach($request->tags);
            return response()->json($post->load('category', 'tags'), 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(Post $post)
    {
        try {
            // Load the category and tags relationships
            $postWithRelations = $post->load('category', 'tags');

            // Return the response as JSON
            return response()->json([
                'status' => 'success',
                'data' => $postWithRelations
            ]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve the post.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Post $post)
    {
        try {
            // var_dump($request->all());
            $request->merge([
                'is_pin' => filter_var($request->is_pin, FILTER_VALIDATE_BOOLEAN)
            ]);

            $validated = $request->validate([
                'title' => 'string|max:255',
                'content' => 'required|string',
                'category_id' => 'required|string|exists:categories,id',
                'tags' => 'array',
                'tags.*' => 'exists:tags,id',
                "image" => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'meta_title' => 'nullable|string|max:60',
                'meta_description' => 'nullable|string|max:160',
                'meta_keywords' => 'nullable|string|max:255',
                'is_pin' => 'required|boolean',
                'status' => 'required|in:draft,review,published',
            ]);
            $user = $request->user();
            $validated['user_id'] = $user->id;

            if ($request->hasFile('image')) {
                // Delete the old image if exists
                if ($post->image) {
                    Storage::delete('public/' . $post->image);
                }

                $imagePath = $request->file('image')->store('images', 'public');
                $validated['image'] = $imagePath;
            }

            $post->update($validated);

            if ($request->has('tags')) {
                $post->tags()->sync($request->tags);
            }

            return response()->json([
                'message' => 'Post updated successfully',
                'data' => $post->load('category', 'tags'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function getDetail($id, request $request)
    {
        try {
            $post = Post::with('category', 'tags', 'user')->find($id);

            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }
            // dd($request->headers->all());
            $ipAddress = $request->ip();
            $userAgent = $request->header('user-agent');
            $user = $request->user();

            $existingView = PostViewHistory::where('post_id', $id)
                ->where('ip_address', $ipAddress)
                ->where('user_agent', $userAgent)
                ->where('viewed_at', '>', Carbon::now()->subHour())
                ->exists();

            if (!$existingView) {
                PostViewHistory::create([
                    'post_id' => $id,
                    'user_id' => $user->id ?? null,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'viewed_at' => now(),
                ]);
            }

            $isBookmarked = false;
            $bookmarkId = null;
            if ($user = $request->user()) {
                $isBookmarked = $user->bookmarks()->where('post_id', $id)->exists();
                if ($isBookmarked) {
                    $bookmarkId = $user->bookmarks()->where('post_id', $id)->first()->id;
                }
            }

            $post->isBookmarked = $isBookmarked;
            $post->bookmarkId = $bookmarkId;

            return response()->json($post, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
