<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Chiiya\FilamentAccessControl\Enumerators\RoleName;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * @var AdminUser $admin
         */
        $admin = AdminUser::firstOrCreate([
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'first_name' => 'Admin',
            'last_name' => 'Administrator',
            'expires_at' => new \DateTime('+6 months'),
        ]);

        $admin->assignRole(RoleName::SUPER_ADMIN);
    }
}
