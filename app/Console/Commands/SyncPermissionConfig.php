<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use Illuminate\Console\Command;

class SyncPermissionConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->syncPermission();
        $this->syncRoles();
        $this->syncPermissionGroup();
    }


    protected function syncPermission()
    {
        foreach ($this->getPermissions() as $name => $displayName) {
            $perm = Permission::findOrCreate($name);
            $perm->display_name = $displayName;
            $perm->save();
        }
    }

    public function syncRoles()
    {
        $syncRoles = function ($role)
        {
            $model = Role::where('name', $role->name)
                    ->firstOrCreate(['name' => $role->name]);

            $permWillSync = Permission::all()->filter(
                function (Permission $perm) use ($role) {
                    return in_array($perm->name, $role->permissions);
                }
            );

            foreach ($permWillSync as $perm) {
               $model->permissions()->save($perm);
            }
        };

        foreach ($this->getRoles() as $role) {
            $role = (object) $role;
            $syncRoles($role);
        }
    }

    protected function syncPermissionGroup()
    {

        $syncGroup = function ($group)
        {
            $groupModel = PermissionGroup::where('name', $group->name)
                            ->firstOrCreate(['name' => $group->name]);

            $permWillSync = Permission::all()->filter(
                function (Permission $perm) use ($group) {
                    return in_array($perm->name, $group->permissions);
                }
            );

            foreach ($permWillSync as $perm) {
                $groupModel->permissions()->save($perm);
            }
        };

        foreach ($this->getPermissionGroups() as $group) {
            $group = (object) $group;
            $syncGroup($group);
        }
    }

    private function getPermissions()
    {
        return config('permission.sync.permissions', []);
    }

    private function getRoles()
    {
        return config('permission.sync.roles', []);
    }

    private function getPermissionGroups()
    {
        return config('permission.sync.groups', []);
    }
}
