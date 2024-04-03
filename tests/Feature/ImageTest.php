<?php

use App\Models\Image;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->profile = Profile::factory()->create();
    $this->post = Post::factory()->create();
    $this->withoutExceptionHandling();
});


it('image belongs to user profile', function () {
    Storage::fake('public');
    $image = UploadedFile::fake()->image('avatar.jpg');
    actingAs($this->user)->postJson('/api/profile', [
        'bio' => 'test bio sentence',
        'phone_number' => fake()->phoneNumber,
        'address' => fake()->address,
        'user_id' => $this->user->id,
        'gender' => 'male',
        'url' => $image,
        'imageable_id' => 1,
        'imageable_type' => 'App\Models\Profile'
    ]);
    $imageModel = Image::first();
    $this->assertInstanceOf(Profile::class, $imageModel->imageable);
});


it('image belongs to post', function () {
    Storage::fake('public');

    $image = UploadedFile::fake()->image('avatar.jpg');
    actingAs($this->user)->postJson('/api/post/', [
        'title' => 'post title',
        'body' => fake()->realText,
        'user_id' => $this->user->id,
        'url' => $image,
        'imageable_id' => 1,
        'imageable_type' => 'App\Models\Post'
    ]);
    $imageModel = Image::first();
    $this->assertInstanceOf(Post::class, $imageModel->imageable);
});

it('can add image to profile', function () {
    Storage::fake('public');

    $image = UploadedFile::fake()->image('avatar.jpg');

    actingAs($this->user)->postJson('/api/profile', [
        'bio' => 'test bio sentence',
        'phone_number' => fake()->phoneNumber,
        'address' => fake()->address,
        'user_id' => $this->user->id,
        'gender' => 'male',
        'url' => $image,
        'imageable_id' => 1,
        'imageable_type' => 'App\Models\Profile'
    ])->assertCreated()
        ->assertJson(['created' => true]);

    $this->assertDatabaseCount('profiles', 2);
    $this->assertDatabaseCount('images', 1);
    $this->assertDatabaseHas('profiles', [
        'bio' => 'test bio sentence',
    ]);

    $this->assertDatabaseHas('images', [
        'url' => 'uploads/' . $image->hashName(),
    ]);

});

it('user can add image to post', function () {

    Storage::fake('public');

    $image = UploadedFile::fake()->image('avatar.jpg');

    actingAs($this->user)->postJson('/api/post/', [
        'title' => 'post title',
        'body' => fake()->realText,
        'user_id' => $this->user->id,
        'url' => $image,
        'imageable_id' => 1,
        'imageable_type' => 'App\Models\Post'
    ])->assertCreated()
        ->assertJson(['created' => true]);

    $this->assertDatabaseCount('posts', 2);
    $this->assertDatabaseCount('images', 1);
    $this->assertDatabaseHas('posts', [
        'title' => 'post title',
    ]);

    $this->assertDatabaseHas('images', [
        'url' => 'uploads/' . $image->hashName(),
    ]);

});

it('user can update profile image', function () {

    Storage::fake('public');

    $image = UploadedFile::fake()->image('avatar.jpg');

    $profile = Profile::first();

    $newImage = UploadedFile::fake()->image('new avatar.jpg');

    actingAs($this->user)->patchJson('/api/profile/' . $profile->id, [
        'user_id' => $profile->user_id,
        'bio' => 'new bio sentence',
        'url' => $newImage,
    ])->assertStatus(200)
        ->assertJsonPath('data.bio', Profile::first()->bio);

    $this->assertEquals('new bio sentence', Profile::first()->bio);

    $this->assertDatabaseMissing('images', [
        'url' => 'uploads/' . $image->hashName(),
    ]);

    $this->assertDatabaseHas('images', [
        'url' => 'uploads/' . $newImage->hashName(),
    ]);

});


it('user can update post image', function () {

    Storage::fake('public');

    $image = UploadedFile::fake()->image('avatar.jpg');
    $post = Post::first();
    $newImage = UploadedFile::fake()->image('new avatar.jpg');

    actingAs($this->user)->patchJson('/api/post/' . $post->id, [
        'title' => 'new title',
        'body' => $post->body,
        'user_id' => $post->user_id,
        'url' => $newImage
    ])->assertStatus(200)
        ->assertJsonPath('data.title', Post::first()->title);


    $this->assertDatabaseMissing('images', [
        'url' => 'uploads/' . $image->hashName(),
    ]);

    $this->assertDatabaseHas('images', [
        'url' => 'uploads/' . $newImage->hashName(),
    ]);

});

it('user can view profile image', function () {

    Storage::fake('public');

    $profile = Profile::first();

    $newImage = UploadedFile::fake()->image('new avatar.jpg');

    actingAs($this->user)->patchJson('/api/profile/' . $profile->id, [
        'user_id' => $profile->user_id,
        'bio' => 'new bio sentence',
        'url' => $newImage,
    ]);

    actingAs($this->user)->getJson('/api/profile/')
        ->assertStatus(200)
        ->assertJsonPath('data.0.profile image', asset('uploads/' . $profile->image?->url));
});


it('user can his own profile image', function () {

    Storage::fake('public');

    $profile = Profile::first();

    $newImage = UploadedFile::fake()->image('new avatar.jpg');

    actingAs($this->user)->patchJson('/api/profile/' . $profile->id, [
        'user_id' => $profile->user_id,
        'bio' => 'new bio sentence',
        'url' => $newImage,
    ]);

    actingAs($this->user)->getJson('/api/profile/' . $profile->id)
        ->assertStatus(200)
        ->assertJsonPath('data.profile image', asset('uploads/' . $profile->image?->url));
});

it('user can view posts image', function () {

    Storage::fake('public');

    $post = Post::first();

    $newImage = UploadedFile::fake()->image('new avatar.jpg');

    actingAs($this->user)->patchJson('/api/post/' . $post->id, [
        'title' => 'new title',
        'body' => $post->body,
        'user_id' => $post->user_id,
        'url' => $newImage
    ]);

    actingAs($this->user)->getJson('/api/post/')
        ->assertStatus(200)
        ->assertJsonPath('data.0.image', asset('uploads/' . $post->image?->url));
});

it('user can view his own post image', function () {

    Storage::fake('public');

    $post = Post::first();

    $newImage = UploadedFile::fake()->image('new avatar.jpg');

    actingAs($this->user)->patchJson('/api/post/' . $post->id, [
        'title' => 'new title',
        'body' => $post->body,
        'user_id' => $post->user_id,
        'url' => $newImage
    ]);

    actingAs($this->user)->getJson('/api/post/' . $post->id)
        ->assertStatus(200)
        ->assertJsonPath('data.image', asset('uploads/' . $post->image?->url));

});


it('image is deleted when post is deleted', function () {

    actingAs($this->user)->deleteJson('/api/post/' . $this->post->id)
        ->assertStatus(200)
        ->assertSee('removed successfully');
    $this->assertDatabaseCount('posts', 0);
    $this->assertDatabaseCount('images', 0);
});

