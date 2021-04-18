<?php

$updater = file_get_contents(backup_path('mapper.ids'));
$updater = collect(explode("\n", trim($updater)));

$updater->each(function ($id) {
    [$uid, $id] = explode('|', $id);

    \Illuminate\Support\Facades\DB::table('users')->where('id', $uid)->update([
        'supporter_id' => $id
    ]);
});
