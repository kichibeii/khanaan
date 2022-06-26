<?php

namespace App\Imagecache;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class ProductDetail implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(1004, 1406);
    }
}