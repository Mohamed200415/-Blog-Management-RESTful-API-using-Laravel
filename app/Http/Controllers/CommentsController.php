<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $comments = Comment::all();
            return response()->json(['message' => 'Comments retrieved successfully', 'comments' => $comments], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching comments: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error retrieving comments',
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
            Log::info('Comment Creation Request:', [
                'all_data' => $request->all(),
                'content_type' => $request->header('Content-Type'),
                'is_json' => $request->isJson(),
                'raw_content' => $request->getContent()
            ]);

            $validated = $request->validate([
                'content' => 'required|string',
                'post_id' => 'required|exists:posts,id',
                'user_id' => 'required|exists:users,id',
                'is_published' => 'nullable|boolean',
                'published_at' => 'nullable|date',
            ]);

            $comment = Comment::create($validated);
            return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);

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
            Log::error('Error creating comment: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating comment',
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
            $comment = Comment::find($id);
            if (!$comment) {
                return response()->json(['message' => 'Comment not found'], 404);
            }
            return response()->json(['message' => 'Comment retrieved successfully', 'comment' => $comment], 200);
        } catch (\Exception $e) {
             Log::error('Error fetching comment: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error retrieving comment',
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
            $comment = Comment::find($id);
            if(!$comment){
                return response()->json(['message'=> 'comment not found'],404);
            }

            $validated = $request->validate([
                'content' => 'required|string',
                'post_id' => 'required|exists:posts,id',
                'user_id' => 'required|exists:users,id',
                 'is_published' => 'nullable|boolean',
                'published_at' => 'nullable|date',
            ]);

            $comment->update($validated);
            return response()->json(['message' => 'comment updated successfully', 'comment' => $comment], 200);

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
            Log::error('Error updating comment: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating comment',
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
            $comment = Comment::find($id);
            if (!$comment) {
                return response()->json(['message' => 'Comment not found'], 404);
            }
            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully'], 204);

        } catch (\Exception $e) {
             Log::error('Error deleting comment: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
