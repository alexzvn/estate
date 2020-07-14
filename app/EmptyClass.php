<?php

namespace App;

/**
 * An empty class for storage simple OOP data
 * instead of using stdClass that when call
 * some variable doesn't exists will throw waring
 */
class EmptyClass
{
    public function __get(string $key)
    {
        return $this->$key ?? null;
    }

    public function __set(string $key, $value)
    {
        $this->$key = $value;
    }
}
