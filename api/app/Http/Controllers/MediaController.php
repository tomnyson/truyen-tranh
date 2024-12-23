<?php
namespace App\Http\Controllers;

use App\Models\Media as MediaModel;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;


class MediaController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 5);
        $page = $request->input('page', 1);
    
        // Signature: paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
        $media = MediaModel::orderBy('created_at', 'desc')
                      ->paginate($limit, ['*'], 'page', $page);
    
        return response()->json($media);
    }

    public function store(Request $request): JsonResponse
    {
        // Validate incoming request
        $request->validate([
            'media' => 'required|file'
        ]);

        $uploadedFile = $request->file('media');
        $path = $uploadedFile->store('uploads', 'public'); // stores file in storage/app/public/uploads

        // Create media record
        $media = MediaModel::create([
            'file_name' => basename($path),
            'original_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'disk' => 'public',
            'path' => $path,
            'user_id' => auth()->id() ?? null,
        ]);

        // Return JSON with success and media data
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $media->id,
                'original_name' => $media->original_name,
                'file_name' => $media->file_name,
                'url' => Storage::disk($media->disk)->url($media->path),
                'created_at' => $media->created_at,
            ]
        ], 201); // 201 Created
    }

    /**
     * Remove the specified media resource from storage and return JSON.
     */
    public function destroy(MediaModel $media): JsonResponse
    {
        // Optionally, delete the actual file from storage
        if (Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully',
        ], 200);
    }
}
