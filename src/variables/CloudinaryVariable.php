<?php
namespace futureactivities\cloudinary\variables;

use futureactivities\cloudinary\Plugin as Cloudinary;

class CloudinaryVariable
{
    public function transform($image, $sizes = [], $options = [])
    {
        return Cloudinary::getInstance()->transform->image($image, $sizes, $options);
    }
    
    public function scaleAndCrop($image, $sizes)
    {
        return Cloudinary::getInstance()->transform->scaleAndCrop($image, $sizes);
    }
}