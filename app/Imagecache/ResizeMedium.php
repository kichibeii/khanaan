<?php

namespace App\Imagecache;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class ResizeMedium implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->resize(250, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}