<?php

use App\Models\Post;
use App\Models\Profile;
use App\Models\User;


it('can show users', function () {
    $this->withoutExceptionHandling();
    $user = User::factory()->create();
    Profile::factory()->create();
    $response = $this->actingAs($user)->get('/api/user');
    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
//    $response->assertJsonPath('data.0.name', $user->name);
});

it('can allow user to his information', function () {
    $this->withoutExceptionHandling();

    User::factory()->create();
    $user = User::first();

    $response = $this->actingAs($user)->getJson('/api/user/' . $user->id);
    $response->assertStatus(200);
    $response->assertJsonPath('data.name', $user->name);
    $response->assertJsonPath('data.email', $user->email);


});

it('can allow user to edit his information', function () {
    $this->withoutExceptionHandling();
    User::factory()->create();
    $user = User::first();

    $response = $this->actingAs($user)->patchJson('/api/user/' . $user->id, [
        'name' => 'new jon -v-',
        'password' => 'new password',
    ]);

    $response->assertStatus(200);
    $this->assertEquals('new jon -v-', User::first()->name);
    $response->assertJsonPath('data.name', User::first()->name);
});

it('can allow user to delete his account', function () {
    $this->withoutExceptionHandling();

    User::factory()->create();
    $user = User::first();

    $response = $this->actingAs($user)->deleteJson('/api/user/' . $user->id);

    $response->assertStatus(200);
    $this->assertDatabaseCount('users', 0);
    $response->assertSee('removed successfully');
});

it('user has profile', function () {
    $user = User::factory()->create();
    Profile::factory()->create(['user_id' => $user->id]);

    $this->assertInstanceOf(Profile::class, $user->profile);
});

it('user has many posts', function () {
    $this->withoutExceptionHandling();
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $this->assertTrue($user->posts->contains($post));
});
