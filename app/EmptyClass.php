<?php

namespace App;

class EmptyClass
{
    protected $container = [];

    public function __get(string $key)
    {
        return $this->container[$key] ?? null;
    }

    public function __set(string $key, $value)
    {
        $this->container[$key] = $value;
    }
}
