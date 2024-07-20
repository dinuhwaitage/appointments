<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creating roles
        Role::create(['name' => 'ADMIN']);
        Role::create(['name' => 'DOCTOR']);
        Role::create(['name' => 'STAFF']);
        Role::create(['name' => 'PATIENT']);
        
    }
}
