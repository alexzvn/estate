<?php

namespace App;

/**
 * An empty class for storage simple OOP data
 * instead of using stdClass that when call
 * some variable doesn't exists will throw waring
 */
class EmptyClass
{
    /**
     *
     * @var \stdClass
     */
    protected $container;

    public function __construct() {
        $this->container = new \stdClass;
    }

    public function __get(string $key)
    {
        return $this->container->$key ?? null;
    }

    public function __set(string $key, $value)
    {
        $this->container->$key = $value;
    }
}
