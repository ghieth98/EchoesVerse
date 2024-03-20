<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('user can add a profile', function () {
    $this->withoutExceptionHandling();

    User::factory()->create();

    $response = $this->postJson('/api/profile', [
        //TODO ADD PROFILE DATA HERE
        'bio' => 'test bio sentence',
        'phone_number' => fake()->phoneNumber,
        'address' => fake()->address,
        'user_id' => 2,
        'gender' => 'male'
    ]);
    $this->assertDatabaseCount('profiles', 1);
    $this->assertDatabaseHas('profiles', [
        'bio' => 'test bio sentence',
    ]);

    $response->assertCreated()->assertJson(['created' => true]);

});

it('user can view his profile information', function () {
    $this->withoutExceptionHandling();

    User::factory()->create();
    $this->postJson('/api/profile', [
        'bio' => 'test bio sentence',
        'phone_number' => fake()->phoneNumber,
        'address' => fake()->address,
        'user_id' => 2,
        'gender' => 'male'
    ]);
    $profile = Profile::first();
    $response = $this->getJson('/api/profile/' . $profile->id);

    $response->assertStatus(200);
    $response->assertJsonPath('data.bio', $profile->bio);
    $response->assertJsonPath('data.gender', $profile->gender);

});

it('user can edit profile information', function () {
    $this->withoutExceptionHandling();

    User::factory()->create();
    $user = User::first();

    $this->postJson('/api/profile', [
        'bio' => 'test bio sentence',
        'phone_number' => fake()->phoneNumber,
        'address' => fake()->address,
        'user_id' => $user->id,
        'gender' => 'male'
    ]);

    Profile::factory()->create();
    $profile = Profile::first();

    $response = $this->actingAs($user)->patchJson('/api/profile/' . $profile->id, [
        'user_id' => $profile->user_id,
        'bio' => 'new bio sentence'
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('data.bio', Profile::first()->bio);
    $this->assertEquals('new bio sentence', Profile::first()->bio);
});

it('user can view other users profiles', function () {
    $this->withoutExceptionHandling();
    User::factory(10)->create();
    Profile::factory(10)->create();

    $response = $this->getJson('/api/profile/');
    $profile = Profile::first();
    $response->assertStatus(200);
    $response->assertJsonPath('data.0.bio', $profile->bio);

});

it('user can only create/edit his own profile', function () {

    User::factory()->create();
    $this->postJson('/api/profile', [
        'bio' => 'test bio sentence',
        'phone_number' => fake()->phoneNumber,
        'address' => fake()->address,
        'user_id' => 1,
        'gender' => 'male'
    ]);
    $user = User::first()->get();
    $profile = Profile::first('id');

    $response = $this->patchJson('/api/profile/' . $profile->id, [
        'user_id' => 2,
        'bio' => 'new bio sentence'
    ]);
    $response->assertStatus(403);
});


it('profile belongs to user', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $this->assertInstanceOf(User::class, $profile->user);
});
