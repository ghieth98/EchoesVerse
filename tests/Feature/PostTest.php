<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('user can add post', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/post/', [
        'title' => fake()->title,
        'body' => fake()->realText,
        'user_id' => $user->id,
    ]);

    $this->assertDatabaseCount('posts', 1);
    $response->assertStatus(201)->assertJson(['created' => true]);

});


it('user can view his own post', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create();

    $post = Post::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/post/' . $post->id);

    $response->assertStatus(200);

    $response->assertJsonPath('data.title', $post->title);
    $response->assertJsonPath('data.body', $post->body);

});

it('user can view users posts', function () {

    User::factory(100)->create();
    Post::factory(200)->create();
    $post = Post::first();
    $user = User::first();

    $response = $this->actingAs($user)->getJson('/api/post');

    $response->assertStatus(200);
    $response->assertJsonPath('data.0.title', $post->title);
    $response->assertJsonPath('data.0.body', $post->body);

});

it('user can edit post', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create();
    $post = Post::factory()->create();

    $response = $this->actingAs($user)->patchJson('/api/post/' . $post->id, [
        'title' => 'new title',
        'body' => $post->body,
        'user_id' => $post->user_id
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('data.title', 'new title');

});

it('user can delete post', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create();
    $post = Post::factory()->create();

    $response = $this->actingAs($user)->deleteJson('/api/post/' . $post->id);

    $response->assertStatus(200);
    $this->assertDatabaseCount('posts', 0);
    $response->assertSee('removed successfully');

});

it('posts belongs to user', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $this->assertInstanceOf(User::class, $post->user);
});



