<?php

namespace App\Imagecache;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class SlideshowLogo implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(240, 261);
        //return $image->fit(360, 258);
    }
}