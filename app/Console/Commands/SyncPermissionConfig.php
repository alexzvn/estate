<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

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
        Permission::whereNotNull('group_id')->update(['group_id' => null]);

        $permissions = $this->syncPermissions();

        $this->syncGroups($permissions);

        Artisan::call('cache:clear');
    }

    public function syncPermissions()
    {
        $permissions = Permission::all();

        return collect($this->getPermissions())->map(function ($name, $key) use ($permissions) {
            if ($permission = $permissions->where('name', $key)->first()) {
                return $permission;
            }

            return Permission::forceCreate([
                'name' => $key,
                'display_name' => $name
            ]);
        });
    }

    public function syncGroups(Collection $permissions)
    {
        $groups = collect($this->getPermissionGroups())->map(fn($group) => (object) $group);

        PermissionGroup::whereNotIn('name', $groups->pluck('name'))->delete();

        $all = PermissionGroup::all();

        $groups->map(function ($item) use ($permissions, $all) {
            $group = $all->firstWhere('name', $item->name) ?: PermissionGroup::forceCreate([
                'name' => $item->name
            ]);

            $group->permissions()->saveMany(
                $permissions->whereIn('name', $item->permissions)
            );
        });
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
