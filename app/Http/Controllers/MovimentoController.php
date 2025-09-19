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
    public function index(Request $request)
    {
        //$movimentos = Movimento::with('categoria', 'forma_pgto')
            //->where('categoria_id', $categoria->id)
            //->where('forma_pgto_id', $forma_pgto->id)
        //->orderBy('dt')
            //->orderByPivot('dt', 'descricao')
        //->get();

        // $movimentos = Movimento::when($request->has('descricao'), function($whenQuery) use($request){
        //     $whenQuery->where('descricao', 'like' , '%' . $request->descricao . '%' );
        // } )
        // ->with('categoria', 'forma_pgto')
        // ->orderBy('dt')
        // ;

        $movimentos = Movimento::when($request->has('descricao'), function($whenQuery) use($request){
            $whenQuery->where('descricao', 'like' , '%' . $request->descricao . '%' );
        } )
        ->when($request->filled('data_de'), function($whenQuery) use($request){
            $whenQuery->where('dt', '>=' , \Carbon\Carbon::parse($request->data_de)->format('Y-m-d H:i:s') );
        } )
        ->when($request->filled('data_ate'), function($whenQuery) use($request){
            $whenQuery->where('dt', '<=' , \Carbon\Carbon::parse($request->data_ate)->format('Y-m-d H:i:s') );
        } )
        ->orderByDesc('dt')
        ->get()
        //->paginate(1000)
        //->withQueryString()
        ;

        //return view('Movimentos.index', ['movimentos' => $movimentos ] );

        return view('Movimentos.index',
         ['menu' => 'movimentos',
          'movimentos' => $movimentos,
          'descricao' => $request->descricao,
          'data_de' => $request->data_de,
          'data_ate' => $request->data_ate
         ]);

    }
}
