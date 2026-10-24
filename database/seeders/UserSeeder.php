<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        if (!User::where('email', 'admin@financ.com.br')->first()) {
            $superAdmin = User::create([
                'name' => 'admin',
                'email' => 'admin@financ.com.br',
                'password' => Hash::make('123456a', ['rounds' => 12])
            ]);

            // Atribuir papel para o usuário:
            // caso no login aparecer: perfil "nao atribuido"
            //      ver tabela select * from roles r a id
            //      ver tabela select * from model_has_roles
            //          role_id 1 super admin e model_id = 1 user super admin
            //          se nao estiver assim, rodar o select e depois o update
            //          update model_has_roles set role_id = 1 , model_id = 1
            $superAdmin->assignRole('Super Admin');
        }

        if (!User::where('email', 'financeiro@financ.com.br')->first()) {
            $Admin = User::create([
                'name' => 'financeiro',
                'email' => 'financeiro@financ.com.br',
                'password' => Hash::make('123456a', ['rounds' => 12])
            ]);

            // Atribuir papel para o usuário
            $Admin->assignRole('Admin');
        }
    }
}
