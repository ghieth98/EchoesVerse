<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->post = Post::factory()->create();
    withoutExceptionHandling();

});

test('user can add comment to post', function () {
    $user = User::first();
    actingAs($user)->postJson('/api/post/{post}/comments', [
        'comment' => fake()->text,
        'user_id' => $user->id,
        'post_id' => $this->post->id
    ])->assertStatus(201);

    $this->assertDatabaseCount('comments', 1);
    $this->assertDatabaseHas('comments', [
        'user_id' => $user->id,
        'post_id' => $this->post->id
    ]);

});


test('user can edit post comment', function () {
    $user = User::first();
    actingAs($user)->postJson('/api/post/{post}/comments/', [
        'comment' => fake()->text,
        'user_id' => $user->id,
        'post_id' => $this->post->id
    ])->assertStatus(201);

    $comment = Comment::first();

    actingAs($user)->patchJson("/api/comments/". $comment->id, [
        'comment' => 'new comment',
        'post_id' => $this->post->id,
        'user_id' => $user->id
    ])->assertStatus(200);

    $this->assertDatabaseHas('comments', [
        'comment' => 'new comment',
        'post_id' => $this->post->id
    ]);
});

test('user delete comment', function () {
    $user = User::first();
    actingAs($user)->postJson('/api/post/{post}/comments/', [
        'comment' => fake()->text,
        'user_id' => $user->id,
        'post_id' => $this->post->id
    ])->assertStatus(201);


    $comment = Comment::first();

    $response = $this->deleteJson('/api/comments/' . $comment->id);

    $response->assertStatus(200);
    $this->assertDatabaseCount('comments', 0);

});
