<?php
// database/seeders/RolesAndPermissionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\PermissionKey; // Import your Enum
use App\Enums\RoleKey;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Get the list of permissions from the Enum
        $enumPermissions = collect(PermissionKey::cases())->map(fn($enum) => $enum->value);

        // --- SYNCHRONIZE PERMISSIONS ---

        // 2. DROP Mismatched Permissions
        // Delete permissions in DB that are NOT in the Enum
        Permission::query()
            ->whereNotIn('name', $enumPermissions)
            ->delete();

        // 3. CREATE/UPDATE Permissions from the Enum
        foreach ($enumPermissions as $permissionName) {
            Permission::updateOrCreate(
                ['name' => $permissionName, 'guard_name' => 'api'], // API guard is common for APIs
                ['name' => $permissionName, 'guard_name' => 'api']
            );
        }

        // --- SEED ROLES AND ASSIGN PERMISSIONS ---

        // Get the newly synced permissions from the DB
        $allPermissions = Permission::whereIn('name', $enumPermissions)->get();

        $adminPermissions = $allPermissions->whereIn('name', []);

        // Define specific groups of permissions for clarity
        $studentPermissions = $allPermissions->whereIn('name', []);

        // --- Create Roles ---

        // 1. Super Admin (Can do everything)
        $superAdmin = Role::updateOrCreate(['name' => RoleKey::SUPER_ADMIN, 'guard_name' => 'api']);
        $superAdmin->syncPermissions($allPermissions);

        // 2. Editor Role
        $admin = Role::updateOrCreate(['name' => RoleKey::ADMIN, 'guard_name' => 'api']);
        $admin->syncPermissions($adminPermissions); // Only post permissions

        // 3. Basic User Role
        $student = Role::updateOrCreate(['name' => RoleKey::STUDENT, 'guard_name' => 'api']);
        $student->givePermissionTo($studentPermissions); // Only view posts
    }
}
