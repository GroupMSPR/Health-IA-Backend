<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view-users', 'create-users', 'update-users', 'delete-users',
            'view-exercises', 'create-exercises', 'update-exercises', 'delete-exercises',
            'view-foods', 'create-foods', 'update-foods', 'delete-foods',
            'view-health-metrics', 'create-health-metrics', 'update-health-metrics', 'delete-health-metrics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
        $userRole->givePermissionTo([
            'view-exercises',
            'view-foods',
            'view-health-metrics', 'create-health-metrics', 'update-health-metrics', 'delete-health-metrics'
        ]);

        $coachRole = Role::firstOrCreate(['name' => 'coach', 'guard_name' => 'api']);
        $coachRole->givePermissionTo([
            'view-users',
            'view-exercises', 'create-exercises', 'update-exercises', 'delete-exercises',
            'view-foods', 'create-foods', 'update-foods', 'delete-foods',
            'view-health-metrics'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::whereIn('name', ['admin', 'user', 'coach'])->delete();
        Permission::whereLike('name', '%-users')->orWhereLike('name', '%-exercises')
            ->orWhereLike('name', '%-foods')->orWhereLike('name', '%-health-metrics')->delete();
    }
};
