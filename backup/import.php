<?php

require_once __DIR__ . '/importer/helper/Mapper.php';
require_once __DIR__ . '/importer/helper/functions.php';

mapIds();

$restores = [
    'provinces'      => 0,
    'districts'      => 0,
    'wards'          => 0,
    'users'          => 1,
    'whitelists'     => 1,
    'blacklists'     => 1,
    'categories'     => 1,
    'permissions'    => 1,
    'roles'          => 1,

    'plans'          => 1,
    'subscriptions'  => 1,
    'posts'          => 1,

    'orders'         => 1,
    'notes'          => 1,
    'logs'           => 0,
    'reports'        => 0,
    'tracking_posts' => 0,
    'audits'         => 1,

    'category_post' => 1,
    'order_plan'    => 1,
    'plan_province' => 1,
    'category_plan' => 1,
    'province_user' => 1,

    'sms_templates'  => 0,
    'sms_histories'  => 0,
    'keywords'       => 1,

    'failed_jobs'    => 10,

    'post_user_blacklist'   => 1,
    'post_user_save'        => 1,
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
