<?php

function echoActiveIf(bool $should, $active = 'active')
{
    if ($should) echo $active;
}