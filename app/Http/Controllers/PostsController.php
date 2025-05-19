<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Log;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = Post::with(['category', 'user'])->get();
            return PostResource::collection($posts);
        } catch (\Exception $e) {
            Log::error('Error fetching posts: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error retrieving posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Debug incoming request
            Log::info('Post Creation Request:', [
                'all_data' => $request->all(),
                'content_type' => $request->header('Content-Type'),
                'is_json' => $request->isJson(),
                'raw_content' => $request->getContent()
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'image_thumbnail' => 'nullable|string|url',
                'image_content' => 'nullable|string|url',
                'is_published' => 'nullable|boolean',
                'published_at' => 'nullable|date',
                'author' => 'required|string|max:255'
            ]);
            
            $post = Post::create($validated);
            
            return new PostResource($post);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating post:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error creating post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = Post::with(['category', 'user', 'comments'])->find($id);
            if (!$post) {
                return response()->json([
                    'message' => 'Post not found'
                ], 404);
            }
            return new PostResource($post);
        } catch (\Exception $e) {
            Log::error('Error fetching post: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error retrieving post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return response()->json([
                    'message' => 'Post not found'
                ], 404);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'image_thumbnail' => 'nullable|string|url',
                'image_content' => 'nullable|string|url',
                'is_published' => 'nullable|boolean',
                'published_at' => 'nullable|date',
                'author' => 'required|string|max:255'
            ]);

            $post->update($validated);
            return new PostResource($post);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating post: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return response()->json([
                    'message' => 'Post not found'
                ], 404);
            }

            $post->delete();
            return response()->json([
                'message' => 'Post deleted successfully'
            ], 204);

        } catch (\Exception $e) {
            Log::error('Error deleting post: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
