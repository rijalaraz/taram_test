<?php

namespace Database\Seeders;

use Chiiya\FilamentAccessControl\Enumerators\PermissionName;
use Chiiya\FilamentAccessControl\Enumerators\RoleName;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * @var array<int, RoleName>
     */
    protected static array $roles = [RoleName::SUPER_ADMIN];

    /**
     * @var array<int, string>
     */
    protected static array $permissions = [
        PermissionName::UPDATE_ADMIN_USERS,
        PermissionName::UPDATE_PERMISSIONS,
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::$permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => config('filament-access-control.guard_name', 'filament'),
            ]);
        }

        foreach (self::$roles as $role) {
            /** @var Role $role */
            $role = Role::firstOrCreate([
                'name' => $role,
                'guard_name' => config('filament-access-control.guard_name', 'filament'),
            ]);

            foreach (self::$permissions as $permission) {
                $role->givePermissionTo($permission);
            }
        }
    }
}
