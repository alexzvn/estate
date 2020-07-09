<?php

function echoActiveIf($expression, $active = 'active')
{
    if (is_bool($expression)) {
        echo $active; return;
    }

    if (is_string($expression)) {
        echo request()->route()->getName() === $expression ? $active : '';
    }
}