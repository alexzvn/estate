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

        foreach ($data as $key => $value) {
            if (!isset($this->caster[$key])) continue;

            if ($this->caster[$key] === 'empty') {
                unset($data[$key]); continue;
            }

            if ($value === null) {
                continue;
            }

            $data[$key] = $this->castValue($value, $key, $data);
        }

        foreach ($this->rename as $old => $new) {
            if (! isset($data[$old])) continue;

            $data[$new] = $data[$old]; unset($data[$old]);
        }

        return $data;
    }

    protected function castValue($value, string $key, $data)
    {
        $type = $this->caster[$key];

        switch (true) {
            case $type instanceof Closure: return $type($value, $data);
            case $type === 'null': return null;
            case $type === 'datetime': return $this->date($value, $type);
            case Str::startsWith($type, 'id') : return $this->id($value, $type);
            case $type === 'int': return $this->int($value);
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
}
