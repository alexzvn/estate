<?php

namespace App\Repository;

use App\Contracts\RepositoryInterface;
use Illuminate\Support\Traits\ForwardsCalls;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @method \Jenssegers\Mongodb\Eloquent\Model fill(array $attr = [])
 * @method \Jenssegers\Mongodb\Eloquent\Model forceFill(array $attr = [])
 * @method static \Jenssegers\Mongodb\Eloquent\Model findOrFail(string $id)
 * @method static \Jenssegers\Mongodb\Eloquent\Model where(string $column, $operation, $value)
 * @method static \Jenssegers\Mongodb\Eloquent\Model find(string $id)
 */
abstract class BaseRepository implements RepositoryInterface
{
    use ForwardsCalls;

    /**
     * The model
     *
     * @var \Jenssegers\Mongodb\Eloquent\Model
     */
    protected $model;

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    public function model()
    {
        return $this->model;
    }

    public function __get(string $key)
    {
        return $this->model->$key;
    }

    public function __set(string $key, $value)
    {
        $this->model->$key = $value;
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
