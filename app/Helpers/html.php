<?php

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