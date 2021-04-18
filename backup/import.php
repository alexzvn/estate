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
    'files'          => 1,

    'orders'         => 1,
    'notes'          => 1,
    'logs'           => 1,
    'reports'        => 1,
    'tracking_posts' => 1,
    'audits'         => 1,

    'category_post' => 1,
    'order_plan'    => 1,
    'plan_province' => 1,
    // // 'category_plan' => 1,
    'province_user' => 1,

    'sms_templates'  => 0,
    'sms_histories'  => 0,
    'keywords'       => 1,

    // 'failed_jobs'    => 10,

    'post_user_blacklist'   => 1,
    'post_user_save'        => 1,
    'role_has_permissions'  => 1,
    'model_has_roles'       => 1,
    'model_has_permissions' => 1,
];

$allowErrors = [
    'whitelists',
    'blacklists',
    'notes',
    'tracking_posts',
    'audits'
];

foreach ($restores as $table => $chunk) {

    $chunk = $chunk === 0 ? 2000 : $chunk;

    echo "Import [$table] with $chunk/insert... \n";

    try {
        restore($table, backup_path("importer/$table.php"), $chunk);
    } catch (\Throwable $th) {
        if (! in_array($table, $allowErrors)) {
            throw $th;
        }
    }
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
