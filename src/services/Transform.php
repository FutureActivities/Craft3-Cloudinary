<?php
namespace futureactivities\cloudinary\services;

use craft\base\Component;

class Transform extends Component
{
    /**
     * Transform an image
     * 
     * @param Asset $image Craft Asset
     * @param array $sizes A list of sizes for this image
     * @param array $options Additional options
     */
    public function image($image, $sizes = [], $options = [])
    {
        // Secure sign the cloudinary URL
        $options['sign_url'] = true;
        
        // No alt tag specified?
        if (!isset($options['alt']))
            $options['alt'] = $image->title;
            
        // Scale and crop?
        if (isset($options['scaleAndCrop']) && $options['scaleAndCrop']) {
            $sizes = $this->scaleAndCrop($image, $sizes);
            unset($options['scaleAndCrop']);
        }
        
        $urls = $this->generateUrls($image->filename, $sizes, $options);
        
        return [
            'alt' => $options['alt'],
            'src' => reset($urls),
            'srcset' => $this->generateSrcSet($urls)
        ];
    }
    
    /**
     * Scale and crop an image.
     *
     * By default, Cloudinary can only scale OR crop. In craft we want to scale
     * then crop using the focal point specified.
     */
    protected function scaleAndCrop($image, $sizes)
    {
        $focalPoint = $image->getFocalPoint();
          
        foreach ($sizes AS &$size) {
            
            $x = strval(round($focalPoint['x'] * $image->width));
            $y = strval(round($focalPoint['y'] * $image->height));
            
            $quality = 100;
            if (isset($size['quality']))
                $quality = $size['quality'];
            
            $size['transformation'] = [];
            if ($image->width >= $image->height)
                $size['transformation'][] = ['height' => $size['height'], 'crop' => 'scale', 'quality' => $quality];
            else if ($image->width < $image->height)
                $size['transformation'][] = ['width' => $size['width'], 'crop' => 'scale', 'quality' => $quality];
                
            $size['transformation'][] = ['height' => $size['height'], 'width' => $size['width'], 'crop' => 'crop', 'gravity' => 'xy_center', 'x' => $x, 'y' => $y, 'quality' => $quality];
        }
        
        return $sizes;
    }
    
    /**
     * Generate the cloudinary URLs for the list of sizes
     * 
     * @param array $sizes The list of sizes we need to generate
     * @param array $options Additional cloudinary options to apply to all sizes
     */
    protected function generateUrls($filename, $sizes, $options)
    {
        $result = [];
        
        foreach ($sizes AS $size) {
            $sizeOptions = array_merge($size, $options);
            $result[$size['width']] = cloudinary_url($filename, $sizeOptions);
        }
        
        return $result;
    }
    
    /**
     * Generate the srcset string
     * 
     * @param array $urls The list of Cloudinary URLs
     */
    protected function generateSrcSet($urls)
    {
        if (count($urls) < 2) return;
        
        $srcset = array_map(function($value, $key) {
            return $value.' '.$key.'w';
        }, array_values($urls), array_keys($urls));
        
        return implode(',', $srcset);
    }
}
