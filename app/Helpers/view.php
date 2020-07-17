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