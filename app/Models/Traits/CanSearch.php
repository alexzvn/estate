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
        return $builder->selectRaw(
            "*, MATCH (`$this->indexField`) AGAINST (? IN BOOLEAN MODE) AS score",
            [$this->fullTextWildcards($search)]
        );
    }

    public function scopeOrderByScore(Builder $builder)
    {
        return $builder->orderBy('score', 'desc');
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

    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {

            if (strlen($word) >= 2) {
                $words[$key] = '+' . $word . '*';
            }
        }

        return implode(' ', $words);
    }

    public function getIndexDocumentData()
    {
        return $this->attributesToArray();
    }
}
