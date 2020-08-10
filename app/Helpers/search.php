<?php

function levenshtein_level_one(string $search, string $key = '_')
{
    $search = preg_split('//u', $search);

    $addOneChar = function (array $origin, $char)
    {
        foreach (range(0, count($origin) -1) as $i) {
            $word = $origin;
            array_splice($word, $i, 0, [$char]);

            $words[] = implode('', $word);
        }

        return $words ?? [];
    };

    $replaceOneChar = function (array $origin, $char)
    {
        foreach (range(0, count($origin) -1) as $i) {
            $word = $origin;
            $word[$i] = $char;

            $words[] = implode('', $word);
        }

        return $words ?? [];
    };

    $removeOneChar = function (array $origin, $char)
    {
        foreach (range(0, count($origin) -1) as $i) {
            $word = $origin;
            unset($word[$i]);

            $words[] = implode('', $word);
        }

        return $words ?? [];
    };

    return array_unique(array_merge(
        // $addOneChar($search, $key),
        // $removeOneChar($search, $key),
        $replaceOneChar($search, $key),
        [implode('', $search)]
    ));
}