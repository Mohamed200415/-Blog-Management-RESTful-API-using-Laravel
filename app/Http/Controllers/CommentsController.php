<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::all();
        return response()->json(['message' => 'Comments retrieved successfully', 'comments' => $comments], 200);
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
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id',
        ]);
        $comment = Comment::create($request->all());
        return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json(['message' => 'Comment retrieved successfully', 'comment' => $comment], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comment = Comment::find($id);
        if(!$comment){
            return response()->json(['message'=> 'comment not found'],404);
        }
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id',
        ]);

        $comment->update($request->all());
        
        return response()->json(['message' => 'comment updated successfully', 'comment' => $comment], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 204);
    }
}
