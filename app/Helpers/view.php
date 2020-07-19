<?php

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