<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Forma_pgto;
use App\Models\Movimento;
use Illuminate\Http\Request;

class MovimentoController extends Controller
{
    // index
    public function index(Movimento $movimento)
    {
        $movimentos = Movimento::orderBy('dt', 'ASC')->get();
       

        return view('movimentos.index' , 
            [ 'movimentos' => $movimentos ]);
    }
}
