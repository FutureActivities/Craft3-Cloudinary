<?php
namespace futureactivities\cloudinary\twigextensions;

class CloudinaryTwigExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'Cloudinary';
    }
    
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('buildImg', [$this, 'buildImg'], ['is_safe' => ['html']]),
        ];
    }

    public function buildImg($attributes = [], $sizes = null)
    {
        $parts = [];

        if ($sizes)
            $parts[] = sprintf('sizes="%s"', $sizes);
        
        foreach($attributes AS $key=>$value) {
            if (empty($value)) continue;
            $parts[] = sprintf('%s="%s"', $key, $value);
        }
        
        return '<img '.implode(' ', $parts).'/>';
    }
}

