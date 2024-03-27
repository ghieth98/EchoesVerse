<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFollowRequest;
use App\Models\User;

class FollowController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFollowRequest $request)
    {
        $userToFollow = User::Find($request->input('user_id'));
        auth()->user()->follow($userToFollow);
        return response()->json([
            'created' => true
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreFollowRequest $request)
    {
        $userToUnfollow = User::findOrFail($request->user_id);
        auth()->user()->unfollow($userToUnfollow);
    }
}
