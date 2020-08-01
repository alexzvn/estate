<?php

namespace App\Models\Traits;

use Elasticquent\ElasticquentResultCollection;
use Illuminate\Support\Facades\Http;
use Jenssegers\Mongodb\Eloquent\Builder;

/**
 * 
 */
trait ElasticquentSearch
{
    public function scopeFilterSearch(Builder $builder, $value = '')
    {
        $keyName = $this->getKeyName();

        $filter = $this->esearch($value ?? '')->reduce(function ($carry, $model) use ($keyName)
        {
            $carry[] = $model->$keyName;

            return $carry;
        }, []);

        return $builder->whereIn($keyName, $filter);
    }

    public static function esearch(string $query = '')
    {
        $response = self::fetchSearch($query, self::getInstance());

        if (! $response->successful()) {
            return new ElasticquentResultCollection([]);
        }

        $body = $response->json();

        if (empty($body['hits']['total']['value'])) {
            return new ElasticquentResultCollection([], $body);
        }

        foreach ($body['hits']['hits'] as $item) {
            $models[] = self::getInstance()->forceFill(
                array_merge(['_id'=> $item['_id']], $item['_source'] ?? [])
            );
        }

        return new ElasticquentResultCollection($models, $body);
    }

    private static function fetchSearch(string $query, $instance)
    {
        return Http::get(self::getSearchUrl($instance), [
            'q' => $query,
            'size' => 1000
        ]);
    }

    private static function getSearchUrl($instance)
    {
        $url = config('elasticquent.config.hosts', []);
        $url = [$url[0], config('elasticquent.default_index'), $instance->getTable()];
        $url = implode('/', $url) . '/_search';

        return $url;
    }

    private static function getInstance(...$params)
    {
        return new self(...$params);
    }
}
