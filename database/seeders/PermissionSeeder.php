<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['title'=> 'Listar categoria', 'name' => 'index-categoria'],
            ['title'=> 'Visualizar categoria', 'name' => 'show-categoria'],
            ['title'=> 'Cadastrar categoria', 'name' => 'create-categoria'],
            ['title'=> 'Editar categoria', 'name' => 'edit-categoria'],
            ['title'=> 'Apagar categoria', 'name' => 'destroy-categoria'],
        ];

        // pega a model 'permission'
        foreach ($permissions as $permission) {
            $existingPermission = Permission::where('name', $permission['name'])->first();

            if (!$existingPermission) {
                Permission::create([
                    'title' => $permission['title'],
                    'name' => $permission['name'],
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
