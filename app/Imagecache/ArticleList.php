<?php

namespace App\Imagecache;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class ArticleList implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(1000, 650);
    }
}