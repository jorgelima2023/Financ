<?php

namespace Database\Seeders;

use App\Models\ContasCorrente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContaCorrente extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if( !ContasCorrente::where('nome', 'Caixa Local')->first() ){
            ContasCorrente::create(['nome' => 'Caixa Local']);
        }
        if( !ContasCorrente::where('nome', 'Bradesco')->first() ){
            ContasCorrente::create(['nome' => 'Bradesco', 'agencia' => '123', 'conta' => '123456', 'cod_compe' => '237']);
        }
        if( !ContasCorrente::where('nome', 'Itau')->first() ){
            ContasCorrente::create(['nome' => 'Itau', 'agencia' => '123', 'conta' => '123456', 'cod_compe' => '341']);
        }
    }
}
