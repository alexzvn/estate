<?php

/**
 * Map all ids from collection required backup data first
 *
 * @return void
 */
function mapIds()
{
    foreach (glob(backup_path('e/*.json')) as $collection) {
        $collection = str_replace('.json', '', $collection);
        $collection = explode('/', $collection);
        $collection = $collection[count($collection) - 1];

        saveCollectionIds($collection);
    }
}

/**
 * Get json data
 *
 * @param string $collection
 * @return array
 */
function get(string $collection, Mapper $mapper = null)
{
    $collection = file_get_contents(backup_path("e/$collection.json"));
    $collection = explode("\n", trim($collection));

    return array_map(function ($e) use ($mapper) {
        $e = json_decode($e, 1);

        if ($mapper === null) return $e;

        $e = $mapper->cast($e);

        foreach ($e as $key => $value) {
            if (is_array($value) || is_object($value)) {
                throw new Exception("`$key` mustn't array or object", 1);
            }
        }

        return $e;
    }, array_filter($collection, fn($e) => !empty($e)));
}

/**
 * Get id from oid
 *
 * @param string $collection
 * @param string $oid
 * @return int
 */
function id(string $collection, string $oid)
{
    static $jar;

    if (!isset($jar[$collection])) {
        $jar[$collection] = getCollectionIds($collection);
    }

    return isset($jar[$collection][$oid]) ? (int) $jar[$collection][$oid] : null;
}

/**
 * Get collection mapped id
 *
 * @param string $collection
 * @return array oid -> id
 */
function getCollectionIds(string $collection)
{
    $collection = file_get_contents(backup_path("id/$collection.ids"));

    $collection = explode("\n", trim($collection));

    foreach ($collection as $line) {
        [$oid, $id] = explode('-', $line);
        $ids[$oid] = $id;
    }

    return $ids ?? [];
}

function saveCollectionIds(string $collection, Closure $makeIds = null)
{
    $data = '';

    if ($makeIds === null) {
        $makeIds = function () use ($collection) {
            $collect = get($collection, new Mapper);
            for ($i=0, $n = count($collect); $i < $n; $i++) { 
                $ids[$collect[$i]['_id']] = $i + 1;
            }
            return $ids ?? [];
        };
    }

    foreach ($makeIds() as $oid => $id) {
        $data .= "$oid-$id\n";
    }

    return file_put_contents(backup_path("id/$collection.ids"), trim($data));
}

function restore(string $table, string $file, int $chunk = 0)
{
    $table = Illuminate\Support\Facades\DB::table($table);

    $data = require_once $file;

    $insert = function ($data) use ($table) {
        return $table->insert($data);
    };

    if ($chunk < 1) {
        return $insert($data);
    }

    foreach (array_chunk($data, $chunk) as $dataInsert) {
        $insert($dataInsert);
    }
}

function test(string $collection)
{

    foreach (get($collection) as $post) {
        foreach ($post as $key => $value) {
            if (! isset($fields[$key])) $fields[$key] = $value;
        }
    }

    return $fields ?? [];
}
