<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\api\v1\ProfileCollection;
use App\Http\Resources\api\v1\ProfileResource;
use App\Models\Profile;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ProfileCollection(Profile::with(['user', 'image'])->paginate());
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        return new ProfileResource($profile);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        if (!Gate::allows('update-profile', $profile)) {
            abort(403);
        }
        $profile->update($request->validated());
        if ($request->hasFile('url')) {
            $profile->updateImage($request->file('url')->store('/uploads'));
        }
        return new ProfileResource($profile);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileRequest $request)
    {
        $profile = Profile::create($request->validated());

        if ($request->hasFile('url')) {
            $profile->storeImage($request->file('url')->store('/uploads'));
        }

        return response()->json(['created' => true], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
//    public function destroy(Profile $profile)
//    {
//        //
//    }
}
