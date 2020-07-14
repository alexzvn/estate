<?php

namespace App\Repository;

use App\Contracts\RepositoryInterface;
use Illuminate\Support\Traits\ForwardsCalls;

abstract class BaseRepository implements RepositoryInterface
{
    use ForwardsCalls;

    /**
     * The model
     *
     * @var \Jenssegers\Mongodb\Eloquent\Model
     */
    protected $model;

    public function model()
    {
        return $this->model;
    }

    public function __call(string $method, array $args = [])
    {
        return $this->forwardCallTo($this->model, $method, $args);
    }

    public static function __callStatic(string $method, array $args = [])
    {
        $self = app()->make(get_called_class());

        return $self->__call($method, $args);
    }
}
