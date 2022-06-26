<?php

namespace App\Imagecache;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Slideshow implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(1920, 1060);
    }
}