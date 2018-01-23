# Cloudinary Volume for Craft 3

This will add a new asset volume for Cloudinary and supports srcset generation.


## Using in Twig

Use `craft.cloudinary.transform(image, sizes, options)` in your twig templates.

Where:

`image` is a Craft Asset Model
`sizes` is an array of sizes you want to generate this image as. This also supports any cloudinary option per size.
`options` is an array of additional options including any cloudinary option that you want to apply to all sizes.

Example:

    {% set result = craft.cloudinary.transform(image, [{width:375, height:675, quality: 80},{width: 1440, height: 675}], {scaleAndCrop: true}) %}
    
This example will return an array with the following keys:

- src
- alt
- srcset

Where srcset is only applicable if you supplied more than one size.
Every size must have at least a width specified, even if using Cloudinarys transformation array, this will be used for the srcset generation.


## Using in Plugins

Include Cloudinary with:

    use futureactivities\cloudinary\Plugin as Cloudinary;
    
and then use:

    Cloudinary::getInstance()->transform->image($image, $sizes, $options);
    
    
## Additional Options

The options parameter can be used to specify additional cloudinary options to apply
to all sizes.

You can also add the following custom options:

- `scaleAndCrop` - Setting this to true will adjust the sizes array to first scale and then crop using the Craft 3 focal points.
    

## Twig Filters

The transform method will return an array that you can use in your image tag. Optionally a twig filter has been provided to generate this for you:

    {{ craft.cloudinary.transform(image, [{width:375, height:675},{width: 1440, height: 675}], {scaleAndCrop: true}) | buildImg }}
    
The filter also supports the following parameters:

- `sizes` - the value of the sizes attribute