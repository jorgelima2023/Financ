<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovimentoEditRequest;
use App\Http\Requests\MovimentoRequest;
use App\Models\Categoria;
use App\Models\Forma_pgto;
use App\Models\Movimento;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function store(MovimentoRequest $request){
        //dd($request);
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

        // validar o fomulario
        $validated = $request->validated();

        DB::beginTransaction();

        // form => name="tipo_lcto" 1-Receita , 0-Despesa

        //         name="tipo"      "O" Obrigacao , "P" Provisao
        
        try {
            Movimento::create([
                'dt' => $request->input('data'),
                'tipo_lc' => $request->input('tipo') ,     
                'tipo' => $request->input('tipo_lcto'),   
                'categoria_id' => $request->input('categoria_id'),
                'descricao' => $request->input('descricao'),
                'dcto' => $request->input('dcto'),
                //'valor' => $request->input('valor'),
                'valor' => str_replace(',', '.', str_replace('.', '', $request->input('valor'))),
                // Adicione `forma_pgto_id` se estiver usando
                'forma_pgto_id' => 1 // ou pegue do formulário
            ]);

            DB::commit();

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('movimentos.index' )->with('success', 'Movimento cadastrado com sucesso!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Movimento não incluido.', [ 'error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Movimento não cadastrado!');
        }

        // Redirecionar o usuário, enviar a mensagem de sucesso
        //return redirect()->route('movimentos.index' )->with('success', 'Movimento cadastrado com sucesso!');

    }

    //public function update(Request $request){
    //public function update(MovimentoEditRequest $request, Movimento $movimento){
    public function update(MovimentoEditRequest $request, Movimento $movimento){
       //dd($request);

        $validated = $request->validated();

        // tipo: 1-receita , 0-despesa label => 'tipo_lcto' 
        // tipo_lc: O-Obrigacao , P-Previsao
        // $validated = $request->validate([
        //     'editdt' => 'required|date',
        //     'edittipo' => 'required|in:0,1',
        //     'edittipo_lc' => 'required|in:O,P',
        //     'editcategoria_id' => 'required|exists:categorias,id',
        //     'editDescricao' => 'required|string|max:150',
        //     'editdcto' => 'required|string|max:50',
        //     'editValor' => 'required|numeric',
        // ]);

        // "editMovimentoId" => "8"
        // "editdt" => "2025-09-28"
        // "edittipo" => "1"
        // "edittipo_lc" => "O"
        // "editcategoria_id" => "3"
        // "editDescricao" => "dinheiro A"
        // "editdcto" => "d-AA"
        // "editValor" => "18.23"

        DB::beginTransaction();
        
        try {
            $movimento->update([
                // 'dt' => $validated['editdt'],
                // 'tipo_lc' => $validated['edittipo_lc'],
                // 'tipo' => $validated['edittipo'],
                // 'categoria_id' => $validated['editcategoria_id'],
                // 'descricao' => $validated['editDescricao'],
                // 'dcto' => $validated['editdcto'],
                // 'valor' => str_replace(',', '.', str_replace('.', '', $request->editValor))

                'dt' => $request->input('editdt'),
                'tipo_lc' => $request->input('edittipo_lc') ,     
                'tipo' => $request->input('edittipo'),   
                'categoria_id' => $request->input('editcategoria_id'),
                'descricao' => $request->input('editDescricao'),
                'dcto' => $request->input('editdcto'),
                //'valor' => $request->input('valor'),
                'valor' => str_replace(',', '.', str_replace('.', '', $request->input('editValor'))),
                // Adicione `forma_pgto_id` se estiver usando
                //'forma_pgto_id' => 1 // ou pegue do formulário
            ]);
            
            DB::commit();

            return redirect()->route('movimentos.index')->with('success', 'Movimento atualizado com sucesso!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('movimento não alterado.', [ 'error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Movimento não atualizado!');
        }

        //return redirect()->route('movimentos.index')->with('success', 'Movimento atualizado com sucesso!');

    }

    // Excluir o Movimento do banco de dados
    public function destroy(Movimento $movimento){
        // dd($movimento);

        // dd("Excluir o categorias do banco de dados");

        // excluir o registro do banco de dados, no metodo 'delete' pode adiconar condicao

        DB::beginTransaction();

        try {
            $movimento->delete();
            DB::commit();
            return redirect()->route('movimentos.index')->with('success', 'Movimento apagado com sucesso');

        } catch (Exception $e) {
             DB::rollBack();
            Log::error('movimento não alterado.', [ 'error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Movimento não atualizado!');
        }

        // redirecionar o usuario, enviar msg de sucesso
        //return redirect()->route('movimentos.index')->with('success', 'Movimento apagado com sucesso');
    }

}
