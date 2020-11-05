<?php

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class Mapper
{
    protected $caster;

    protected $rename;

    public function __construct(array $caster = [], array $rename = []) {
        $this->caster = $caster;
        $this->rename = $rename;
    }

    public function cast(array $data)
    {
        $data['_id'] = $data['_id']['$oid'];

        foreach ($this->caster as $field => $type) {
            $data[$field] = $this->castValue($data[$field] ?? null, $type, $data);
        }

        foreach ($this->rename as $old => $new) {
            if (! isset($data[$old])) continue;

            $data[$new] = $data[$old]; unset($data[$old]);
        }

        return $this->removeUnnecessary($data);
    }

    protected function removeUnnecessary(array $data)
    {
        $allowedKey = array_merge(
            array_keys($this->caster),
            array_values($this->rename)
        );

        foreach ($data as $key => $value) {
            if (! in_array($key, $allowedKey)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    protected function castValue($value, string $type, $data)
    {
        if ($value === null) return $value;

        switch (true) {
            case $type === 'null': return null;
            case $type instanceof Closure: return $type($value, $data);
            case $type === 'datetime': return $this->date($value, $type);
            case Str::startsWith($type, 'id') : return $this->id($value, $type);
            case $type === 'int': return $this->int($value);
            case $type === 'boolean': return $this->boolean($value);
        }

        return $value;
    }

    protected function date($value, $key)
    {
        return Carbon::parse($value['$date'])->format('Y-m-d H:i:s');
    }

    protected function id($value, $key)
    {
        [$junk, $collection] = explode('.', $key);

        return id($collection, $value);
    }

    protected function int($value)
    {
        return $value > PHP_INT_MAX || $value < 0 ? null : (int) $value;
    }

    protected function string($value)
    {
        return $value;
    }

    public function boolean($value)
    {
        return (bool) $value;
    }
}
