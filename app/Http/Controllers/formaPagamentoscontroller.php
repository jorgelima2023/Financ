<?php

namespace App\Http\Controllers;

use App\Models\Forma_pgto;
use Illuminate\Http\Request;

class formaPagamentoscontroller extends Controller
{
    public function form(){
        $formaPagamentos = Forma_pgto::orderBy('nome', 'ASC')
        ->get();

        $formaPagamentos->push(new Forma_pgto(['id' => 0, 
                                         'nome' => '']));
        $formaPagamentos->push(new Forma_pgto(['id' => 0, 
                                         'nome' => '']));
        $formaPagamentos->push(new Forma_pgto(['id' => 0, 
                                         'nome' => '']));

        // Carregar a VIEW
        return view('formaPagamentos.form', ['formaPagamentos' => $formaPagamentos]);
    }
}
