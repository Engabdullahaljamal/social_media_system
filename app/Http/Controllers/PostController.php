<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::with('category', 'user', 'comments')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = new Post([
            'title' => $request->title,
            'body' => $request->body,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
        ]);

        $post->save();

        return response()->json(['message' => 'post added successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Post::with('category', 'user', 'comments')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id != Auth::id()) {
            return response()->json(['error' => 'You can only edit your own posts.'], 403);
        }

        $post->update($request->all());

        return response()->json(['message' => 'post updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id != Auth::id()) {
            return response()->json(['error' => 'You can only delete your own posts.'], 403);
        }

        $post->delete();

        return response()->noContent();
    }
}
