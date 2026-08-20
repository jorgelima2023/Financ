<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovimentoEditRequest;
use App\Http\Requests\MovimentoRequest;
use App\Models\Categoria;
use App\Models\ContasCorrente;
use App\Models\descricaoCategoria;
use App\Models\Forma_pgto;
use App\Models\Movimento;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

// Um relacionamento um-para-muitos 
// é usado para definir relacionamentos 
// em que um único modelo é o pai de um ou mais modelos filhos.

class MovimentoController extends Controller
{

    

    // gerar lctos automatico
    public function indexGerar(Request $request)
    {
        if( $request->indexGerarLctos ){
           
            $falha = 0;
            for ($i = 0; $i < $request->qtde; $i++){
                $dt = $request->data_de;
                
                if( $i > 0 ){
                    $dt = \Carbon\Carbon::parse($dt)->addDays(30*$i);
                } 

                /*
                echo "<br> reg " . $i . ' ' . $dt . ' tipo_filtro ' . $request->tipo_filtro . 
                    ' tipo_lcto ' . $request->tipo_lcto . ' categoria_id ' . $request->categoria_id . 
                    ' contabusca ' . $request->contabusca . ' valor ' . $request->valor . 
                    ' qtde ' . $request->qtde;
                */

                // form => name="tipo_lcto" => campo "tipo" : 1-Receita , 0-Despesa
                //         name="tipo"      "O" Obrigacao , "P" Provisao

                DB::beginTransaction();

                try {
                    Movimento::create([
                    'dt' => $dt,
                    'tipo_lc' => $request->tipo_filtro,      
                    'tipo' => $request->tipo_lcto ,
                    'categoria_id' => $request->categoria_id,
                    'descricao' => $request->input('descricao'),
                    'dcto' => '0',
                    //'valor' => $request->input('valor'),
                    'valor' => str_replace(',', '.', str_replace('.', '', $request->input('valor'))),
                    // Adicione `forma_pgto_id` se estiver usando
                    'forma_pgto_id' => $request->eforma_pgto_id , 
                    'contascorrente_id' => $request->contabusca 
                    ]);

                    DB::commit();

                    // Redirecionar o usuário, enviar a mensagem de sucesso
                   // return redirect()->route('movimentos.indexGerar' )->with('success', 'Movimentos ( {$i} ) cadastrado com sucesso!');

                } catch (Exception $e) {
                    $falha++;
                    DB::rollBack();
                    Log::error('Movimento não incluido.', [ 'error' => $e->getMessage()]);
                    // retorna ao form que chamou.
                    //return back()->withInput()->with('error', 'Movimento não cadastrado!');
                }
            }


            if( $i > 0 ){
                if( $falha == 0){
                // Redirecionar o usuário, enviar a mensagem de sucesso
                $msgmov = 'Movimentos cadastrados com sucesso: (' . $i . ') movimentos!';
                    return redirect()->route('movimentos.indexGerar' )->with('success', $msgmov);
                }else{
                    return back()->withInput()->with('error', 'Movimento não cadastrado!');
                }
                
            }
                
            
            // dd($request);
            /*
            "indexGerarLctos" => "1"
            "descricao" => null
            "data_de" => "2026-07-04"
            "tipo_filtro" => "P"
            "tipo_lcto" => "0"
            "categoria_id" => "43"
            "contabusca" => "2"
            "valor" => "30"
            "qtde" => "5"  
            */
            exit;
        }
        
        //   montar select option categorias, apenas as que estão ativas, e indicadas recebem lcto
        $categorias = DB::select("SELECT * FROM categorias where lcto = '1' and ativo = '1' order by classif");
        // formulario para o filtro categorias, montar select option contas correntes
        $contascorrentes = DB::select("SELECT id, id as contascorrente_id , CONCAT_WS(' - ', nome, agencia, conta, id) as nome FROM contascorrentes");
        // formulario para o filtro forma_pgtos, montar select option
        $forma_pgtos = Forma_pgto::orderBy('nome', 'ASC')->get();

        $sel = '';

        return view('Movimentos.indexGerar',
         ['menu' => 'movimentos em lote' ,
          'categorias' => $categorias,
          'contascorrentes' => $contascorrentes,
          'forma_pgtos' => $forma_pgtos,
          'descricao' => '',
          'categoriabusca' => '',
          'data_de' => '',
          'tipo_filtro' => '',
          
         ]);
    }
    // form index
    public function index(Request $request)
    {


        //DB::enableQueryLog();   // <<-- ativar debug mostrar consulta
        
        // ===================================================================================
        
        // tratamento se a data vier vazia.
        /*
        Se data_de estiver vazia → usa hoje 00:00:00
        Se data_ate estiver vazia → usa hoje 23:59:59
        Evita duplicação de when
        Usa Carbon diretamente (sem format, mais seguro)
        Código mais fácil de manter
          Prefira filled() em vez de has() para evitar valores vazios
          whereBetween() é ideal para filtros de período
          startOfDay() / endOfDay() evitam problemas com horário

          \Carbon\Carbon::now()->startOfMonth()  => 1o. dia do mes
          \Carbon\Carbon::now()->startOfDay()    => data atual
        */ 

        $dataDe  = $request->filled('data_de') 
            ? \Carbon\Carbon::parse($request->data_de)->startOfDay()
            : \Carbon\Carbon::now()->startOfMonth();
            

        $dataAte = $request->filled('data_ate')
            ? \Carbon\Carbon::parse($request->data_ate)->endOfDay()
            : \Carbon\Carbon::now()->endOfMonth();
        
        // prepara a consulta com os movimentos    
        $movimentos = Movimento::select('movimentos.*')
            ->join('categorias', 'movimentos.categoria_id', '=', 'categorias.id')
            ->join('contascorrentes', 'movimentos.contascorrente_id', '=', 'contascorrentes.id')
            ->join('forma_pgtos', 'movimentos.forma_pgto_id', '=', 'forma_pgtos.id')
            ->with(['categoria', 'forma_pgto', 'contascorrente'])
            ->when($request->filled('descricao'), function ($q) use ($request) {
                $q->where('descricao', 'like', '%' . $request->descricao . '%');
            })
            /* formulario campo input texto digitar a categoria, campo texto digitar categoria! 
            ->when($request->filled('categoriabusca'), function ($q) use ($request) {
                $q->where('categorias.nome', '=', $request->categoriabusca );
            })*/

            /*  formulario campo select option, categorias   */
            ->when($request->filled('categoria_id'), function ($q) use ($request) {
                $q->where('categorias.id', '=', $request->categoria_id );
            })

            /*  formulario campo conta corrente select option, busca contas 
            ->when($request->filled('contabusca'), function ($q) use ($request) {
                $q->where('movimentos.contascorrente_id', '=', $request->contabusca );
                */

            /*  formulario campo conta corrente multi select option, busca contas    */
            ->when($request->filled('contabusca'), function ($q) use ($request) {
                $selectedContabuscaIds = $request->input('contabusca');
                $q->whereIn('movimentos.contascorrente_id', $selectedContabuscaIds )->get();

            })
            ->when($request->filled('tipo_filtro'), function ($q) use ($request) {
                $q->where('tipo_lc', $request->tipo_filtro);
            })
            ->whereBetween('dt', [$dataDe, $dataAte])
            ->orderBy('movimentos.dt', 'asc')
            ->orderBy('categorias.classif', 'asc')
            ->get();  // get vai retornar uma collection 
            //->firstOrFail();

            //Log::info('sql movimento_index', DB::getQueryLog() );

        // dd(DB::getQueryLog());   <<-- ativar debug mostrar consulta 

        // ************************************************
        // montar selecao a ser apresentada no formulario
        // ************************************************
        $sel = "";
        if( $request->has('descricao') && $request->descricao ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Descrição:{$request->descricao}";
        }
        if( $request->has('categoriabusca') && $request->categoriabusca ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Categoria:{$request->categoriabusca}";
        }
        if( $request->has('contabusca') && $request->contabusca ){

            // DESATIVADO !!! formulario campo select option contas correntes
            //$cc = ContasCorrente::where('id', $request->contabusca)->first();
            
            // formulario campo multi select option contas correntes
            $selectedContabuscaIds = $request->input('contabusca');
                //$q->whereIn('movimentos.contascorrente_id', $selectedContabuscaIds )->get();
            
            $cc = ContasCorrente::whereIn('id', $selectedContabuscaIds)->get();
            $ccNomes = $cc->pluck('nome')->implode(', '); 
            //dd($nomes = $cc->pluck('nome')->toArray() );
            $sel .= $sel ? ', ' : '' ;
            $sel .= "Contas:" . $ccNomes;

            // DESATIVADO !!! formulario mostrar conta select option
            //$sel .= " Conta corrente:{$request->contabusca} {$cc->nome} - {$cc->agencia} - {$cc->conta}";
            //$sel .= " Conta corrente: {$cc->nome} - {$cc->agencia} - {$cc->conta}";

        }

        if( $request->has('tipo_filtro') && $request->tipo_filtro ){
            $sel .= $sel ? ', ' : '' ;
            if( $request->tipo_filtro == 'O' ){
                $sel .= " Tipo:Obrigação";
            }else{
                $sel .= " Tipo:Previsão";
            }                    
        }
        if ( $request->filled('data_de') && $request->data_de ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Data de:" . \Carbon\Carbon::parse($request->data_de)->format('d-m-Y');
        }
        if ( $request->filled('data_ate') && $request->data_ate ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Data ate:" . \Carbon\Carbon::parse($request->data_ate)->format('d-m-Y');
        }

        // teste alerta nenhum registro encontrado ->where('id', 1000)

        // anterior ... $categorias = Categoria::orderBy('classif', 'ASC')->get();

        // formulario para o filtro categorias, 
        //   montar select option categorias, apenas as que estão ativas, e indicadas recebem lcto
        $categorias = DB::select("SELECT * FROM categorias where lcto = '1' and ativo = '1' order by classif");
        // formulario para o filtro categorias, montar select option contas correntes
        $contascorrentes = DB::select("SELECT id, id as contascorrente_id , CONCAT_WS(' - ', nome, agencia, conta, id) as nome FROM contascorrentes");
        // formulario para o filtro forma_pgtos, montar select option
        $forma_pgtos = Forma_pgto::orderBy('nome', 'ASC')->get();

        
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
        $totalDespesa = 0;
        $totalDespesa = $movimentos->filter(function ($movimento) {
            return $movimento->tipo == 0;
        })->sum('valor');

        $totalReceita = 0;
        $totalReceita = $movimentos->filter(function ($movimento) {
            return $movimento->tipo == 1;
        })->sum('valor');

        $total = $totalReceita - $totalDespesa; 

        // dd(DB::getQueryLog());   //esse funciona ok!   <<---   mostrar consulta sql 

        /*
        consulta agrupada movimentos por categoria
        SELECT * 
        FROM ( 
        SELECT tipo_lc,categorias.nome,categorias.classif, sum(movimentos.valor) as valor FROM movimentos INNER JOIN categorias ON movimentos.categoria_id = categorias.id WHERE tipo_lc = 'O' AND dt BETWEEN '2026-01-01' AND '2026-01-12' 
        GROUP BY tipo_lc,categorias.nome,categorias.classif 
        UNION ALL 
        SELECT tipo_lc,categorias.nome,categorias.classif, sum(movimentos.valor) as valor FROM movimentos INNER JOIN categorias ON movimentos.categoria_id = categorias.id WHERE tipo_lc = 'P' AND dt BETWEEN '2026-01-01' AND '2026-01-12' 
        GROUP BY tipo_lc,categorias.nome,categorias.classif ) AS subquery_combinada 
        ORDER BY classif, tipo_lc;
        */

        // mostrar qtde total registros do select
        // 'qtde_registros' => count($movimentos)

        // **********************************************************************
        // retorna a view que chamou registros encontrados conforme os filtros.

        /*  formulario index 
            returno view
            'menu'  => nome do menu para indicar o ativo
            'movimentos' => $movimentos,    array colection com os movimentos
            'categorias' => $categorias,    array colection com as categorias form selecao
            'contascorrentes' => $contascorrentes,  array colection com as contascorrentes form selecao 
            'forma_pgtos' => $forma_pgtos,  array colection com as forma_pgtos form selecao 

            'descricao' => $request->descricao,  retorna a descricao informada ao form selecao
            'categoriabusca' => $request->categoriabusca, retorna categoria informada ao form selecao
            'data_de' => $request->data_de, retorna data_de informada ao form selecao
            'data_ate' => $request->data_de, retorna data_ate informada ao form selecao
            'tipo_filtro' => $request->tipo_filtro, retorna campo tipo_filtro informado ao form selecao
            'qtde_registros' => count($movimentos), retorna qtde registros ao form selecao
            'totalMovimento' => $total, retorna valor total movimentos ao form selecao
            'totalDespesa' => $totalDespesa,   retorna valor total despesa movimentos ao form selecao
             'totalReceita' => $totalReceita,  retorna valor total receita movimentos ao form selecao
             'sel' => $sel     retorna campos da selecao ao formulario
            */

        // **********************************************************************
        return view('Movimentos.index',
         ['menu' => 'movimentos',
          'movimentos' => $movimentos,
          'categorias' => $categorias,
          'contascorrentes' => $contascorrentes,
          'forma_pgtos' => $forma_pgtos,
          'descricao' => $request->descricao,
          'categoriabusca' => $request->categoriabusca,
          'data_de' => $request->data_de,
          'data_ate' => $request->data_ate,
          'tipo_filtro' => $request->tipo_filtro,
          'qtde_registros' => count($movimentos), 
          'totalMovimento' => $total,
          'totalDespesa' => $totalDespesa,
          'totalReceita' => $totalReceita,
          'sel' => $sel ? 'Filtros - Seleção: ' . $sel : ''
         ]);

    }
    // incluir - cadastrar 
    public function store(MovimentoRequest $request){
        
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

        // validar o fomulario, receber dados na request e faz validacao no metodo
        $validated = $request->validated();

        DB::beginTransaction();

        // form => name="tipo_lcto" => campo "tipo" : 1-Receita , 0-Despesa
        //         name="tipo"      "O" Obrigacao , "P" Provisao
        // campo valor, formatar virgula no formulario para apresentacao, e formatar ponto gravar banco        
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
                'forma_pgto_id' => $request->input('forma_pgto_id') , 
                'contascorrente_id' => $request->input('conta_id') 
            ]);

            DB::commit();

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('movimentos.index' )->with('success', 'Movimento cadastrado com sucesso!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Movimento não incluido.', [ 'error' => $e->getMessage()]);
            // retorna ao form que chamou.
            return back()->withInput()->with('error', 'Movimento não cadastrado!');
        }
    }

    // **********************************************************************************
    //  alterar movimentos
    // **********************************************************************************
    
    //public function update(Request $request){
    //public function update(Request $request, Movimento $movimento){
    public function update(MovimentoEditRequest $request, Movimento $movimento){
        
        // recebe dados formulario no 'request' a ser validado, e a modal 'movimento' 
       //dd($request);

        //Log::info('Movimento alterar: {$movimento}', ['request' => $request ] );

        $validated = $request->validated();

                    //         dd( 'dt:' . $request->input('editdt') . 
                    //             'tipo_lc:' . $request->input('edittipo_lc') .
                    //             'tipo:' . $request->input('edittipo') .  
                    //             'categoria_id:' . $request->input('editcategoria_id') . 
                    //             'contascorrente_id:' . $request->input('econta_id') .
                    //             'descricao:' . $request->input('editDescricao') .
                    //             'dcto:' . $request->input('editdcto') .
                    //             'valor:' . $request->input('editValor') .
                    //             'forma_pgto_id:' . $request->input('eforma_pgto_id') 
                    //         );

        DB::beginTransaction();
        
        try {
            $movimento->update([
                'dt' => $request->input('editdt'),
                'tipo_lc' => $request->input('edittipo_lc') ,     
                'tipo' => $request->input('edittipo'),  
                'categoria_id' => $request->input('editcategoria_id'),
                'contascorrente_id' => $request->input('econta_id'),
                'descricao' => $request->input('editDescricao'),
                'dcto' => $request->input('editdcto'),
                //'valor' => $request->input('valor'),
                'valor' => str_replace(',', '.', str_replace('.', '', $request->input('editValor'))),
                'forma_pgto_id' => $request->input('eforma_pgto_id') ,
            ]);
            
            DB::commit();

            return redirect()->route('movimentos.index')->with('success', 'Movimento atualizado com sucesso!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('movimento não alterado.', [ 'error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Movimento não atualizado!');
        }
    }

    // **********************************************************************
    // Excluir o Movimento do banco de dados
    // **********************************************************************
    public function destroy(Movimento $movimento){
        
        // enviar ID movimento, na modal 'movimento' 
        // dd($movimento);
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
    }

    // importar arq csv
    public function importar(Request $request){

        //   montar select option categorias, apenas as que estão ativas, e indicadas recebem lcto
        $categorias = DB::select("SELECT * FROM categorias where lcto = '1' and ativo = '1' order by classif");
        // formulario para o filtro categorias, montar select option contas correntes
        $contascorrentes = DB::select("SELECT id, id as contascorrente_id , CONCAT_WS(' - ', nome, agencia, conta, id) as nome FROM contascorrentes");
        // formulario para o filtro forma_pgtos, montar select option
        $forma_pgtos = Forma_pgto::orderBy('nome', 'ASC')->get();

        // chamar a view e essa faz a chamada do form request
        return view('Movimentos.importar',
         ['menu' => 'movimentos importar' ,
          'categorias' => $categorias,
          'contascorrentes' => $contascorrentes,
          'forma_pgtos' => $forma_pgtos,
          'descricao' => '',
          'categoriabusca' => '',
          'data_de' => '',
          'tipo_filtro' => '',
         ]);
    }

    // laravel importar arq ofx - envia para rota formulario
    // ===========================================================================
    public function form(Request $request){

        //dd($request);      //dd('botao ' . $request->btn);
        // => no form colocado nome no botao para separar o tipo arq:  "btn" => "btncsv" ou "btn" => "btnofx" 
        //    receber dados da rota 'importar' faz request rota 'form' e processar controller 'form' e 
        //    retornar msg a rota 'importar' 
        // => ao exportar planilha para csv - cabecalho: Data;Histórico;Valor (US$);Valor(R$)
               // 1-separador campos ";"  2-separador milhar "."  3-retirar acentuacao do cabecalho 
               // 4-deixar a primeira linha do cabecalho

        if( $request->btn == "btncsv" )
        {   /*
            $request->validate(
                ['file' => 'required|mimes:csv|max:2048', ], ['file.required' => 'O arquivo é requerido (.csv)', 
                 'file.mimes' => 'O arquivo deve ser do tipo (.csv)', 'file.max' => 'Tamanho do arquivo excedido :max Mb'
                ]
            ); */

            // criar array colunas
            $headers = ['Data', 'Historico', 'Valor(US$)', 'Valor(R$)'];

            // Recebe o arquivo 'file' vem name formulario  input type="file" name="file"
            $datafile = file($request->file('file'));

            // validar dados arquivo, vem como string
            // dd( file($request->file('file')) );

            // 1) receber o arquivo, ler os dados e converter a string em array , usar array_map
            //    dd(array_map('str_getcsv', file( $request->file('file') ) ));
            $dataFile = array_map('str_getcsv', file( $request->file('file') ) );

            /*  //dd($dataFile);
            array_map 0 => array:1 [▶]  1 => array:1 [▶]  2 => array:1 [▶]   3 => array:1 [▶]
            array:19    0 => array:1 [▼] 0 => "Data;Historico;Valor(US$);Valor(R$)" ]
                        1 => array:1 [▼] 0 => "25/05;SEGURO SUPERPROTEGIDO;0.00;9.99" ]
            */
                        /*    
                    dd($dataFile);      $keyData 0 =>  1 => 2 =>
                    $keyData
                    0 => array:1 [▶]
                    1 => array:1 [▶]
                    2 => array:1 [▶]
                */

            // 2) percorrer as linhas do arquivo csv depois de usar o array_map
            foreach( $dataFile as $keyData => $row ){
                /*  dd($row);  com os dados do 1o. registro  
                    0 => array:1 $row[0]  0 => "Data;Historico;Valor(US$);Valor(R$)"
                    1 => array:1 [▼] $row[0]  0 => "25/05;SEGURO SUPERPROTEGIDO;0.00;9.99"
                */

                // converter a linha 'row' em array
                $values = explode(';' , $row[0]);

                /* dd($values);
                    array:4 [▼] 0 => "Data" 1 => "Historico" 2 => "Valor(US$)" 3 => "Valor(R$)" ]
                */

                // 3) percorrer as colunas do cabecalho, independente da qtde de colunas,
                //    se houver mais colunas, basta acresecentar no cabecalho variavel '$headers' ,
                //    $headers = ['Data', 'Historico', 'Valor(US$)', 'Valor(R$)'];
                //    '$key' tem os elementos colunas
                foreach( $headers as $key => $header ){

                    // 4) atribui o valor ao elemento do array
                    // 1o. elemento chave '$keyData'
                    //     '$key' tem os elementos colunas
                    $arrayValues[$keyData][$header] = $values[$key];
                }

            }

            // 5) array de valores
            /*  dd($arrayValues);
                0 => array:4 [▼] "Data" => "Data" "Historico" => "Historico" "Valor(US$)" => "Valor(US$)" "Valor(R$)" => "Valor(R$)"
                ]
                1 => array:4 [▼] "Data" => "25/05"  "Historico" => "SEGURO SUPERPROTEGIDO" "Valor(US$)" => "0.00" "Valor(R$)" => "9.99"
                ]
            */

            // cadastrar registros no banco dados, caso estejam campos esteja mesma ordem colunas
            // Movimento::insert($arrayValues);

            $jaexiste = 0;
            $texto = '';
            $regs  = 0;
            $falha = 0;
            //     para pegar dia '8' do mes atual:
            //$dt = \Carbon\Carbon::now()->startOfMonth();  // 1a. dia mes
            //$dt = \Carbon\Carbon::parse($dt)->addDays(7); // soma mais 7 dias , e dia 08 do mes
            // dd('data ' . $dt );

            // data de lancamento request do formulario:
            // if ($request->filled('data_de') && $request->filled('data_ate')) {
            //     $dt = $request->data_de; 
            // }

            // dd('dt:' . $request->data_de);
            $dt = $request->data_de;

            // 6) percorrer array de valores 
            foreach( $arrayValues as $key => $row ){

                // **********************************************************************************
                // deve buscar pelo campo 'descricao' + data do movimento + valor + dcto
                //   historico eh o docto 
                // **********************************************************************************

                // verificar valores
                // echo "<br> key " . $key . ' ' . $row['Data'] . ' ' . $row['Historico'] . ' ' . $row['Valor(US$)'] . ' ' . $row['Valor(R$)'];
                
                // 0 eh o cabecalho...
                if( $key > 0 ){

                    // o valor ja vem com o "." ponto
                    $vlr = $row['Valor(R$)'];
                    // 'valor' => str_replace(',', '.', str_replace('.', '', $vlr )),
                    // verificar pelo campo: historico + dt + valor + data se ja nao esta cadastrado!!!
                    $movimento_Dcto = Movimento::where('descricao', $row['Historico'])
                    ->where('dt', '=', $dt)
                    ->where('valor', '=', $vlr)
                    ->where('dcto', '=', $row['Data'])
                    ->get();
                    
                    //dd(' mov dcto:' . $movimento_Dcto);

                    if ($movimento_Dcto->isEmpty()) {

                        DB::beginTransaction();
                        try {
                            Movimento::create([
                            'dt' => $dt ,
                            'tipo_lc' => $request->tipo_filtro /*'P'*/ ,     
                            'tipo' => 0  /* 0-despesa 1-entrada */ ,   
                            'categoria_id' => $request->categoria_id /*42*/ ,
                            'descricao' => $row['Historico'] ,
                            'dcto' => $row['Data'] ,
                            'valor' => $vlr ,
                            'forma_pgto_id' => $request->forma_pgto_id /*6*/ , 
                            'contascorrente_id' => $request->contabusca /*2*/  
                            ]);
                            DB::commit();
                            $regs++;

                        } catch (Exception $e) {
                            DB::rollBack();
                            Log::error('Movimento não incluido.', [ 'error' => $e->getMessage()]);
                            $falha++;
                        }

                    }else{
                        $texto .= $row['Data'] . ' ' . $row['Historico'] . ' ' . $vlr . $dt;
                        $jaexiste++;
                    }
                }   
            }
            
            if( $falha > 0 ){
                return back()->withInput()->with('error', 'Movimento não cadastrado!');
            }else{
                //$regs = count($arrayValues) -1;
                return redirect()->route('movimentos.importar' )->with('success', 'Movimento cadastrado com sucesso! (' . $regs. ') , ja existe: ' . $jaexiste . ' (' . $texto . ')' );
            }       
        }

        // -------------- importar ofx
        // verificar se no import trouxe o http request
            // importar arquivo ofx , recebe o path + name arquivo

        if( $request->btn == "btnofx" )
        {
            /* 
            chatgpt: laravel salvar arquivo no c:\temp do windows
            ==================================================================================================
            use Illuminate\Support\Facades\Storage;
            use Illuminate\Support\Facades\File;

            // 1. Para uma pasta temporária de sistema (requer permissão)
            $pathTemp = 'C:\\Temp'; // Caminho absoluto no Windows
            $nomeArquivo = 'meu_arquivo.txt';
            $caminhoCompleto = $pathTemp . '\\' . $nomeArquivo;

            // Verifica e cria a pasta se não existir (requer permissão de administrador no Windows)
            if (!File::exists($pathTemp)) {
                File::makeDirectory($pathTemp, 0777, true); // 0777 para permissão ampla, mas cuidado com segurança
            }
            // Salva o arquivo
            File::put($caminhoCompleto, 'Conteúdo do arquivo.');

            // 2. Para arquivos temporários dentro do projeto Laravel (recomendado)
            // Cria a pasta 'temp' dentro de storage/app/ (privado)
            $pathStorageTemp = storage_path('app/temp');
            if (!File::exists($pathStorageTemp)) {
                File::makeDirectory($pathStorageTemp, 0777, true);
            }
            File::put($pathStorageTemp . '/' . $nomeArquivo, 'Conteúdo temporário do storage.');

            // 3. Para arquivos públicos (ex: imagens)
            // Cria a pasta 'temp' dentro de public/ (público)
            $pathPublicTemp = public_path('temp');
            if (!File::exists($pathPublicTemp)) {
                File::makeDirectory($pathPublicTemp, 0777, true);
            }
            File::put($pathPublicTemp . '/' . $nomeArquivo, 'Conteúdo público temporário.');

            // Se for para uso público, crie o link simbólico:
            // php artisan storage:link (no terminal)
            // Para acessar: /storage/temp/meu_arquivo.txt

            Pontos Importantes:
            Permissões: Salvar diretamente em C:\Temp pode falhar sem permissões de administrador. Prefira usar diretórios dentro do projeto Laravel (storage/app/temp ou public/temp).
            storage_path(): Retorna o caminho para a pasta storage/ (privada).
            public_path(): Retorna o caminho para a pasta public/ (acessível via web).
            File::put(): Salva o conteúdo em um arquivo.
            File::makeDirectory(): Cria diretórios, com permissões e recursividade. 
            
            Este vídeo explica como fazer upload de arquivos usando o framework Laravel
            https://www.youtube.com/watch?v=ZRNpbog-JBU
            
            ==================================================================================================
        
            */
        
            // originalName: "extrato.ofx"
            //dd($request);
            //dd( file($request->file('file')) );
            //file_put_contents('C:\\Temp\\extrato.ofx', file($request->file('file')));
            

            // c:\temp\extrato.ofx
            // sempre faz o upload do arquivo escolhido, e salvar na c:\temp do windows com nome fixo

            // 1. Para uma pasta temporária de sistema (requer permissão)
            $pathTemp = 'C:\\Temp'; // Caminho absoluto no Windows
            $nomeArquivo = 'extrato.ofx';
            $caminhoCompleto = $pathTemp . '\\' . $nomeArquivo;
            
            // Salva o arquivo - no caminho , e o conteudo
            //  conteudo em: file($request->file('file'))
            //  sempre sobrescreve o arquivo 
            File::put($caminhoCompleto, file($request->file('file')));

            // $request->validate(
            //     ['file' => 'required|mimes:ofx!max:2048',],
            //     ['file.required' => 'arq requerido', 'file.mimes' => 'arq deve ser do tipo ofx', 'file.max' => 'tamanho arq excedido :max Mb'
            //     ]
            // );

            // $request->validate(
            //     ['file' => 'required|max:2048', ],
            //     ['file.required' => 'O arquivo não foi informado!', 'file.max' => 'Tamanho arquivo excedido, tamanho :max Mb'
            //     ]
            // );
            
            // ler arquivo salvo em c:\temp do windows
            $caminho_do_arquivo = 'c:\\temp\\extrato.ofx';
            
            //função nativa do PHP 
            if (!file_exists($caminho_do_arquivo)) {
                return back()->withInput()->with('error', '(C:\temp\extrato.ofx) não encontrado!');
            }
            
            // ************************************************************************************
            // inicio rotina, ler arquivo ofx 
            // ************************************************************************************

            $ofxParser = new \OfxParser\Parser();

            $ofx = $ofxParser->loadFromFile( 'C:\temp\extrato.ofx' );

            // Acesse a primeira conta bancária (a variável $ofx contém um array de contas)
            $contaBancaria = reset($ofx->bankAccounts);
            $dataInicio = $contaBancaria->statement->startDate;
            $dataFim = $contaBancaria->statement->endDate;
            
            //dd('cc=' . $contaBancaria->accountNumber);

            //dd($contaBancaria);
            
            $di = \Carbon\Carbon::parse($dataInicio)->tz('America/Sao_Paulo')->format('d/m/Y');
            $df = \Carbon\Carbon::parse($dataFim)->tz('America/Sao_Paulo')->format('d/m/Y');
            
            // Obtenha as transações da conta
            $transacoes = $contaBancaria->statement->transactions;

            // Exemplo de como acessar uma transação específica
            if (!empty($transacoes)) {

                //$categorias = Categoria::orderBy('classif', 'ASC')->get();
                // filtrar categorias ativas e que esta indicado campo lcto que recebem lctos 
                $categorias = DB::select("SELECT * FROM categorias where lcto = '1' and ativo = '1' order by classif");

                $forma_pgtos = Forma_pgto::orderBy('nome', 'ASC')->get();

                // ***********************************************************************
                // buscar um id nulo, zero para carregar a model
                // ***********************************************************************
                $movimentos = Movimento::orderBy('descricao', 'ASC')
                ->where('id', 0)
                ->get();
                
                //$primeiraTransacao = reset($transacoes);
                //dd($contaBancaria);

                // leu do arquivo ofx
                $agcc = $contaBancaria->accountNumber;

                // ***************************************************************
                // while dados arq carregado.
                // ***************************************************************

                // em busca dos campos necessarios do arquivo ofx

                //DB::enableQueryLog();

                $descricaoCategorias = descricaoCategoria::all();

                //Log::info('sql descricaoCategoria', DB::getQueryLog() );

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
                    //dd($primeiraTransacao);


                    // 'tipo_lc': "O" - obrigacao
                    // tipo: $primeiraTransacao->type  'CREDIT' ou 'DEBIT'
                    $tipo = $primeiraTransacao->type == 'CREDIT' ? 'CR' : 'DB';
                    $dit = \Carbon\Carbon::parse($primeiraTransacao->date)->tz('America/Sao_Paulo')->format('d/m/Y');
                    //dd($dit);
                    $cat = 32;  // debito cartao

                    $string = $primeiraTransacao->memo;
                    
                    // *************************************************************************************
                    // executar um 'de' => 'para' na descricao do lancamento, 
                    //   e tentar localizar conforme a categoria
                    // a combo de categorias vai previamente preenchida
                    // *************************************************************************************

                    //if (Str::contains($string, $texto)) {
                    
                    //DB::enableQueryLog();

                    // $id_categoria = $descr_categoria->firstWhere('descricao_categoria', $string )?->categoria_id;

                    //$id_categoria = $descr_categoria[$string]->categoria_id ?? null;


                    //$descricaoCategorias = descricaoCategoria::all();
                    $nome = $string;
                    $descrCateg = $descricaoCategorias->first(function ($item) use ($nome) {
                        return str_contains(
                            strtolower($nome),
                            strtolower($item->descricao_categoria)
                        );
                    });
                    $categoria_id = $descrCateg?->categoria_id;
                    $id_categoria = $categoria_id;

                   
                    Log::info('string:', ['string' => $string ] );
                    Log::info('string:', ['id_categoria' => $id_categoria ] );
                    

                    if( $id_categoria ){
                        $cat = $id_categoria;
                    }else{
                        $cat = 33;
                    }

                    
                    /*
                    if (strpos( strtoupper($string), "CLINIPAM") !== false) {
                        $cat = 11;
                    }
                    if (strpos( strtoupper($string), "SAQ DIN") !== false) {
                        $cat = 41;
                    }
                    if (strpos( strtoupper($string), "AGUA") !== false) {
                        $cat = 14;
                    }
                    if (strpos( strtoupper($string), "TELEFONICA BRAS") !== false) {
                        $cat = 40;
                    }
                    if (strpos( strtoupper($string), "COPEL") !== false) {
                        $cat = 15;
                    }
                    if (strpos( strtoupper($string), "COPEL") !== false) {
                        $cat = 15;
                    }
                    if (strpos( strtoupper($string), "POSTO") !== false) {
                        $cat = 27;
                    }
                    if (strpos( strtoupper($string), "TV P/ASSINATURA") !== false) {
                        $cat = 17;
                    }
                    if (strpos( strtoupper($string), "PAGTO COBRANCA BANCO BRADESCO") !== false) {
                        $cat = 42;
                    }
                    if (strpos( strtoupper($string), "PAGTO COBRANCA ITAU UNIBANCO HOLDING") !== false) {
                        $cat = 43;
                    }
                    if (strpos( strtoupper($string), "MNINFO") !== false) {
                        $cat = 7;
                    }
                    if (strpos( strtoupper($string), "CLASSIC") !== false) {
                        $cat = 36;
                    }
                    if (strpos( strtoupper($string), "RENDIMENTOS") !== false) {
                        $cat = 39;
                    }
                    if ( (strpos(strtoupper($string), "CONDOR") !== false)  ) {
                        $cat = 45;
                    }
                    if (  (strpos(strtoupper($string), "JACOMAR") !== false) ) {
                        $cat = 45;
                    }
                    if (  (strpos(strtoupper($string), "FESTVAL") !== false) ) {
                        $cat = 45;
                    }
                    if ( (strpos(strtoupper($string), "RESTAURANTE") !== false) ) {
                        $cat = 25;
                    }
                    if ( (strpos(strtoupper($string), "BUFFETSKINA") !== false) ) {
                        $cat = 25;
                    }
                    if ( (strpos(strtoupper($string), "FARMACIA") !== false) ) {
                        $cat = 18;
                    }
                    if ( (strpos(strtoupper($string), "DROGARIA") !== false) ) {
                        $cat = 18;
                    }
                    if ( (strpos(strtoupper($string), "PIX") !== false) ) {
                        if ($tipo == "DB") {
                            $cat = 33;
                        }
                        if ($tipo == "CR") {
                            $cat = 9;
                        }
                    }
                    */

                    
                    // *************************************************************************************
                    // criar um elemento na modal
                    // *************************************************************************************
                    $movimentos->push(new Movimento(['id' => 0, 
                                                'dt' => $dit,
                                                'descricao' => $primeiraTransacao->memo , 
                                                'categoria_id' => $cat,
                                                'forma_pgto_id' => 1,
                                                'contascorrente_id' => 1,
                                                'tipo_lc' => 'O',
                                                'tipo' => $tipo,   
                                                'valor' => $primeiraTransacao->amount,
                                                'dcto' => $primeiraTransacao->checkNumber,
                                                ]));
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

                // retorna ao formulario 'form' com a preview dos lancamentos lidos do arq ofx
                // faz uma conferencia nos dados, e ao submit do form gerar os lancamentos lidos
                // a busca e pelo campo 'dcto' do lancamento, que é chave.
                // se nao localizar faz a inclusao do lcto,
                // ou seja um arquivo de movimentos ofx pode ser lido novamente.
                // pode ser que puxou os movimentos do dia 1 ate 15
                // e depois puxou novamente, agora com mais movimentos a partir do dia 15, 
                // entao os movimentos ja incluidos do dia 1-15 permanecem, e os novos a partir do dia 15
                // serao incluidos.

                return view('movimentos.form', ['movimentos' => $movimentos , 'categorias' => $categorias , 'forma_pgtos' => $forma_pgtos , 'agcc' => $agcc ]  );

                //target="_blank"
                //<a href="/sua-rota" target="_blank">Abrir em nova aba</a>

            
            }else{
                return back()->withInput()->with('error', 'Nenhuma transação encontrada!');
            }
        }
    }

    // **************************************************************************************
    // form update movimentos - vindo do form - ler ofx
    // **************************************************************************************
    
    public function updateMovimentos(Request $request){

        $data = $request;
        //dd($data);

        // buscar do extrato a agencia e conta...
        // no arq ofx a ag e conta vem concatenado

        $movimento_agcc = DB::select("SELECT id FROM contascorrentes 
                                      WHERE CONCAT(agencia, '/', conta) = ?
                            ", [$data->agcc]);

        // ********************************************************************************************
        // o DB::select() retorna um array de objetos. Então você precisa acessar o primeiro resultado 
        // ********************************************************************************************
        // seleionando id conta corrente
        $idcc = $movimento_agcc[0]->id ?? null;

        $falha = 0;
        $movIncluido = 0;
        $movjaexiste = 0;
        
        foreach ($data['itens'] as $item) {

            // localizar movimento pelo campo "dcto" chave registro, se nao existir inclui, caso contrario ja foi inserido.
            if( empty( $item['id'] ) ){
                
                $dataBr = $item['dt'];
                // Converter para formato MySQL
                $dataMysql = \Carbon\Carbon::createFromFormat('d/m/Y', $dataBr)->format('Y-m-d');
                
                $movDt = $dataMysql;
                
                // debug
                //DB::enableQueryLog();
                
                // **********************************************************************************
                
                // ///////////////////   importante !!!!   

                // deve buscar pelo campo 'dcto' + data do movimento
                
                // **********************************************************************************
                
                $movimento_Dcto = Movimento::where('dcto', $item['dcto'])
                ->where('dt', '=', $movDt)
                ->get();

                //if( empty($movimento_Dcto) ){    <<-- nao funciona 
                //if( !$movimento_Dcto ){          <<-- nao funciona 
                if ($movimento_Dcto->isEmpty()) {

                    DB::beginTransaction();

                    // ofx campo type => 'CREDIT' ou 'DEBIT'
                    // no form abreviado CR-CREDIT DB-DEBIT

                    //  gravar tabela movimentos
                    // form => name="tipo" => campo "tipo_lcto" : 1-Receita , 0-Despesa
                    
                    $tp = $item['tipo'] == 'CR' ? 1 : 0;

                    // validar campo valor do ofx , retirar sinal negativo e formato decimal
                    // no arquivo ofx vem sinal negativo!
                    if ( $item['tipo'] == 'DB'){
                        $vlr = str_replace(',', '.', str_replace('.', '', $item['valor'] )) * -1;
                    }else{
                        $vlr = str_replace(',', '.', str_replace('.', '', $item['valor'] ));
                    }

                    try {
                        
                        $movIncluido++;

                        //DB::enableQueryLog();

                        $Movimento = Movimento::create([
                            'dt' => $movDt ,
                            'descricao' => $item['descricao'] , 
                            'tipo' =>  $tp ,
                            'tipo_lc' => 'O',
                            'dcto' =>  $item['dcto'] ,
                            'valor' => $vlr, 
                            'categoria_id' => $item['categoria_id'],
                            'forma_pgto_id' => 1,
                            'contascorrente_id' => $idcc
                        ]);

                        //dd(DB::getQueryLog());
                        //exit;

                        // 'contascorrente_id' => 1  era fixo agora busca do arq ofx a conta

                        // Operação é concluída com êxito, conseguiu cadastrar sem erros
                        DB::commit();
                        // registrar apenas log de erro se houver !
                        // Log::info('Movimento incluido: {id}', ['id' => $Movimento ] );

                    } catch (Exception $e) {

                        $falha = 1;
                        
                        // Operação não é concluída com êxito
                        DB::rollBack();
                        Log::error('Movimento não incluido.', [ 'error' => $e->getMessage()]);
                    }

                    

                }else{
                    $movjaexiste++;
                    //apresentar msg ao final !
                    //Log::info('Movimento já existe: {$movimento_Dcto->id}', ['id' => $movimento_Dcto ] );
                }
            }
        }

        // *******************************************************************************
        // apresentar msg ao form que chamou !
        // *******************************************************************************
        if( $falha == 0 ){   

            // Carregar a VIEW
            
            $msgmov = 'Movimentos cadastrados com sucesso: (' . $movIncluido . ') movimentos!'; 
            if( $movjaexiste ){
                $msgmov .= ' ( ' . $movjaexiste . ' ) Movimento já existe!';
            }
            return redirect()->route('movimentos.index')->with('success', $msgmov );

        }else{
            $msgmov = 'Movimentos não cadastrados!';
            if( $movjaexiste ){
                $msgmov .= ' ( ' . $movjaexiste . ' ) Movimento já existe!';
            }

            return back()->withInput()->with('error', $msgmov );
        }
    }
    
    // ***********************************************************************************
    // chamada metodo - rota - graficos
    // ***********************************************************************************
    
    public function pie(Request $request)
    {   

        // receb o request com os filtros, para aplicar a consulta e montar os dados e gerar grafico

        // "filtro" => "Tipo=O data_de=2025-12-01 data_ate=2025-12-31"
        //  "tipo_filtro" => "O"
        //  "data_de" => "2025-12-01"
        //  "data_ate" => "2025-12-31"

        // dd($request->all());
        // recebe os filtros para montar a consulta

        // ----------------------  debug query
        
        //DB::enableQueryLog();
        
        /*
        $query = DB::table('movimentos');

        if ($request->filled('descricao')) {
            $query->where('descricao', 'like', "%{$request->descricao}%");
        }

        if ($request->filled('tipo_filtro')) {
            $query->where('tipo_lc', $request->tipo_filtro);
        }

        if ($request->filled('data_de')) {
            $query->whereDate('dt', '>=', $request->data_de);
        }
    
        if ($request->filled('data_ate')) {
            $query->whereDate('dt', '<=', $request->data_ate);
        }
        
         // Exemplo: total por tipo
        $dados = $query
            ->select('tipo', DB::raw('SUM(valor) as total'))
            ->groupBy('tipo')
            ->get();
            
        */


               /*
        $query = DB::table('movimentos');

        if ($request->filled('descricao')) {
            $query->where('descricao', 'like', "%{$request->descricao}%");
        }

        if ($request->filled('tipo_filtro')) {
            $query->where('tipo_lc', $request->tipo_filtro);
        }

        if ($request->filled('data_de')) {
            $query->whereDate('dt', '>=', $request->data_de);
        }
    
        if ($request->filled('data_ate')) {
            $query->whereDate('dt', '<=', $request->data_ate);
        }
        
         // Exemplo: total por tipo
        $dados = $query
            ->select('categorias.nome', DB::raw('SUM(movimentos.valor) as total'))
            ->join('categorias', 'movimentos.categoria_id', '=', 'categorias.id')
            ->where('categorias.soma', '=', 'S' )
            ->groupBy('categorias.nome')
            ->get();
            */


        $query = DB::table('movimentos')
            ->join('categorias', 'movimentos.categoria_id', '=', 'categorias.id')
            ->where('categorias.soma', '1');

        if ($request->filled('descricao')) {
            $query->where('descricao', 'like', "%{$request->descricao}%");
        }

        if ($request->filled('tipo_filtro')) {
            $query->where('tipo_lc', $request->tipo_filtro);
        }

        $query->where('movimentos.contascorrente_id', $request->contabusca);

        if ($request->filled('data_de') && $request->filled('data_ate')) {
            $query->whereBetween('movimentos.dt', [
                $request->data_de,
                $request->data_ate
            ]);
        }

        // ->groupBy('categorias.nome')
        // 'categorias.nome',

        if (!$request->filled('tipo_filtro')) { 
            $dados = $query
            ->groupBy('movimentos.tipo_lc', 'categorias.nome')
            ->select(
                DB::raw("CONCAT_WS(' ', categorias.nome , movimentos.tipo_lc ) as nome"),
                DB::raw("SUM(movimentos.valor) as total")
            )
            ->orderBy('nome')
            ->get();
        }else{
            $dados = $query
            ->groupBy('categorias.nome')
            ->select(
                'categorias.nome',
                DB::raw('SUM(movimentos.valor) as total')
            )
            ->orderBy('total')
            ->get();
        }
        
        $totalDespesa = 0;
        
        foreach ($dados as $dad) {
            $totalDespesa += $dad->total;
            
        }

         //dd(DB::getQueryLog());
            /*
        select `categorias`.`nome`, SUM(movimentos.valor) as total 
        from `movimentos` 
        inner join `categorias` on `movimentos`.`categoria_id` = `categorias`.`id` 
        where `categorias`.`soma` = ? and `movimentos`.`dt` between ? and ? 
        group by `categorias`.`nome`

        select categorias.nome, SUM(movimentos.valor) as total 
        from movimentos 
        inner join categorias on movimentos.categoria_id = categorias.id 
        where categorias.soma = 'S' and movimentos.dt between '2026-01-01' and '2026-01-22'  
        group by categorias.nome

        select CONCAT(movimentos.tipo_lc , ' ', categorias.nome ) as nome, SUM(movimentos.valor) as total
        from movimentos 
        inner join categorias on movimentos.categoria_id = categorias.id 
        where categorias.soma = 'S' and movimentos.dt between '2026-01-01' and '2026-01-22'  
        group by movimentos.tipo_lc , categorias.nome

        select CONCAT_ws(' ', movimentos.tipo_lc , categorias.nome ) as nome, SUM(movimentos.valor) as total
        from movimentos 
        inner join categorias on movimentos.categoria_id = categorias.id 
        where categorias.soma = 'S' and movimentos.dt between '2026-01-01' and '2026-01-22'  
        group by movimentos.tipo_lc , categorias.nome

        */
        
        //dd($request->grafico);
        
        // -------- versao 1
        return view('Movimentos.pie', [
            'descricao'    => $request->descricao,
            'data_de'      => $request->data_de,
            'data_ate'     => $request->data_ate,
            'tipo_filtro'  => $request->tipo_filtro,
            'labels'       => $dados->pluck('nome'),
            'values'       => $dados->pluck('total'),
            'dados'        => $dados,
            'totalDespesa' => $totalDespesa,
            'grafico'      => $request->grafico
        ]);
        
       

        
    }

    // *****************************************************************************
    // executa a rota - relatorio
    // enviar controller e retorna form visualizar
    // *****************************************************************************
    public function relatorioselecao(){
        
        //$categorias = Categoria::orderBy('classif', 'ASC')->get();

        $categorias = DB::select("SELECT * FROM categorias where lcto = '1' and ativo = '1' order by classif");


        return view('Movimentos.relatorioselecao', 
         ['menu' => 'movimentos-relatorio',
          'categorias' => $categorias]);
    }

    public function relatoriovisualizacao(Request $request){

        /*
        consulta agrupada movimentos por categoria
        ------------------------------------------------

        SELECT * 
        FROM ( 
        SELECT tipo_lc,categorias.nome,categorias.classif, sum(movimentos.valor) as valor 
        FROM movimentos 
        INNER JOIN categorias ON movimentos.categoria_id = categorias.id 
        WHERE tipo_lc = 'O' AND dt BETWEEN '2026-01-01' AND '2026-01-12' 
        GROUP BY tipo_lc,categorias.nome,categorias.classif 
        UNION ALL 
        SELECT tipo_lc,categorias.nome,categorias.classif, sum(movimentos.valor) as valor 
        FROM movimentos 
        INNER JOIN categorias ON movimentos.categoria_id = categorias.id 
        WHERE tipo_lc = 'P' AND dt BETWEEN '2026-01-01' AND '2026-01-12' 
        GROUP BY tipo_lc,categorias.nome,categorias.classif ) AS subquery_combinada 
        ORDER BY classif, tipo_lc;

        ================ consulta atual desligada 
        
            select 'despesa' as tipo_mov, m.dt, m.descricao, m.dcto ,
            c.nome, c.classif , fp.id as formapg, fp.nome, c2.id, c2.nome as conta,
            0.00 as recebido, m.valor as pago
            from movimentos m
            join categorias c on c.id = m.categoria_id 
            join forma_pgtos fp on fp.id = m.forma_pgto_id 
            join contascorrentes c2 on c2.id = m.contascorrente_id 
            where m.dt >= '2026-06-01' and m.dt <= '2026-06-30' and m.tipo_lc = 'O'  and m.tipo = 0 
            union all 
            select 'receita' as tipo_mov, m2.dt, m2.descricao, m2.dcto ,
                c.nome, c.classif, fp.id as formapg, fp.nome, c2.id, c2.nome as conta,
                m2.valor as recebido, 0.00 as pago
            from movimentos m2
            join categorias c on c.id = m2.categoria_id 
            join forma_pgtos fp on fp.id = m2.forma_pgto_id 
            join contascorrentes c2 on c2.id = m2.contascorrente_id 
            where m2.dt >= '2026-06-01' and m2.dt <= '2026-06-30' and m2.tipo_lc = 'O'  and m2.tipo = 1
            order by 6, 2
        
            - Usar DB::select para executar a consulta SQL bruta

                nao usar: ->with(['categoria', 'forma_pgto']) 
                pois a consulta e vem 

                where 'categorias'.'id' in (7, 15, 17, 18, 25, 26, 27, 28, 29, 32, 33, 36, 38, 39, 40, 41, 42, 43, 45, 46, 48)
                where 'forma_pgtos'.'id' in (1, 5, 6)

                melhor declarar no join ai vem assim
                inner join categorias on movimentos.categoria_id = categorias.id 

            $resultados = DB::select("
                SELECT *
                FROM (
                    SELECT 
                        'despesa' as tipo_mov, movimentos.dt, movimentos.descricao, movimentos.dcto ,
                        categorias.nome as categoria, categorias.classif , forma_pgtos.id as formapg, forma_pgtos.nome as formanome, contascorrentes.id, contascorrentes.nome as conta,
                        0.00 as recebido, movimentos.valor as pago
                    FROM movimentos 
                    join categorias on categorias.id = movimentos.categoria_id
                    join forma_pgtos on forma_pgtos.id = movimentos.forma_pgto_id
                    join contascorrentes on contascorrentes.id = movimentos.contascorrente_id
                        
                    WHERE
                        movimentos.tipo_lc = 'O' AND movimentos.tipo = 0 and movimentos.dt BETWEEN :dataInicioO AND :dataFimO
                    
                    UNION ALL

                    SELECT
                        'receita' as tipo_mov, movimentos.dt, movimentos.descricao, movimentos.dcto ,
                        categorias.nome as categoria, categorias.classif, forma_pgtos.id as formapg, forma_pgtos.nome as formanome, contascorrentes.id, contascorrentes.nome as conta,
                        movimentos.valor as recebido, 0.00 as pago
                    FROM movimentos 
                    join categorias on categorias.id = movimentos.categoria_id
                    join forma_pgtos on forma_pgtos.id = movimentos.forma_pgto_id
                    join contascorrentes on contascorrentes.id = movimentos.contascorrente_id
                    WHERE
                        movimentos.tipo_lc = 'O' and movimentos.tipo = 1 AND movimentos.dt BETWEEN :dataInicioP AND :dataFimP
                    
                ) AS subquery_combinada
                ORDER BY
                    5, 2;
                ", [
                    // Passar os bindings para prevenir SQL Injection
                    'dataInicioO' => $dataInicio,
                    'dataFimO' => $dataFim,
                    'dataInicioP' => $dataInicio,
                    'dataFimP' => $dataFim,
                    
                ]);

                ==============================================================================
                 apresentar mes ano 

                SELECT *
                    FROM (
                        SELECT 
                            DATE_FORMAT(movimentos.dt,  '%m/%Y') AS ano_mes, 
                            categorias.classif , categorias.nome, movimentos.tipo,
                            sum(movimentos.valor) as valor 
                        FROM movimentos 
                        join categorias on categorias.id = movimentos.categoria_id
                            
                        WHERE
                            movimentos.tipo_lc = 'O' and movimentos.dt BETWEEN '2026-04-01' AND '2026-07-21'
                        group by 
                            DATE_FORMAT(movimentos.dt,  '%m/%Y'),  
                            categorias.classif , categorias.nome , movimentos.tipo
                            
                    ) AS subquery_combinada
                    ORDER by
                        2 , 1

        */

        //dd($request);

        //DB::enableQueryLog();

        // ====================== tratamento filtros:
        // ============================================
        $dataDe  = $request->filled('data_de')
            ? \Carbon\Carbon::parse($request->data_de)->startOfDay()
            : \Carbon\Carbon::now()->startOfDay();

        $dataAte = $request->filled('data_ate')
            ? \Carbon\Carbon::parse($request->data_ate)->endOfDay()
            : \Carbon\Carbon::now()->endOfDay();
    
        $dataInicio = $dataDe;
        $dataFim    = $dataAte;
        $sel = "";

        if ( $request->filled('data_de') && $request->data_de ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Data de:" . \Carbon\Carbon::parse($request->data_de)->format('d-m-Y');
        }
        if ( $request->filled('data_ate') && $request->data_ate ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Data ate:" . \Carbon\Carbon::parse($request->data_ate)->format('d-m-Y');
        }

        $categoria_id = null;
        if( $request->has('categoria_id') && $request->categoria_id ){
            $categrel = Categoria::where('id', $request->categoria_id)->get()->first();
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Categoria:{$categrel->nome}";
            $categoria_id = $categrel->id;

            //dd('categoria:' . $categrel->id);
        }

        if( $request->has('OmitirData') && $request->OmitirData ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= "Sintético";
            $OmitirData = "S";
        }else{
            $OmitirData = "N";
        }        
        if( $request->has('mesAno') && $request->mesAno ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= "Agrupar por Mês/Ano";
            $mesAno = "S";
        }else{
            $mesAno = "N";
        }

        if( $request->has('tipo_filtro') && $request->tipo_filtro ){
            $sel .= $sel ? ', ' : '' ;
            if( $request->tipo_filtro == 'O' ){
                $sel .= " Tipo:Obrigação";
            }else{
                $sel .= " Tipo:Previsão";
            }                    
        }




                 // 1. Busca os dados trazendo o relacionamento da categoria

        //$movimentos = Movimento::with('categoria')
        //->join('categorias', 'movimentos.categoria_id', '=', 'categorias.id')

        // ====================== tratamento consultas:
        // ============================================  
        if( $OmitirData == "N" ){   

            $movimentos = Movimento::select('movimentos.*', 'categorias.nome', 'categorias.classif', 'movimentos.categoria_id')
            ->leftJoin('categorias', 'movimentos.categoria_id', '=', 'categorias.id')
            ->when($request->filled('categoria_id'), function ($q) use ($request) {
            $q->where('movimentos.categoria_id', '=', $request->categoria_id );
            })
            ->whereBetween('dt', [$dataDe, $dataAte])
            //->where('tipo_lc', 'O')
            ->when($categoria_id !== null, function ($query) use ($categoria_id) {
                $query->where('movimentos.categoria_id', $categoria_id);
            })
            ->where('tipo_lc', $request->tipo_filtro)
            ->orderBy('dt', 'asc')
            //->orderBy('categoria_id', 'asc')
            ->orderBy('categorias.classif', 'asc')
            ->get();
        }else{
 
            if( $mesAno == "N" ){    
                $movimentos = Movimento::select('movimentos.*',  'categorias.nome', 'categorias.classif',
                    DB::raw("YEAR(movimentos.dt) AS ano"),
                    DB::raw("MONTH(movimentos.dt) AS mes"),
                    DB::raw(" concat( categorias.classif, '-', concat( YEAR(movimentos.dt) , '/', MONTH(movimentos.dt) ) ) AS ano"),
                )
                ->leftJoin('categorias', 'movimentos.categoria_id', '=', 'categorias.id')
                ->when($request->filled('categoria_id'), function ($q) use ($request) {
                $q->where('movimentos.categoria_id', '=', $request->categoria_id );
                })
                ->whereBetween('dt', [$dataDe, $dataAte])
                //->where('tipo_lc', 'O')
                ->when($categoria_id !== null, function ($query) use ($categoria_id) {
                    $query->where('movimentos.categoria_id', $categoria_id);
                })
                ->where('tipo_lc', $request->tipo_filtro)
                ->orderBy('categorias.classif', 'asc')
                ->orderBy('dt', 'asc')
                ->get();
            }    

            if( $mesAno == "S" ){ 
            
                // O Query Builder e o Eloquent são mais legíveis, facilitam a manutenção e já fazem o binding dos parâmetros
                // No DB::table() você deve usar DB::raw() para funções do MySQL

                // 'DB::select' o retorno é um array de objetos
                $movimentos = DB::table('movimentos as m')
                ->leftJoin('categorias as c', 'm.categoria_id', '=', 'c.id')
                ->select(
                    DB::raw("YEAR(m.dt) AS ano"),
                    DB::raw("MONTH(m.dt) AS mes"),
                    //DB::raw("DATE_FORMAT(m.dt, '%m/%Y') AS ano_mes"),
                    'c.classif',
                    'c.nome',
                    'm.tipo',
                    //'m.dt',
                    DB::raw("SUM(m.valor) AS valor")
                )
                ->where('m.tipo_lc', $request->tipo_filtro)
                ->whereBetween('m.dt', [$dataInicio, $dataFim])
                ->when($categoria_id !== null, function ($query) use ($categoria_id) {
                    $query->where('m.categoria_id', $categoria_id);
                })
                ->groupBy(
                    DB::raw("YEAR(m.dt)"),
                    DB::raw("MONTH(m.dt)"),
                    'c.classif',
                    'c.nome',
                    'm.tipo'
                )
                ->orderBy('c.classif')
                ->orderBy('ano')
                ->orderBy('mes')
                ->get();                
            }
        }

        //dd(DB::getQueryLog()); 

        //Log::info('sql movimento', DB::getQueryLog() );



        // =============================================================
        // ----- tratamento das consultas,  novo relatorio agrupado por data e categoria

        // 1. Busca os registros já ordenados do banco de dados
                // $vendas 

       
        // 2. Agrupa primeiro por DATA, e dentro de cada data, agrupa por descricao
        if( $OmitirData == "N" ){

            $movimentosAgrupados = $movimentos->groupBy([
                function ($item) {
                    return \Carbon\Carbon::parse($item->dt)->format('Y-m-d'); // Primeiro nível: Data
                },
                function ($item) {
                    return $item->categoria?->nome ?? 'Sem Categoria';  // Segundo nível: descricao
                },
                
            ]);

        }else{
            
            if( $mesAno == "N" ){
                $movimentosAgrupados = $movimentos->groupBy([
                    function ($item) {
                       // return $item->categoria?->classif ?? 'Sem Categoria';  //  nível: classif

                        return $item->ano ;  //  nível: classif
                    },
                    
                ]);
            }
            
        }

        // 3. Total Geral de todo o período 
        //=======================================================================================

        /*
        $totalDespesa = 0;
        $totalDespesa = $movimentos->filter(function ($movimento) {
            return $movimento->tipo == 0;
        })->sum('valor');

        $totalReceita = 0;
        $totalReceita = $movimentos->filter(function ($movimento) {
            return $movimento->tipo == 1;
        })->sum('valor');

        $totalGeral = $totalReceita - $totalDespesa;
        */
        
        
        if( $OmitirData == "N" ){
            
            $totalDespesa = 0;
            $totalDespesa = $movimentos->filter(function ($movimento) {
                return $movimento->tipo == 0;
            })->sum('valor');

            $totalReceita = 0;
            $totalReceita = $movimentos->filter(function ($movimento) {
                return $movimento->tipo == 1;
            })->sum('valor');

            $totalGeral = $totalReceita - $totalDespesa;

        }else{
            //$totalGeral = collect($movimentos)->sum('valor');   


            //$totalRecebido = collect($movimentos)->sum('recebido');
            //$totalPago     = collect($movimentos)->sum('pago');

            $totalDespesa = 0;
            $totalDespesa = collect($movimentos)->where('tipo', 0)->sum('valor');

            $totalReceita = 0;
            $totalReceita = collect($movimentos)->where('tipo', 1)->sum('valor');

            $totalGeral = $totalReceita - $totalDespesa;

        }   
        

       

        // 3. Total Geral de todo o período
        //=======================================================================================
        
        if( $mesAno == "S" ){
            
            $movimentosAgrupados = ""; 
        }
        

        // ====================== tratamento retorno para view:
        // ============================================

        return view('Movimentos.relatoriovisualizacao', compact('movimentosAgrupados', 'totalGeral', 'totalDespesa', 'totalReceita', 'sel', 'OmitirData', 'movimentos', 'mesAno') );


    }

    // *****************************************************************************
    // executa a rota - relatorio balancete selecao
    // enviar controller e retorna form visualizar
    // *****************************************************************************
    public function relatorioBalanceteselecao(){
        
        return view('Movimentos.relatorioBalanceteselecao', 
         ['menu' => 'movimentos-relatorio Balancete']);
    }

    public function relatorioBalancetevisualizacao(Request $request){
                //dd('balancete relatorio visualizacao');
                //dd($request);

        $sel = "";
        if ( $request->filled('data_de') && $request->data_de ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Data de:" . \Carbon\Carbon::parse($request->data_de)->format('d-m-Y');
            $dataInicio = $request->data_de;
        }
        if ( $request->filled('data_ate') && $request->data_ate ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Data ate:" . \Carbon\Carbon::parse($request->data_ate)->format('d-m-Y');
            $dataFim = $request->data_ate;
        }

        //$dataInicio = "2026-07-01"; 
        //$dataFim = "2026-07-29";

        //DB::enableQueryLog();

        $movimentos = DB::select("
            SELECT r2.classif, r2.nome, sum(r2.total) as total
            FROM (
                SELECT p1.classif, p1.nome, COALESCE(SUM(m.valor),0) AS total
                FROM  categorias p1 
                LEFT JOIN categorias p2 ON p2.classif  LIKE CONCAT(p1.classif , '%')
                LEFT JOIN movimentos m ON m.categoria_id = p2.id
                WHERE
                    m.tipo_lc = 'O' and m.dt BETWEEN :dataInicioO AND :dataFimO
                GROUP BY p1.classif, p1.nome
                union
                select p1.classif, p1.nome, COALESCE(SUM(0.00),0) AS total
                FROM  categorias p1 
                LEFT JOIN categorias p2 ON p2.classif  LIKE CONCAT(p1.classif , '%')
                GROUP by p1.classif, p1.nome 
            ) AS r2
              group by r2.classif, r2.nome
            ORDER BY
                r2.classif;
            ", [
                // Passar os bindings para prevenir SQL Injection
                'dataInicioO' => $dataInicio,
                'dataFimO' => $dataFim,
                
            ]);
            
            //Log::info('sql movimento_rel_balancete', DB::getQueryLog() );

            //dd($resultados);

            // echo "<pre>";
            // print_r($resultados);
            // echo "</pre>";

        // ====================== tratamento retorno para view:
        // ============================================

        return view('Movimentos.relatorioBalancetevisualizacao', compact('movimentos', 'sel') );


    }
}
