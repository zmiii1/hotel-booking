<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        // Clear cache first
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard (Basic access)
            ['name' => 'dashboard.view', 'group_name' => 'Dashboard'],
            ['name' => 'reports.view', 'group_name' => 'Dashboard'],

            // Booking Management (Receptionist focus)
            ['name' => 'booking.view', 'group_name' => 'Booking Management'],
            ['name' => 'booking.create', 'group_name' => 'Booking Management'],
            ['name' => 'booking.edit', 'group_name' => 'Booking Management'],
            ['name' => 'booking.delete', 'group_name' => 'Booking Management'],
            ['name' => 'booking.status.update', 'group_name' => 'Booking Management'],
            ['name' => 'booking.export', 'group_name' => 'Booking Management'],
            ['name' => 'booking.reports', 'group_name' => 'Booking Management'],

            // Beach Ticket Management (Cashier focus)
            ['name' => 'ticket.dashboard', 'group_name' => 'Beach Ticket Management'],
            ['name' => 'ticket.view', 'group_name' => 'Beach Ticket Management'],
            ['name' => 'ticket.create', 'group_name' => 'Beach Ticket Management'],
            ['name' => 'ticket.edit', 'group_name' => 'Beach Ticket Management'],
            ['name' => 'ticket.update', 'group_name' => 'Beach Ticket Management'],
            ['name' => 'ticket.delete', 'group_name' => 'Beach Ticket Management'],
            ['name' => 'ticket.orders.view', 'group_name' => 'Beach Ticket Management'],
            ['name' => 'ticket.orders.manage', 'group_name' => 'Beach Ticket Management'],
            ['name' => 'ticket.export', 'group_name' => 'Beach Ticket Management'],

            // POS System (Cashier)
            ['name' => 'pos.access', 'group_name' => 'POS'],
            ['name' => 'pos.dashboard', 'group_name' => 'POS'],
            ['name' => 'pos.sell', 'group_name' => 'POS'],
            ['name' => 'pos.reports', 'group_name' => 'POS'],

            // Room Management (Admin level)
            ['name' => 'room.type.view', 'group_name' => 'Room Management'],
            ['name' => 'room.type.create', 'group_name' => 'Room Management'],
            ['name' => 'room.type.edit', 'group_name' => 'Room Management'],
            ['name' => 'room.type.delete', 'group_name' => 'Room Management'],
            ['name' => 'room.view', 'group_name' => 'Room Management'],
            ['name' => 'room.create', 'group_name' => 'Room Management'],
            ['name' => 'room.edit', 'group_name' => 'Room Management'],
            ['name' => 'room.delete', 'group_name' => 'Room Management'],
            ['name' => 'room.packages.manage', 'group_name' => 'Room Management'],
            ['name' => 'room.addons.manage', 'group_name' => 'Room Management'],

            // Promo Code Management (Admin level)
            ['name' => 'promo.view', 'group_name' => 'Promo Code'],
            ['name' => 'promo.create', 'group_name' => 'Promo Code'],
            ['name' => 'promo.edit', 'group_name' => 'Promo Code'],
            ['name' => 'promo.delete', 'group_name' => 'Promo Code'],

            // User Management (Admin level)
            ['name' => 'users.view', 'group_name' => 'User Management'],
            ['name' => 'users.create', 'group_name' => 'User Management'],
            ['name' => 'users.edit', 'group_name' => 'User Management'],
            ['name' => 'users.delete', 'group_name' => 'User Management'],

            // Role and Permission (Super Admin ONLY)
            ['name' => 'roles.view', 'group_name' => 'Role and Permission'],
            ['name' => 'roles.create', 'group_name' => 'Role and Permission'],
            ['name' => 'roles.edit', 'group_name' => 'Role and Permission'],
            ['name' => 'roles.delete', 'group_name' => 'Role and Permission'],
            ['name' => 'permissions.view', 'group_name' => 'Role and Permission'],
            ['name' => 'permissions.create', 'group_name' => 'Role and Permission'],
            ['name' => 'permissions.edit', 'group_name' => 'Role and Permission'],
            ['name' => 'permissions.delete', 'group_name' => 'Role and Permission'],
            ['name' => 'roles.permissions.manage', 'group_name' => 'Role and Permission'],

            // System Settings (Super Admin ONLY)
            ['name' => 'system.settings', 'group_name' => 'System'],
            ['name' => 'admin.management', 'group_name' => 'System'],
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name']
            ], $permission);
        }

        // Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $receptionist = Role::firstOrCreate(['name' => 'Receptionist']);
        $cashier = Role::firstOrCreate(['name' => 'Cashier']);

        // Clear existing permissions
        $superAdmin->syncPermissions([]);
        $admin->syncPermissions([]);
        $receptionist->syncPermissions([]);
        $cashier->syncPermissions([]);

        // SUPER ADMIN: Full access to everything
        $superAdmin->givePermissionTo(Permission::all());

        // ADMIN: Almost everything except Role & Permission management
        $admin->givePermissionTo([
            'dashboard.view', 'reports.view',
            'booking.view', 'booking.create', 'booking.edit', 'booking.delete', 'booking.status.update', 'booking.export', 'booking.reports',
            'ticket.dashboard', 'ticket.view', 'ticket.create', 'ticket.edit', 'ticket.update', 'ticket.delete', 'ticket.orders.view', 'ticket.orders.manage', 'ticket.export',
            'pos.access', 'pos.dashboard', 'pos.sell', 'pos.reports',
            'room.type.view', 'room.type.create', 'room.type.edit', 'room.type.delete', 'room.view', 'room.create', 'room.edit', 'room.delete', 'room.packages.manage', 'room.addons.manage',
            'promo.view', 'promo.create', 'promo.edit', 'promo.delete',
            'users.view', 'users.create', 'users.edit', 'users.delete',
        ]);

        // RECEPTIONIST: Booking Management ONLY
        $receptionist->givePermissionTo([
            'dashboard.view',
            'booking.view', 'booking.create', 'booking.edit', 'booking.delete', 'booking.status.update', 'booking.export', 'booking.reports',
        ]);

        // CASHIER: Beach Ticket Management ONLY  
        $cashier->givePermissionTo([
            'dashboard.view',
            'ticket.dashboard', 'ticket.view', 'ticket.create', 'ticket.edit', 'ticket.update', 'ticket.delete', 'ticket.orders.view', 'ticket.orders.manage', 'ticket.export',
            'pos.access', 'pos.dashboard', 'pos.sell', 'pos.reports',
        ]);

        $this->command->info('✅ Permissions and Roles created successfully!');
        $this->command->info('📋 Summary:');
        $this->command->info('- Super Admin: ' . $superAdmin->permissions->count() . ' permissions (ALL)');
        $this->command->info('- Admin: ' . $admin->permissions->count() . ' permissions');
        $this->command->info('- Receptionist: ' . $receptionist->permissions->count() . ' permissions (Booking only)');
        $this->command->info('- Cashier: ' . $cashier->permissions->count() . ' permissions (Beach Ticket only)');
    }
}
