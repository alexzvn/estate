<?php

require_once __DIR__ . '/importer/helper/Mapper.php';
require_once __DIR__ . '/importer/helper/functions.php';

// mapIds();

foreach (glob(backup_path('importer/*.php')) as $file) {
    $table = explode('/', $file);
    $table = str_replace('.php', '', $table[count($table) - 1]);

    restore($table, $file, 1);
}

/**
 * Get backup path
 *
 * @param string $path
 * @return string
 */
function backup_path(string $path = null)
{
    if ($path) {
        return __DIR__ . "/$path";
    }

    return __DIR__;
}