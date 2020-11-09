<?php

require_once __DIR__ . '/importer/helper/Mapper.php';
require_once __DIR__ . '/importer/helper/functions.php';

// mapIds();

$restores = [
    'provinces'      => 0,
    'districts'      => 0,
    'wards'          => 0,
    'users'          => 0,
    'whitelists'     => 1,
    'blacklists'     => 1,
    'categories'     => 0,
    'permissions'    => 0,
    'roles'          => 0,

    'plans'          => 0,
    'subscriptions'  => 0,
    'posts'          => 1,

    'orders'         => 1,
    'notes'          => 0,
    'logs'           => 0,
    'reports'        => 0,
    'tracking_posts' => 1,
    'audits'         => 1,

    'category_post' => 0,
    'order_plan'    => 0,
    'plan_province' => 0,
    'category_plan' => 0,
    'plan_types'    => 0,

    'post_user_blacklist'   => 0,
    'post_user_save'        => 0,
    'role_has_permissions'  => 1,
    'model_has_roles'       => 1,
    'model_has_permissions' => 1,

];

foreach ($restores as $table => $chunk) {

    $chunk = $chunk === 0 ? 2000 : $chunk;

    echo "Import [$table] with $chunk/insert... \n";

    restore($table, backup_path("importer/$table.php"), $chunk);
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