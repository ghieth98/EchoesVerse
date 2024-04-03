<?php

use App\Models\User;
use function Pest\Laravel\postJson;

it('users can login', function () {
    $user = User::factory()->create();

    postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertStatus(200)->assertJsonStructure([
        'access_token',
    ]);

    $this->assertAuthenticated();


});

test('users can not login if password is invalid', function () {
    $user = User::factory()->create();

    postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('user can register', function () {
    postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
});
