<?php

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->post = Post::factory()->create();
    withoutExceptionHandling();
});

test('like belongs to post', function () {
    $user = User::first();

    actingAs($user)->postJson('/api/like', [
        'user_id' => $this->user->id,
        'post_id' => $this->post->id,
    ]);

    $like = Like::first();
    $this->assertInstanceOf(Post::class, $like->post);

});

test('like belongs to user', function () {
    $user = User::first();

    actingAs($user)->postJson('/api/like', [
        'user_id' => $this->user->id,
        'post_id' => $this->post->id,
    ]);

    $like = Like::first();
    $this->assertInstanceOf(User::class, $like->user);

});

test('user can like post', function () {

    $user = User::first();

    actingAs($user)->postJson('/api/like/', [
        'user_id' => $this->user->id,
        'post_id' => $this->post->id
    ])->assertStatus(201);

    $this->assertDatabaseCount('likes', 1);
    $this->assertDatabaseHas('likes', [
        'user_id' => $this->user->id,
        'post_id' => $this->post->id
    ]);
});

test('user can unlike post', function () {
    $user = User::first();

    actingAs($user)->deleteJson('/api/unlike/', [
        'user_id' => $this->user->id,
        'post_id' => $this->post->id
    ])->assertStatus(200);

    $this->assertDatabaseCount('likes', 0);
});



