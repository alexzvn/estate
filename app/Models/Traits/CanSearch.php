<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Jenssegers\Mongodb\Eloquent\Builder;

/**
 * 
 */
trait CanSearch
{
    protected static $indexField = 'index_meta';

    public function scopeFilterSearch(Builder $builder, $search = '')
    {
        $builder->whereRaw(['$text' => ['$search' => Str::lower($search)]])
            ->project(['score' => ['$meta' => 'textScore']])
            ->orderBy('score', ['$meta' => 'textScore']);
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

        $index .= Str::ascii(implode('. ', $index)) .'.';
        $index = Str::lower($index);

        return $this->forceFill([$this->indexField => $index])->save();
    }

    public function getIndexDocumentData()
    {
        return $this->attributesToArray();
    }
}
