<?php

namespace App\Models;

use App\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, HasImage;

    protected $fillable = [
        'title',
        'body',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function like(Post $post): void
    {
        if (!$this->isLiked($post)) {
            Post::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id
            ]);
        }
    }

    public function isLiked(Post $post): bool
    {
        return $this->likes()->where('posts.id', $post->id)->exists();
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function unlike(Post $post): void
    {
        Like::where('post_id', $post->id)->delete();
    }
}
