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

    public function scopeFilterSearch(Builder $builder, $value = '')
    {
        $builder->where(function ($builder) use ($value)
        {
            foreach (levenshtein_level_one($value, '%') as $keyword) {
                $builder->orWhere($this->indexField, 'like', "%$keyword%");
            }
        });
    }

    public function index()
    {
        foreach ($this->getIndexDocumentData() as $key => $value) {
            $index[] = $value;
        }

        $index = trim(implode('. ', $index));
        $index = $index . Str::ascii($index) .'.';

        $this->forceFill([$this->indexField => $index])->save();
    }

    public function getIndexDocumentData()
    {
        return $this->attributesToArray();
    }
}
