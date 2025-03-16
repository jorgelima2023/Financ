<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Role::where('name', 'Super Admin')->first()) {
            Role::create(['name' => 'Super Admin', ]);
        }
    
    if (!Role::where('name', 'Admin')->first()) {
            $admin = Role::create(['name' => 'Admin', ]);
        } else {
            $admin = Role::where('name', 'Admin')->first();
        }
        // Cadastrar permissão para o papel
        $admin->givePermissionTo([
            'index-categoria', 'show-categoria', 'create-categoria', 'edit-categoria', 'destroy-categoria',
        ]);
    
    }

}
