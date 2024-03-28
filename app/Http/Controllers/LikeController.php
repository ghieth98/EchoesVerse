<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Models\Post;

class LikeController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLikeRequest $request)
    {
        $postToLike = Post::Find($request->input('post_id'));
        auth()->user()->Like($postToLike);
        return response()->json([
            'created' => true
        ], 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreLikeRequest $request)
    {
        $postToUnlike = Post::findOrFail($request->post_id);

        auth()->user()->unLike($postToUnlike);
    }
}
