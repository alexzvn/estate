<?php

/**
 * Get logged user
 *
 * @return \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User|null
 */
function user()
{
    return Illuminate\Support\Facades\Auth::user();
}

/**
 * Source to create an image from. The method responds to the following input types:
 * 
 * string - Path of the image in filesystem.
 * string - URL of an image (allow_url_fopen must be enabled).
 * string - Binary image data.
 * string - Data-URL encoded image data.
 * string - Base64 encoded image data.
 * resource - PHP resource of type gd. (when using GD driver)
 * object - Imagick instance (when using Imagick driver)
 * object - Intervention\Image\Image instance
 * object - SplFileInfo instance (To handle Laravel file uploads via Symfony\Component\HttpFoundation\File\UploadedFile)
 *
 * @param mixed $source
 * @return \Intervention\Image\Image
 */
function image($source)
{
    return Intervention\Image\Facades\Image::make($source);
}

/**
 * Remove all tag contain in html
 *
 * @param string $html
 * @return string
 */
function remove_tags(string $html)
{
    return preg_replace('/<\/?[\w\s]*>|<.+[\W]>/', '', $html);
}

function echoActiveIf($expression, $active = 'active')
{
    if (is_bool($expression) && $expression) {
        echo $active; return;
    }

    if (is_string($expression)) {
        echo request()->routeIs($expression) ? $active : '';
    }
}

/**
 * hide last three number of phone
 *
 * @param string $phone
 * @return string
 */
function hide_phone(string $phone)
{
    return substr($phone, 0, 7) . 'xxx';
}

/**
 * converter price int to humans string
 *
 * @param int $price
 * @return string
 */
function format_web_price($price)
{
    static $define;

    if (! $define) {
        $define = [
            1000000000 => 'Tỷ',
            1000000    => 'Triệu'
        ];
    }

    $price = (int) $price;

    foreach ($define as $number => $word) {
        if ($price / $number >= 0.98) {
            $price = $price/$number;
            return "$price $word";
        }
    }
}