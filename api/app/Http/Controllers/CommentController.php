<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{


    public function index(Request $request)
    {
        $postId = $request->query('postId');
        $comments = Comment::with('user', 'replies.user')
            ->where('post_id', $postId)
            ->whereNull('parent_id') // Only top-level comments
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'parent_id' => $request->parent_id, // Null for top-level comments
        ]);

        return response()->json($comment, 201);
    }

    
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
