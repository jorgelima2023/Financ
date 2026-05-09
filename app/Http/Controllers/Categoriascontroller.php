<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nette\Utils\Arrays;

class Categoriascontroller extends Controller
{
    // form categoria
    public function form(){
        // Recuperar os registros do banco dados
        // $courses = Course::where('id', 1000)->get();
        // $courses = Course::paginate(10);
        $categorias = Categoria::orderBy('classif', 'ASC')
        // teste alerta nenhum registro encontrado ->where('id', 1000)
        ->get();

        //$categorias[] = "";
        //$categorias->new_field = null; 
        //$categorias->another_field = '';

        // Adicionando campos vazios a cada item
        // $categorias->each(function ($Categoria) {
        //     $Categoria->extra_field = null;
        // });

        // adiciona elemento vazio array - para incluir
        // $fillable = ['nome', 'classif', 'soma', 'lcto'];

        $categorias->push(new Categoria(['id' => 0, 
                                         'nome' => '' , 
                                         'classif' => '', 
                                         'soma' => '' ,
                                         'lcto' => '' ]));

        $categorias->push(new Categoria(['id' => 0, 
                                         'nome' => '' , 
                                         'classif' => '', 
                                         'soma' => '' ,
                                         'lcto' => ''  ]));
                                
        $categorias->push(new Categoria(['id' => 0, 
                                    'nome' => '' , 
                                    'classif' => '', 
                                    'soma' => '' ,
                                    'lcto' => '' ]));
                                    
        //$categorias->push(new Categoria(['id' => null, 'nome' => '']));
        //$categorias->push(new Categoria(['id' => null, 'nome' => '']));

        // Carregar a VIEW
        return view('categorias.form', ['categorias' => $categorias]);
    }

    // Listar os categoria
    public function index(){
        // Recuperar os registros do banco dados
        // $courses = Course::where('id', 1000)->get();
        // $courses = Course::paginate(10);

        // teste alerta nenhum registro encontrado ->where('id', 1000)
        $categorias = Categoria::orderBy('nome', 'ASC')
        ->get();

        // Carregar a VIEW
        return view('categorias.index', ['categorias' => $categorias]);
    }

    // Visualizar o categoria
    public function show(Categoria $categoria){
        // Carregar a VIEW
        return view('categorias.show', ['categoria' => $categoria] );
    }

    // Carregar o formulário cadastrar novo curso
    public function create(){
        return view('categorias.create');  // Carregar a VIEW
    }
    // Cadastrar no banco de dados o novo curso
    public function store(CategoriaRequest $request){

        // validar o fomulario
        $validated = $request->validated();

        DB::beginTransaction();

        // Cadastrar no banco de dados na tabela categorias os valores de todos os campos
        // dd($request->name);

        try {
            $categoria = Categoria::create([
                'nome' => $request->nome , 
                'classif' =>  $request->classif , 
                'soma' => $request->soma , 
            ]);

            DB::commit();
            return redirect()->route('categorias.show', ['categoria' => $categoria->id ] )->with('success', 'categoria cadastrado com sucesso!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Categoria não cadastrada.', [ 'error' => $e->getMessage()]);

            // retorna ao form
            return back()->withInput()->with('error', 'Categoria não cadastrada!');
        }
        
        // Redirecionar o usuário, enviar a mensagem de sucesso
        // 'course->id' veio da funcao create , da model, que cadastrou o curso
        // e na variavel tem o id novo
        //return redirect()->route('categorias.show', ['categoria' => $categoria->id ] )->with('success', 'categoria cadastrado com sucesso!');
    }
    // Carregar o formulário editar categorias
    // a model 'Categoria' recebe o id na variavel '$categoria'
    public function edit(Categoria $categoria){
        // dd($categoria);
        return view('categorias.edit', ['categoria' => $categoria]);  // Carregar a VIEW
    }

    // Editar no banco de dados o categorias
    // receber os dados formulario via 'request'
    // e na model 'Categoria' receber o id do categoria
    public function update(CategoriaRequest $request, Categoria $categoria){

        // validar o fomulario
        $validated = $request->validated();

        // editar as informacoes do banco de dados

        DB::beginTransaction();

        try {
            $categoria->update([
                'nome' => $request->nome
            ]);

            DB::commit();
            return redirect()->route('categorias.show', ['categoria' => $categoria->id ])->with('success', 'categoria alterada com sucesso');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('categoria não alterada.', [ 'error' => $e->getMessage()]);

            // retorna ao form
            return back()->withInput()->with('error', 'Curso não cadastrado!');
        }

        // dd("Editar no banco de dados o categorias");

        // redirecionar para o form show
        // na sessao 'success' e a msg 'Curso editado com sucesso'
        //return redirect()->route('categorias.show', ['categoria' => $categoria->id ])->with('success', 'categoria editado com sucesso');
    }

    // Editar no banco de dados o categorias
    // receber os dados formulario via 'request'
    // e na model 'Categoria' receber o id do categoria
    public function updateCategorias(Request $request){
        //$categorias = $request->categorias; // Isso será um array
        //dd($categorias);
        //dd($request->categoria);
        //$dados = $request->all();
        //dd($dados);

        

        // laravel 11 receber request array
        //$itens = $request->input('itens'); // Array de arrays
        $data = $request;
        //dd($data);
        
    // 'soma' => $item['soma']  campo checkbox

        // Se o checkbox foi marcado, o valor será '1'; senão, não estará presente
        // Checkbox não enviado: Se o checkbox não for marcado, 
        //     ele não aparece no request. 
        //     Por isso usamos isset() para garantir que não marque como 1 
        //     se o checkbox não estiver presente.
        
        //         validacao - opcional:

        //         $request->validate([
        //          'itens.*.id' => 'required|exists:items,id',
        //          'itens.*.soma' => 'nullable|in:1',
        //          ]);

        $falha = 0;

        foreach ($data['itens'] as $item) {
           
            //dd($item['id'] . ' ' . $item['nome'] . ' ' . $item['classif'] . ' ' . $item['soma'] );
           // id vazio eh incluir o registro
           if( empty( $item['id'] ) ){
                //echo "id vazio ";
                
                if( ( !empty( $item['nome'] ) ) && ( !empty( $item['classif'] ) ) )
                {
                
                    DB::beginTransaction();

                    try {

                        $categoria = Categoria::create([
                            'nome' => $item['nome'] , 
                            'classif' =>  $item['classif'] , 
                            'soma' => isset($item['soma']) ? 1 : 0 ,
                            'lcto' => isset($item['lcto']) ? 1 : 0 ,
                            'ativo' => isset($item['ativo']) ? 1 : 0
                        ]);

                        // Operação é concluída com êxito, conseguiu cadastrar sem erros
                        DB::commit();

                        Log::info('Categoria incluida: {id}', ['id' => $categoria]);

                    } catch (Exception $e) {

                        $falha = 1;
                        
                        // Operação não é concluída com êxito
                        DB::rollBack();
                        Log::error('Categoria não incluida.', [ 'error' => $e->getMessage()]);
                    }
                }

           }else{
                
                // localizar registro no banco de dados.
                $categoria = Categoria::where('id', $item['id'])->first();

                DB::beginTransaction();
                   
                try {

                    //echo "<br>nome=" . $item['nome'] . " classif=" . $item['classif'] . " soma=" . $item['soma'];

                    // registro nao foi marcado para excluir
                    if( isset($item['excluir']) == 0 ){

                        $categoria->update([
                            'nome' => $item['nome'],
                            'classif' => $item['classif'],
                            'soma' => isset($item['soma']) ? 1 : 0 ,
                            'lcto' => isset($item['lcto']) ? 1 : 0 ,
                            'ativo' => isset($item['ativo']) ? 1 : 0
                        ]);
                    }else{

                        $categoria->delete();
                    }

                    DB::commit();

                //     Log::info('Categoria alterada: {id}', ['id' => $item['id']]);

                } catch (Exception $e) {
                    
                    $falha = 1;

                    DB::rollBack();
                    Log::error('Categoria não alterada.', [ 'error' => $e->getMessage()]);
                }

           }

            // debug - mostrar valores , omitir return acima.
            // echo 'id=' . $item['id'] . ' nome=' . $item['nome'] . ' c=' . $item['classif'] . ' soma=' . $item['soma'] . '<br>';
           
           
        }

        
        if( $falha == 0 ){   
            //$categorias = Categoria::orderBy('nome', 'ASC')->get();

            // Carregar a VIEW
            //return view('categorias.index', ['categorias' => $categorias]);

            return redirect()->route('categorias.form')->with('success', 'Categoria cadastrada com sucesso!');

        }else{
            return back()->withInput()->with('error', 'Categoria não cadastrada!');

        }

        // validar o fomulario
        // $validated = $request->validated();

        // editar as informacoes do banco de dados
        // $categoria->update([
        //     'nome' => $request->nome
        // ]);

        // dd("Editar no banco de dados o categorias");

        // redirecionar para o form show
        // na sessao 'success' e a msg 'Curso editado com sucesso'
        // return redirect()->route('categorias.show', ['categoria' => $categoria->id ])
        //     ->with('success', 'categoria editado com sucesso');
    }

    // Excluir o categorias do banco de dados
    public function destroy(Categoria $categoria){

        // dd("Excluir o categorias do banco de dados");

        // excluir o registro do banco de dados, no metodo 'delete' pode adiconar condicao

        DB::beginTransaction();

        try {
            $categoria->delete();
            
            DB::commit();
            return redirect()->route('categorias.index')->with('success', 'categoria apagada com sucesso');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Curso não alterado.', [ 'error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'categoria não apagada!');
        }
        // redirecionar o usuario, enviar msg de sucesso
        //return redirect()->route('categorias.index')->with('success', 'categoria apagado com sucesso');
    }
}
