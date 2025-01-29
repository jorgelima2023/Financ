<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Categoria::where('nome', 'Categoria - C1')->first()) {
            Categoria::create([
                'nome' => 'Categoria - C1',
                'classif' => '01',
                'soma' => 'N',
            ]);
        }

        if (!Categoria::where('nome', 'Categoria - C2')->first()) {
            Categoria::create([
                'nome' => 'Categoria - C2',
                'classif' => '01',
                'soma' => 'N',
            ]);
        }
    }
}
