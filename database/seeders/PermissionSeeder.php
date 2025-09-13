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

            ['title'=> 'Listar usuarios', 'name' => 'index-user'],
            ['title'=> 'Visualizar usuarios', 'name' => 'show-user'],
            ['title'=> 'Cadastrar usuarios', 'name' => 'create-user'],
            ['title'=> 'Editar usuarios', 'name' => 'edit-user'],
            ['title'=> 'Editar senha usuarios', 'name' => 'edit-user-password'],
            ['title'=> 'Apagar usuarios', 'name' => 'destroy-user'],
            ['title'=> 'Gerar PDF usuarios', 'name' => 'generate-pdf-user'],

            ['title'=> 'Listar categoria', 'name' => 'index-categoria'],
            ['title'=> 'Visualizar categoria', 'name' => 'show-categoria'],
            ['title'=> 'Cadastrar categoria', 'name' => 'create-categoria'],
            ['title'=> 'Editar categoria', 'name' => 'edit-categoria'],
            ['title'=> 'Apagar categoria', 'name' => 'destroy-categoria'],

            ['title'=> 'Formulario categoria', 'name' => 'form-categoria'],
            ['title'=> 'Formulario forma-pgtos', 'name' => 'form-forma_pgto'],

            ['title'=> 'Listar papeis', 'name' => 'index-role'],
            ['title'=> 'Cadastrar papeis', 'name' => 'create-role'],
            ['title'=> 'Editar papeis', 'name' => 'edit-role'],
            ['title'=> 'Apagar papeis', 'name' => 'destroy-role'],

            ['title'=> 'Listar permissões papel', 'name' => 'index-role-permission'],
            ['title'=> 'Editar permissões papel', 'name' => 'update-role-permission'],

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
