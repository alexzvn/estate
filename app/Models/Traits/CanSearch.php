<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Jenssegers\Mongodb\Eloquent\Builder;

/**
 * 
 */
trait CanSearch
{
    protected $indexField = 'index_meta';

    public function scopeFilterSearch(Builder $builder, $search = '')
    {
        $search = Str::lower($search);

        $builder->where($this->indexField, 'like', "%$search%");
    }

    public function index()
    {
        $attr = $this->getIndexDocumentData();

        unset($attr[$this->indexField]);

        foreach ($attr as $key => $value) {
            if (is_string($value)) {
                $index[] = $value;
            }
        }

        $index = implode('. ', $index);
        $index .= '. ' . Str::ascii($index) .'.';
        $index = Str::lower($index);

        return $this->forceFill([$this->indexField => $index])->save();
    }

    public function getIndexDocumentData()
    {
        return $this->attributesToArray();
    }
}
