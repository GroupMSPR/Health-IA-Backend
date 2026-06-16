<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration {
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
            'view-goals', 'create-goals', 'update-goals', 'delete-goals',
            'view-constraints', 'create-constraints', 'update-constraints', 'delete-constraints',
            'view-equipments', 'create-equipments', 'update-equipments', 'delete-equipments',
            'view-muscles', 'create-muscles', 'update-muscles', 'delete-muscles',
            'view-subscriptions', 'create-subscriptions', 'update-subscriptions', 'delete-subscriptions',
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
            'view-health-metrics', 'create-health-metrics', 'update-health-metrics', 'delete-health-metrics',
            'view-goals', 'create-goals', 'update-goals', 'delete-goals',
            'view-constraints',
            'view-equipments',
            'view-muscles',
            'view-subscriptions',
        ]);

        $coachRole = Role::firstOrCreate(['name' => 'coach', 'guard_name' => 'api']);
        $coachRole->givePermissionTo([
            'view-users',
            'view-exercises', 'create-exercises', 'update-exercises', 'delete-exercises',
            'view-foods', 'create-foods', 'update-foods', 'delete-foods',
            'view-health-metrics',
            'view-goals', 'create-goals', 'update-goals',
            'view-constraints', 'create-constraints', 'update-constraints',
            'view-equipments', 'create-equipments', 'update-equipments',
            'view-muscles', 'create-muscles', 'update-muscles',
            'view-subscriptions',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::whereIn('name', ['admin', 'user', 'coach'])->delete();

        Permission::where(function ($query) {
            $query->whereLike('name', '%-users')
                ->orWhereLike('name', '%-exercises')
                ->orWhereLike('name', '%-foods')
                ->orWhereLike('name', '%-health-metrics')
                ->orWhereLike('name', '%-goals')
                ->orWhereLike('name', '%-constraints')
                ->orWhereLike('name', '%-equipments')
                ->orWhereLike('name', '%-muscles')
                ->orWhereLike('name', '%-subscriptions');
        })->delete();
    }
};
