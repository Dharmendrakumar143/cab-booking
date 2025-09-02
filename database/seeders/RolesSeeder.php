<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'admin']);
          Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);
        //   Role::firstOrCreate(['name' => 'driver', 'guard_name' => 'admin']);
          Role::firstOrCreate(['name' => 'independent-contractors', 'guard_name' => 'admin']);
          Role::firstOrCreate(['name' => 'employees', 'guard_name' => 'admin']);
          Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
    }
}
