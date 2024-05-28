<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required',
            'post_id' => 'required|exists:posts,id',
        ]);

        $comment = new Comment([
            'body' => $request->body,
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
        ]);
        $comment->save();

        return response()->json(['message' => 'comment added successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id != Auth::id()) {
            return response()->json(['error' => 'You can only delete your own comments.'], 403);
        }

        $comment->delete();

        return response()->noContent();
    }
}
