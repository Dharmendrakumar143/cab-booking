<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guardName = 'admin'; // Change this if your guard is different
        
        // Define Permissions
        $permissions = [
            'view rides', 
            'view ride details', 
            'delete rides',
            'view payments', 
            'view payment details', 
            'delete payments',
        ];
    
        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => $guardName]);
        }

        // Retrieve All Permissions
        $allPermissions = Permission::where('guard_name', $guardName)->get();

        // Create Roles
        $userRole = Role::firstOrCreate(['name' => 'independent-contractors']);
        $employeesRole = Role::firstOrCreate(['name' => 'employees']);

        // Assign All Permissions to Both Roles
        $userRole->syncPermissions($allPermissions);
        $employeesRole->syncPermissions($allPermissions);

    }

}
