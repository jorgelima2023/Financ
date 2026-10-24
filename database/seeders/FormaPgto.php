<?php

namespace Database\Seeders;

use App\Models\Forma_pgto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormaPgto extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if( !Forma_pgto::where('nome', 'Cartao Debito')->first()) {
            Forma_pgto::create(['nome' => 'Cartao Debito']);
        }
        if( !Forma_pgto::where('nome', 'Dinheiro')->first()) {
            Forma_pgto::create(['nome' => 'Dinheiro']);
        }
        if( !Forma_pgto::where('nome', 'Cartao Credito')->first()) {
            Forma_pgto::create(['nome' => 'Cartao Credito']);
        }
        if( !Forma_pgto::where('nome', 'Outros')->first()) {
            Forma_pgto::create(['nome' => 'Outros']);
        }
        if( !Forma_pgto::where('nome', 'PIX')->first()) {
            Forma_pgto::create(['nome' => 'PIX']);
        }
        if( !Forma_pgto::where('nome', 'Boleto')->first()) {
            Forma_pgto::create(['nome' => 'Boleto']);
        }
        if( !Forma_pgto::where('nome', 'Inter')->first()) {
            Forma_pgto::create(['nome' => 'Inter']);
        }
        if( !Forma_pgto::where('nome', 'Visa Credito')->first()) {
            Forma_pgto::create(['nome' => 'Visa Credito']);
        }
    }
}
