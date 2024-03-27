<?php

namespace App;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasImage
{
    public function getImage(): ?string
    {
        $image = $this->image;
        if (!$image) {
            return null;
        }

        return asset('uploads/' . $image->url);
    }

    public function updateImage($url): void
    {
        $this->deleteImage();

        $this->storeImage($url);
    }

    public function deleteImage(): void
    {
        if ($this->image) {
            $this->image->delete();
        }
    }

    public function storeImage($url)
    {
        return $this->image()->create(['url' => $url]);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }


}
