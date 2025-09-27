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

        $categorias = Categoria::orderBy('classif', 'ASC')
        // teste alerta nenhum registro encontrado ->where('id', 1000)
        ->get();

        //return view('Movimentos.index', ['movimentos' => $movimentos ] );

        return view('Movimentos.index',
         ['menu' => 'movimentos',
          'movimentos' => $movimentos,
          'categorias' => $categorias,
          'descricao' => $request->descricao,
          'data_de' => $request->data_de,
          'data_ate' => $request->data_ate
         ]);

    }

    public function store(Request $request){
        
        // dd('data:' . $request->data . 
        //    ' tipo:' . $request->tipo_lcto . 
        //    ' tipo_lc:' . $request->tipo . 
        //    ' categoria_id:' . $request->categoria_id . 
        //    ' descricao:' . $request->descricao  . 
        //    ' dcto:' . $request->dcto . 
        //    ' valor:' . $request->valor ) ;

           // "data:2025-09-21 tipo:0 tipo_lc:O categoria_id:1 descricao:jacomar dcto:01 valor:150"

        // validar formulario campos:

        // $request->validate([
        //         'data' => 'required|date',
        //         'tipo_lcto' => 'required|boolean',
        //         'tipo' => 'required|in:O,P',
        //         'categoria_id' => 'required|exists:categorias,id',
        //         'descricao' => 'required|string|max:255',
        //         'dcto' => 'required|string|max:50',
        //         'valor' => 'required|numeric',
        //     ]);

        // tipo_lcto: 1-receita / 0-despesa
        // tipo_lc:   'O'-obrigacao / 'P'-previsao
        // $Movimento = Movimento::create([
        //     'dt' => $request->data , 
        //     'tipo' => $request->tipo_lcto ,
        //     'tipo_lc' => $request->tipo , 
        //     'categoria_id' => $request->categoria_id , 
        //     'descricao' => $request->descricao ,
        //     'dcto' => $request->dcto ,
        //     'valor' => $request->valor ,
        // ]);

        Movimento::create([
            'dt' => $request->input('data'),
            'tipo_lc' => $request->input('tipo_lcto') ,
            'tipo' => 0 /*$request->input('tipo')*/,
            'categoria_id' => $request->input('categoria_id'),
            'descricao' => $request->input('descricao'),
            'dcto' => $request->input('dcto'),
            'valor' => $request->input('valor'),
            // Adicione `forma_pgto_id` se estiver usando
            'forma_pgto_id' => 1 // ou pegue do formulário
        ]);

        // Redirecionar o usuário, enviar a mensagem de sucesso
        return redirect()->route('movimentos.index' )->with('success', 'Movimento cadastrado com sucesso!');

    }

    //public function update(Request $request){
    public function update(Request $request, Movimento $movimento){
        //dd($request);

        // tipo: 1-receita , 0-despesa label => 'tipo_lcto' 
        // tipo_lc: O-Obrigacao , P-Previsao
        $validated = $request->validate([
            'editdt' => 'required|date',
            'edittipo' => 'required|in:0,1',
            'edittipo_lc' => 'required|in:O,P',
            'editcategoria_id' => 'required|exists:categorias,id',
            'editDescricao' => 'required|string|max:150',
            'editdcto' => 'required|string|max:50',
            'editValor' => 'required|numeric',
        ]);

        $movimento->update([
            'dt' => $validated['editdt'],
            'tipo_lc' => $validated['edittipo_lc'],
            'tipo' => $validated['edittipo'],
            'categoria_id' => $validated['editcategoria_id'],
            'descricao' => $validated['editDescricao'],
            'dcto' => $validated['editdcto'],
            'valor' => $validated['editValor'],
        ]);

        return redirect()->route('movimentos.index')->with('success', 'Movimento atualizado com sucesso!');

    }

}
