<?php

namespace App\Imagecache;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class FitMedium implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(250);
    }
}