<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('roles')->upsert([
            [
                'name' => 'Super Admin',
                'slug' => 'superadmin',
                'description' => 'Full access tenant system',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Manage users and master data',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Host',
                'slug' => 'host',
                'description' => 'View schedule & attendance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Telco',
                'slug' => 'telco',
                'description' => 'Manage hosts & schedules',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KA',
                'slug' => 'ka',
                'description' => 'Manage studio, brand & schedule',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['slug'], ['name', 'description', 'updated_at']);
    }
}
