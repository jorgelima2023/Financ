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
        if (!Categoria::where('nome', 'ENTRADAS')->first()) {
            Categoria::create([ 'nome' => 'ENTRADAS', 'classif' => '1', 'soma' => '0', 'lcto' => '0' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'TOTAL ENTRADAS')->first()) {
            Categoria::create([ 'nome' => 'TOTAL ENTRADAS', 'classif' => '1.1', 'soma' => '0', 'lcto' => '0' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'Saldo Anterior')->first()) {
            Categoria::create([ 'nome' => 'Saldo Anterior', 'classif' => '1.1.1', 'soma' => '0', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'Salario')->first()) {
            Categoria::create([ 'nome' => 'Salario', 'classif' => '1.1.1', 'soma' => '0', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'PIX Recebido')->first()) {
            Categoria::create([ 'nome' => 'PIX Recebido', 'classif' => '1.1.3', 'soma' => '0', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'Acerto cx entradas')->first()) {
            Categoria::create([ 'nome' => 'Acerto cx entradas', 'classif' => '1.1.6', 'soma' => '0', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'Transferencias CC entradas')->first()) {
            Categoria::create([ 'nome' => 'Transferencias CC entradas', 'classif' => '1.1.9', 'soma' => '0', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'DESPESAS')->first()) {
            Categoria::create([ 'nome' => 'DESPESAS', 'classif' => '2', 'soma' => '0', 'lcto' => '0' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'TOTAL DESPESAS')->first()) {
            Categoria::create([ 'nome' => 'TOTAL DESPESAS', 'classif' => '2.1', 'soma' => '0', 'lcto' => '0' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'DESPESAS FIXAS')->first()) {
            Categoria::create([ 'nome' => 'DESPESAS FIXAS', 'classif' => '2.1.1', 'soma' => '0', 'lcto' => '0' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'Luz desp fixas')->first()) {
            Categoria::create([ 'nome' => 'Luz desp fixas', 'classif' => '2.1.1.2', 'soma' => '1', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'Celular desp fixas')->first()) {
            Categoria::create([ 'nome' => 'Celular desp fixas', 'classif' => '2.1.1.5', 'soma' => '1', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'Agua desp fixas')->first()) {
            Categoria::create([ 'nome' => 'Agua desp fixas', 'classif' => '2.1.1.6', 'soma' => '1', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'DESPESAS VARIAVEIS')->first()) {
            Categoria::create([ 'nome' => 'DESPESAS VARIAVEIS', 'classif' => '2.1.2', 'soma' => '0', 'lcto' => '0' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'CARTOES CREDITO')->first()) {
            Categoria::create([ 'nome' => 'CARTOES CREDITO', 'classif' => '2.1.2.1', 'soma' => '0', 'lcto' => '0' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'Visa')->first()) {
            Categoria::create([ 'nome' => 'Visa', 'classif' => '2.1.2.1.1', 'soma' => '1', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'Inter')->first()) {
            Categoria::create([ 'nome' => 'Inter', 'classif' => '2.1.2.1.3', 'soma' => '1', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'Ubber')->first()) {
            Categoria::create([ 'nome' => 'Ubber', 'classif' => '2.1.2.15', 'soma' => '1', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'CARTOES DEBITO')->first()) {
            Categoria::create([ 'nome' => 'CARTOES DEBITO', 'classif' => '2.1.2.2', 'soma' => '0', 'lcto' => '0' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'Debito(cartao)')->first()) {
            Categoria::create([ 'nome' => 'Debito(cartao)', 'classif' => '2.1.2.2.1', 'soma' => '1', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
        if (!Categoria::where('nome', 'PIX Pagamento')->first()) {
            Categoria::create([ 'nome' => 'PIX Pagamento', 'classif' => '2.1.2.2.2', 'soma' => '1', 'lcto' => '1' , 'ativo' => '1' , ]);
        }
    }
}
