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
use Symfony\Component\HttpKernel\Event\ViewEvent;

// Um relacionamento um-para-muitos 
// é usado para definir relacionamentos 
// em que um único modelo é o pai de um ou mais modelos filhos.

class MovimentoController extends Controller
{
    // index
    //public function index(categoria $categoria )
    public function index(Request $request)
    {
        //dd($request);

        DB::enableQueryLog();

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

        // ===================================================================================
        // consulta original - desabilitada

        if( 1 == 2 ){
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
        }
        // ===================================================================================

        // ===================================================================================
        // opcao order by coluna tabela relacionada
        if( 1 == 1 ){
            
            $movimentos = Movimento::select('movimentos.*')
            ->join('categorias', 'movimentos.categoria_id', '=', 'categorias.id')
            ->with(['categoria', 'forma_pgto'])
            ->when($request->has('descricao'), function($whenQuery) use($request){
                $whenQuery->where('descricao', 'like' , '%' . $request->descricao . '%' );
                } )
            ->when($request->has('tipo_filtro'), function($whenQuery) use($request){
                $whenQuery->where('tipo_lc', '=' , $request->tipo_filtro );
                } )    
            ->when($request->filled('data_de'), function($whenQuery) use($request){
                $whenQuery->where('dt', '>=' , \Carbon\Carbon::parse($request->data_de)->format('Y-m-d H:i:s') );
                } )
            ->when($request->filled('data_ate'), function($whenQuery) use($request){
                $whenQuery->where('dt', '<=' , \Carbon\Carbon::parse($request->data_ate)->format('Y-m-d H:i:s') );
                } )
            ->orderBy('movimentos.dt', 'asc')
            ->orderBy('categorias.classif', 'asc')
            ->get();
        }
        // ===================================================================================
        
        $sel = "";
        if( $request->has('descricao') && $request->descricao ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Descrição:{$request->descricao}";
        }
        if( $request->has('tipo_filtro') && $request->tipo_filtro ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Tipo:" . ($request->tipo_filtro == 'O') ? 'Obrigação' : 'Previsão'; 
        }
        if ( $request->filled('data_de') && $request->data_de ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Data de:" . \Carbon\Carbon::parse($request->data_de)->format('d-m-Y');
        }
        if ( $request->filled('data_ate') && $request->data_ate ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Data ate:" . \Carbon\Carbon::parse($request->data_ate)->format('d-m-Y');
        }

        $categorias = Categoria::orderBy('classif', 'ASC')
        // teste alerta nenhum registro encontrado ->where('id', 1000)
        ->get();

        //return view('Movimentos.index', ['movimentos' => $movimentos ] );

        /*  ==============================
            opcao 1 somar valores

            $totalTipoZero = 0;

            foreach ($movimentos as $movimento) {
                if ($movimento->tipo == 0) {
                    $totalTipoZero += $movimento->valor;
                }
            }
        */

        // =================================
        // opcao 2 somar valores 

        $totalDespesa = $movimentos->filter(function ($movimento) {
            return $movimento->tipo == 0;
        })->sum('valor');

        $totalReceita = $movimentos->filter(function ($movimento) {
            return $movimento->tipo == 1;
        })->sum('valor');

        $total = $totalReceita - $totalDespesa; 


        // nao funciona Log::info('sql' , ['' => $movimentos->toRawSql()]);
        // nao funciona dd($movimentos->toSql());

        //dd(DB::getQueryLog());   esse funciona ok!

        

        return view('Movimentos.index',
         ['menu' => 'movimentos',
          'movimentos' => $movimentos,
          'categorias' => $categorias,
          'descricao' => $request->descricao,
          'data_de' => $request->data_de,
          'data_ate' => $request->data_ate,
          'tipo_filtro' => $request->tipo_filtro,
          'qtde_registros' => count($movimentos),
          'totalMovimento' => $total,
          'sel' => $sel
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

        // form => name="tipo_lcto" => campo "tipo" : 1-Receita , 0-Despesa

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

    // ===========================================================================
    
    
    // ===========================================================================

    

    // verificar se no import trouxe o http request
    // importar arquivo ofx , recebe o path + name arquivo
    // botao importar form index

    //public function import(Request $request){
    
    public function form(){
        //dd($request);
        //dd( file($request->file('file')) );

        // $request->validate(
        //     ['file' => 'required|mimes:ofx!max:2048',
        //     ],
        //     ['file.required' => 'arq requerido',
        //      'file.mimes' => 'arq deve ser do tipo ofx',
        //      'file.max' => 'tamanho arq excedido :max Mb'
        //     ]
        // );

        // $request->validate(
        //     ['file' => 'required|max:2048',
        //     ],
        //     ['file.required' => 'O arquivo não foi informado!',
        //      'file.max' => 'Tamanho arquivo excedido, tamanho :max Mb'
        //     ]
        // );
        
        $caminho_do_arquivo = 'c:\\temp\\extrato.ofx';
        
        //função nativa do PHP 
        if (!file_exists($caminho_do_arquivo)) {
            return back()->withInput()->with('error', '(C:\temp\extrato.ofx) não encontrado!');
        }
        
        $ofxParser = new \OfxParser\Parser();

        $ofx = $ofxParser->loadFromFile( 'C:\temp\extrato.ofx' );

        // Acesse a primeira conta bancária (a variável $ofx contém um array de contas)
        $contaBancaria = reset($ofx->bankAccounts);
        $dataInicio = $contaBancaria->statement->startDate;
        $dataFim = $contaBancaria->statement->endDate;

        $di = \Carbon\Carbon::parse($dataInicio)->tz('America/Sao_Paulo')->format('d/m/Y');
        $df = \Carbon\Carbon::parse($dataFim)->tz('America/Sao_Paulo')->format('d/m/Y');
        
        // Obtenha as transações da conta
        $transacoes = $contaBancaria->statement->transactions;

        // Exemplo de como acessar uma transação específica
        if (!empty($transacoes)) {

            $categorias = Categoria::orderBy('classif', 'ASC')->get();

            // buscar um id nulo, zero para carregar a model
            $movimentos = Movimento::orderBy('descricao', 'ASC')
            ->where('id', 0)
            ->get();
            
            //$primeiraTransacao = reset($transacoes);

            $i = 0;
            foreach ($contaBancaria->statement->transactions as $ofxEntity) {
                $i++;
                $primeiraTransacao = $ofxEntity;

                /*  debug mostrar valores...

                $dit = \Carbon\Carbon::parse($primeiraTransacao->date)->tz('America/Sao_Paulo')->format('d/m/Y');
                
                echo "linha:" . $i . "<br>";
                echo "Data da primeira transação: " . $dit . "<br>";
                echo "Valor da primeira transação: " . $primeiraTransacao->amount . "<br>";
                echo "tipo da primeira transação: " . $primeiraTransacao->type . "<br>";
                echo "memo da primeira transação: " . $primeiraTransacao->memo . "<br>";
                echo "checkNumber da primeira transação: " . $primeiraTransacao->checkNumber . "<br>";
                echo "<br>";  
                */
                
                // 'tipo_lc': "O" - obrigacao
                // tipo: $primeiraTransacao->type  'CREDIT' ou 'DEBIT'
                $tipo = $primeiraTransacao->type == 'CREDIT' ? 'CR' : 'DB';
                $dit = \Carbon\Carbon::parse($primeiraTransacao->date)->tz('America/Sao_Paulo')->format('d/m/Y');
//dd($dit);
                $movimentos->push(new Movimento(['id' => 0, 
                                            'dt' => $dit,
                                            'descricao' => $primeiraTransacao->memo , 
                                            'categoria_id' => 0,
                                            'forma_pgto_id' => 1,
                                            'tipo_lc' => 'O',
                                            'tipo' => $tipo,   
                                            'valor' => $primeiraTransacao->amount,
                                            'dcto' => $primeiraTransacao->checkNumber ]));
                }

                //dd($movimentos);
                /*
                    #attributes: array:8 [
                        "dt" => "01/08/2025"
                        "descricao" => "Rendimentos Poup Facil-depos a Partir 4/5/12"
                        "categoria_id" => 0
                        "forma_pgto_id" => 1
                        "tipo_lc" => "O"
                        "tipo" => "CREDIT"
                        "valor" => 0.53
                        "dcto" => SimpleXMLElement 
                    ]

                    #fillable: array:8 [▼
                        0 => "tipo"
                        1 => "forma_pgto_id"
                        2 => "tipo_lc"
                        3 => "categoria_id"
                        4 => "descricao"
                        5 => "valor"
                        6 => "dt"
                        7 => "dcto"
                    ]
                */

            return view('movimentos.form', ['movimentos' => $movimentos , 'categorias' => $categorias ]  );
            //return view('movimentos.abrirOfx');

            //target="_blank"
            //<a href="/sua-rota" target="_blank">Abrir em nova aba</a>

           
        }else{
            return back()->withInput()->with('error', 'Nenhuma transação encontrada!');
        }
    }

    // form update movimentos - vindo do form - ler ofx
    public function updateMovimentos(Request $request){
        $data = $request;
        //dd($data);
        //dd($request);

        // $request->validate(
        //     ['itens.categoria_id' => 'required',
        //     ],
        //     ['itens.categoria_id' => 'Categoria não informada!'
        //     ]
        // );

        /* 
            "itens" => array:16 [▼
            0 => array:7 [▼
            "id" => null
            "dt" => "01/08/2025"
            "descricao" => "Rendimentos Poup Facil-depos a Partir 4/5/12"
            "tipo" => "CR"
            "dcto" => "0106767"
            "valor" => "0.53"
            "categoria_id" => "3"
            ]

            2 => array:7 [▼
            "id" => null
            "dt" => "04/08/2025"
            "descricao" => "Visa Electron Condor Derosso lj"
            "tipo" => "DB"
            "dcto" => "0033328"
            "valor" => "-31.68"
            "categoria_id" => "2"
            ]
        */

        $falha = 0;
        $movIncluido = 0;

        foreach ($data['itens'] as $item) {

            if( empty( $item['id'] ) ){
                
                // localizar movimento pelo campo "dcto" chave registro, 
                //    se nao existir inclui, caso contrario ja foi inderido.

                $movimento_Dcto = Movimento::where('dcto', $item['dcto'])->first();

                if( empty($movimento_Dcto) ){

                    DB::beginTransaction();

                    // ofx campo type => 'CREDIT' ou 'DEBIT'
                    // no form abreviado CR-CREDIT DB-DEBIT

                    //  gravar tabela movimentos
                    // form => name="tipo" => campo "tipo_lcto" : 1-Receita , 0-Despesa

                    
                    $tp = $item['tipo'] == 'CR' ? 1 : 0;
                    $movDt = \Carbon\Carbon::parse($item['dt'])->tz('America/Sao_Paulo')->format('Y/d/m');

                    // validar campo valor do ofx , retirar sinal negativo
                    if ( $item['tipo'] == 'DB'){
                        $vlr = str_replace(',', '.', str_replace('.', '', $item['valor'] )) * -1;
                    }else{
                        $vlr = str_replace(',', '.', str_replace('.', '', $item['valor'] ));
                    }
                    //dd($item['dt']);
                    //dd($movDt);
                    try {
                        
                        $movIncluido++;

                        $Movimento = Movimento::create([
                            'dt' => $movDt ,
                            'descricao' => $item['descricao'] , 
                            'tipo' =>  $tp ,
                            'tipo_lc' => 'O',
                            'dcto' =>  $item['dcto'] ,
                            'valor' => $vlr, 
                            'categoria_id' => $item['categoria_id'],
                            'forma_pgto_id' => 1
                        ]);

                        // Operação é concluída com êxito, conseguiu cadastrar sem erros
                        DB::commit();

                        Log::info('Movimento incluido: {id}', ['id' => $Movimento]);

                    } catch (Exception $e) {

                        $falha = 1;
                        
                        // Operação não é concluída com êxito
                        DB::rollBack();
                        Log::error('Movimento não incluido.', [ 'error' => $e->getMessage()]);
                    }
                }
            }
        }

        if( $falha == 0 ){   
            //$categorias = Categoria::orderBy('nome', 'ASC')->get();

            // Carregar a VIEW
            //return view('categorias.index', ['categorias' => $categorias]);

            return redirect()->route('movimentos.index')->with('success', 'Movimentos cadastrados com sucesso: (' . $movIncluido . ') movimentos!' );

        }else{
            return back()->withInput()->with('error', 'Movimentos não cadastrados!');

        }

    }

}
