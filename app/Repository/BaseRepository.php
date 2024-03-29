<?php

namespace App\Repository;

use App\Contracts\RepositoryInterface;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Database\Eloquent\Model;

/**
 * @method \Illuminate\Database\Eloquent\Model fill(array $attr = [])
 * @method \Illuminate\Database\Eloquent\Model forceFill(array $attr = [])
 * @method static \Illuminate\Database\Eloquent\Model findOrFail(string $id)
 * @method static \Illuminate\Database\Eloquent\Model where(string $column, $operation, $value)
 * @method static \Illuminate\Database\Eloquent\Model find(string $id)
 */
abstract class BaseRepository implements RepositoryInterface
{
    use ForwardsCalls;

    /**
     * The model
     *
     * @var \Illuminate\Database\Eloquent\Model
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
