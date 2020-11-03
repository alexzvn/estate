<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

/**
 * 
 */
trait CanSearch
{
    protected $indexField = 'index_meta';

    public function scopeSearch(Builder $builder, $search = '')
    {
        return $builder->whereRaw([
            '$text' => [
                '$search' => Str::ascii(Str::lower($search))
                ]
            ]);
    }

    public function scopeOrderByScore(Builder $builder)
    {
        return $builder->project(['score' => ['$meta' => 'textScore']])
            ->orderBy('score', ['$meta' => 'textScore']);
    }

    public function index()
    {
        $attr = $this->getIndexDocumentData();

        unset($attr[$this->indexField]);

        foreach ($attr as $key => $value) {
            if (is_string($value)) {
                $values[] = $value;
            }
        }

        $index = implode('. ', $values ?? []);
        $index = Str::lower($index);
        $index .= '. ' . Str::ascii($index);

        return $this->forceFill(['index_meta' => $index])->save();
    }

    public function getIndexDocumentData()
    {
        return $this->attributesToArray();
    }
}
