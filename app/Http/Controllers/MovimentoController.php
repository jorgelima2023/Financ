<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Forma_pgto;
use App\Models\Movimento;
use Illuminate\Http\Request;

// Um relacionamento um-para-muitos 
// é usado para definir relacionamentos 
// em que um único modelo é o pai de um ou mais modelos filhos.

class MovimentoController extends Controller
{
    // index
    //public function index(categoria $categoria )
    public function index()
    {
        $movimentos = Movimento::with('categoria', 'forma_pgto')
            //->where('categoria_id', $categoria->id)
            //->where('forma_pgto_id', $forma_pgto->id)
            ->orderBy('dt')
            //->orderByPivot('dt', 'descricao')
            ->get();

        return view('Movimentos.index', ['movimentos' => $movimentos ] );

    }
}
