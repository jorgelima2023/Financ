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
        if (!User::where('email', 'cesar3@celke.com.br')->first()) {
            $superAdmin = User::create([
                'name' => 'Cesar3',
                'email' => 'cesar3@celke.com.br',
                'password' => Hash::make('123456a', ['rounds' => 12])
            ]);

            // Atribuir papel para o usuário
            $superAdmin->assignRole('Super Admin');
        }

        if (!User::where('email', 'kelly3@celke.com.br')->first()) {
            $Admin = User::create([
                'name' => 'Kelly3',
                'email' => 'kelly3@celke.com.br',
                'password' => Hash::make('123456a', ['rounds' => 12])
            ]);

            // Atribuir papel para o usuário
            $Admin->assignRole('Admin');
        }
    }
}
