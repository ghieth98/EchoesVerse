<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function () {
    $this->user = User::factory(6)->create();
    withoutExceptionHandling();
});

it('user can follow user', function () {
    $user = User::first();
    $user2 = User::find(2);

    actingAs($user)->postJson('/api/follow/', [
        'user_id' => $user2->id,
        'following_id' => $user->id
    ])->assertStatus(201);

    $this->assertDatabaseCount('follows', 1);
});


it('user can unfollow user', function () {
    $user = User::first();
    $user2 = User::find(12);

    actingAs($user)->deleteJson('/api/unfollow/', [
        'user_id' => $user2->id,
        'following_id' => $user->id
    ])->assertStatus(200);

    $this->assertDatabaseCount('follows', 0);
});
