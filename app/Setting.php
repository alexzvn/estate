<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class Setting
{
    protected const FILE_NAME = 'config.dat';

    protected array $config = [];

    public function __construct() {
        if (Storage::exists(static::FILE_NAME)) {
            $this->config = static::load()->toArray();
        }
    }

    /**
     * Get value of config
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Set key config
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value)
    {
        $this->config[$key] = $value;

        $this->save();
    }

    /**
     * Compare data by key and value given
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function compare(string $key, $value)
    {
        return $this->get($key) == $value;
    }

    /**
     * Compare strict data by key and value given
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function compareStrict(string $key, $value)
    {
        return $this->get($key) === $value;
    }

    /**
     * Fill all config to model and save
     *
     * @param array $configs
     * @return void
     */
    public function fill(array $configs)
    {
        $this->config = array_merge($this->config, $configs);

        $this->save();
    }

    /**
     * Load exists settings
     *
     * @return static
     */
    public static function load()
    {
        if (! Storage::exists(static::FILE_NAME)) {
            return app(static::class);
        }

        return unserialize(Storage::get(static::FILE_NAME));
    }

    /**
     * Save current settings
     *
     * @return void
     */
    public function save()
    {
        Storage::put(static::FILE_NAME, serialize($this));
    }

    /**
     * Get all key value config
     *
     * @return array
     */
    public function toArray()
    {
        return $this->config;
    }

    public function __get(string $key)
    {
        return $this->get($key);
    }

    public function __set(string $key, $value)
    {
        $this->set($key, $value);
    }
}
