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
    // var_dump($expression);

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