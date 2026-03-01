<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovimentoEditRequest;
use App\Http\Requests\MovimentoRequest;
use App\Models\Categoria;
use App\Models\ContasCorrente;
use App\Models\Forma_pgto;
use App\Models\Movimento;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
    // index
    //public function index(categoria $categoria )
    public function index(Request $request)
    {
        //dd('tipo_filtro=' . $request->tipo_filtro);

        DB::enableQueryLog();
        
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
        */ 

        $dataDe  = $request->filled('data_de')
            ? \Carbon\Carbon::parse($request->data_de)->startOfDay()
            : \Carbon\Carbon::now()->startOfDay();

        $dataAte = $request->filled('data_ate')
            ? \Carbon\Carbon::parse($request->data_ate)->endOfDay()
            : \Carbon\Carbon::now()->endOfDay();
            
            /*
            $movimentos = Movimento::select('movimentos.*')
            ->join('categorias', 'movimentos.categoria_id', '=', 'categorias.id')
            ->with(['categoria', 'forma_pgto'])
            */
                                         
        //$movimentos = Movimento::select('movimentos.*')

        $movimentos = Movimento::select('movimentos.*')
            ->join('categorias', 'movimentos.categoria_id', '=', 'categorias.id')
            ->join('contascorrentes', 'movimentos.contascorrente_id', '=', 'contascorrentes.id')
            ->join('forma_pgtos', 'movimentos.forma_pgto_id', '=', 'forma_pgtos.id')
            ->with(['categoria', 'forma_pgto', 'contascorrente'])
            ->when($request->filled('descricao'), function ($q) use ($request) {
                $q->where('descricao', 'like', '%' . $request->descricao . '%');
            })
            /*->when($request->filled('categoriabusca'), function ($q) use ($request) {
                $q->where('categorias.nome', '=', $request->categoriabusca );
            })*/
            ->when($request->filled('categoria_id'), function ($q) use ($request) {
                $q->where('categorias.id', '=', $request->categoria_id );
            })
            ->when($request->filled('contabusca'), function ($q) use ($request) {
                $q->where('movimentos.contascorrente_id', '=', $request->contabusca );
            })
            ->when($request->filled('tipo_filtro'), function ($q) use ($request) {
                $q->where('tipo_lc', $request->tipo_filtro);
            })
            ->whereBetween('dt', [$dataDe, $dataAte])
            ->orderBy('movimentos.dt', 'asc')
            ->orderBy('categorias.classif', 'asc')
            ->get();  // get vai retornar uma collection 
            //->firstOrFail();

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
            $cc = ContasCorrente::where('id', $request->contabusca)->first();
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Conta corrente:{$request->contabusca} {$cc->nome} - {$cc->agencia} - {$cc->conta}";
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

        $categorias = Categoria::orderBy('classif', 'ASC')
        ->get();

        // $contascorrentes = ContasCorrente::orderBy('nome', 'ASC')
        // ->get();

        // CREATE VIEW view_contas AS
        // SELECT
        //     CONCAT(
        //         COALESCE(nome, ''),
        //         COALESCE(agencia, ''),
        //         COALESCE(conta, '')
        //     ) AS nome_completo
        // FROM contascorrentes;

        // SELECT CONCAT_WS('', nome, agencia, conta) as nome
        // FROM contascorrentes;

        // SELECT CONCAT_WS(' - ', nome, agencia, conta)
        // FROM contascorrentes;

        //$contascorrentes = ContasCorrente::orderBy('nome', 'ASC')
        //->get();

        $contascorrentes = DB::select("SELECT id, id as contascorrente_id , CONCAT_WS(' - ', nome, agencia, conta, id) as nome FROM contascorrentes");

        //$contascorrentes = ContasCorrente::orderBy('nome', 'ASC')
        //->get();

        $forma_pgtos = Forma_pgto::orderBy('nome', 'ASC')
        ->get();

        //dd($contascorrentes);

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
        $totalDespesa = 0;
        $totalDespesa = $movimentos->filter(function ($movimento) {
            return $movimento->tipo == 0;
        })->sum('valor');

        $totalReceita = 0;
        $totalReceita = $movimentos->filter(function ($movimento) {
            return $movimento->tipo == 1;
        })->sum('valor');

        $total = $totalReceita - $totalDespesa; 


        // nao funciona Log::info('sql' , ['' => $movimentos->toRawSql()]);
        // nao funciona dd($movimentos->toSql());


        // ===========================================================================
        //       debug sql query   dd(DB::getQueryLog())
        // ===========================================================================
        
        //dd(DB::getQueryLog());   //esse funciona ok!

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

        // 'qtde_registros' => count($movimentos)

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
                'forma_pgto_id' => $request->input('forma_pgto_id') , // ou pegue do formulário
                'contascorrente_id' => $request->input('conta_id') // ou pegue do formulário
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
    //public function update(Request $request, Movimento $movimento){
    public function update(MovimentoEditRequest $request, Movimento $movimento){
        
       //dd($request);

        //Log::info('Movimento alterar: {$movimento}', ['request' => $request ] );

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
                    //         dt:2026-02-01
                    // tipo_lc:O
                    // tipo:1
                    // categoria_id:38
                    // contascorrente_id:1
                    // descricao:Sld Ant
                    // dcto:0
                    // valor:874,20
                    // forma_pgto_id:7


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
                'contascorrente_id' => $request->input('econta_id'),

                'descricao' => $request->input('editDescricao'),
                'dcto' => $request->input('editdcto'),
                //'valor' => $request->input('valor'),
                'valor' => str_replace(',', '.', str_replace('.', '', $request->input('editValor'))),
                // Adicione `forma_pgto_id` se estiver usando
                'forma_pgto_id' => $request->input('eforma_pgto_id') ,
                //'contascorrente_id' => 1 // ou pegue do formulário
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
    
    public function form(Request $request){

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
        
        // 1. Para uma pasta temporária de sistema (requer permissão)
        $pathTemp = 'C:\\Temp'; // Caminho absoluto no Windows
        $nomeArquivo = 'extrato.ofx';
        $caminhoCompleto = $pathTemp . '\\' . $nomeArquivo;
        
        // Salva o arquivo - no caminho , e o conteudo
        //  conteudo em: file($request->file('file'))
        //  sempre sobrescreve o arquivo 
        File::put($caminhoCompleto, file($request->file('file')));

        //dd($request);



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
        
        // ler arquivo salvo em c:\temp do windows
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
                $cat = 32;  // debito cartao

                $string = $primeiraTransacao->memo;
                
                
                //if (Str::contains($string, $texto)) {
                
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
                

                $movimentos->push(new Movimento(['id' => 0, 
                                            'dt' => $dit,
                                            'descricao' => $primeiraTransacao->memo , 
                                            'categoria_id' => $cat,
                                            'forma_pgto_id' => 1,
                                            'contascorrente_id' => 1,
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
        $movjaexiste = 0;

        foreach ($data['itens'] as $item) {

            if( empty( $item['id'] ) ){
                
                // localizar movimento pelo campo "dcto" chave registro, 
                //    se nao existir inclui, caso contrario ja foi inderido.
                
                $dataBr = $item['dt'];
                // Converter para formato MySQL
                $dataMysql = \Carbon\Carbon::createFromFormat('d/m/Y', $dataBr)
                        ->format('Y-m-d');
                
                $movDt = $dataMysql;
                
                //dd($movDt);

                // debug
                //DB::enableQueryLog();
                
                /*
                Buscar um registro e testar se existe
                $user = User::where('email', 'joao@email.com')->first();
                if ($user) {
                    echo "Usuário encontrado: " . $user->name;
                } else {
                    echo "Usuário não encontrado.";
                }
                */

                //$movimento_Dcto = Movimento::where('dcto', $item['dcto'])->first();
                
                $movimento_Dcto = Movimento::where('dcto', $item['dcto'])
                ->where('dt', '=', $movDt)
                ->get();
                
                // dd($item['dcto']);  0000726  0000099

                // if( $item['dcto'] == '0000726'){ 
                //     dd(ini_get('max_input_vars'));
                //     dd('dt=' . $item['dt'] . ' descr=' . $item['descricao'] . ' tipo=' . $item['tipo'] . 
                //     ' dcto=' . $item['dcto'] . ' valor=' . $item['valor'] . ' cat=' . $item['categoria_id'] );
                // }

                //dd($movimento_Dcto);

                // debug
                //dd(DB::getQueryLog());

                //dd($Movimento->dcto);

                //if( empty($movimento_Dcto) ){
                //if( !$movimento_Dcto ){
                if ($movimento_Dcto->isEmpty()) {

                    DB::beginTransaction();

                    // ofx campo type => 'CREDIT' ou 'DEBIT'
                    // no form abreviado CR-CREDIT DB-DEBIT

                    //  gravar tabela movimentos
                    // form => name="tipo" => campo "tipo_lcto" : 1-Receita , 0-Despesa

                    
                    $tp = $item['tipo'] == 'CR' ? 1 : 0;
                    

                    //$dataBr = $item['dt'];
                    // Converter para formato MySQL
                    //$dataMysql = \Carbon\Carbon::createFromFormat('d/m/Y', $dataBr)
                    //        ->format('Y-m-d');
                    
                    //$movDt = $dataMysql;

                    //dd($movDt);

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
                            'forma_pgto_id' => 1,
                            'contascorrente_id' => 1
                        ]);

                        // Operação é concluída com êxito, conseguiu cadastrar sem erros
                        DB::commit();

                        Log::info('Movimento incluido: {id}', ['id' => $Movimento ] );

                    } catch (Exception $e) {

                        $falha = 1;
                        
                        // Operação não é concluída com êxito
                        DB::rollBack();
                        Log::error('Movimento não incluido.', [ 'error' => $e->getMessage()]);
                    }
                }else{
                    $movjaexiste++;
                    
                    //Log::info('Movimento já existe: {$movimento_Dcto->id}', ['id' => $movimento_Dcto ] );
                }
            }
        }

        if( $falha == 0 ){   
            //$categorias = Categoria::orderBy('nome', 'ASC')->get();

            // Carregar a VIEW
            //return view('categorias.index', ['categorias' => $categorias]);
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
    
    public function pie(Request $request)
    {   
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

    public function relatorioselecao(){
        
        $categorias = Categoria::orderBy('classif', 'ASC')->get();

        return view('Movimentos.relatorioselecao', 
         ['menu' => 'movimentos-relatorio',
          'categorias' => $categorias]);
    }

    public function relatoriovisualizacao(Request $request){
        //dd($request);

        DB::enableQueryLog();

        $dataDe  = $request->filled('data_de')
            ? \Carbon\Carbon::parse($request->data_de)->startOfDay()
            : \Carbon\Carbon::now()->startOfDay();

        $dataAte = $request->filled('data_ate')
            ? \Carbon\Carbon::parse($request->data_ate)->endOfDay()
            : \Carbon\Carbon::now()->endOfDay();
    
             /*
        consulta agrupada movimentos por categoria
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
        */

        $dataInicio = $dataDe;
        $dataFim    = $dataAte;

        if( $request->has('descricao') && $request->descricao ){
            $descrel = ' like ' . '%' . $request->descricao . '%';
        }
        
        if( $request->has('categoria_id') && $request->categoria_id ){
            $categrel = $request->categoria_id;
        }

        if( $request->has('categoria_id') && $request->categoria_id ){

                // Usar DB::select para executar a consulta SQL bruta
            $resultados = DB::select("
                SELECT *
                FROM (
                    SELECT 
                        tipo_lc, categorias.nome, categorias.classif,
                        SUM(movimentos.valor) as valor
                    FROM
                        movimentos
                    INNER JOIN
                        categorias ON movimentos.categoria_id = categorias.id
                    WHERE
                        tipo_lc = 'O' AND dt BETWEEN :dataInicioO AND :dataFimO
                        and categorias.id = :catrelO
                    GROUP BY
                        tipo_lc, categorias.nome, categorias.classif

                    UNION ALL

                    SELECT
                        tipo_lc, categorias.nome, categorias.classif,
                        SUM(movimentos.valor) as valor
                    FROM
                        movimentos
                    INNER JOIN
                        categorias ON movimentos.categoria_id = categorias.id
                    WHERE
                        tipo_lc = 'P' AND dt BETWEEN :dataInicioP AND :dataFimP
                        and categorias.id = :catrelP
                    GROUP BY
                        tipo_lc, categorias.nome, categorias.classif
                ) AS subquery_combinada
                ORDER BY
                    classif;
                ", [
                    // Passar os bindings para prevenir SQL Injection
                    'dataInicioO' => $dataInicio,
                    'dataFimO' => $dataFim,
                    'dataInicioP' => $dataInicio,
                    'dataFimP' => $dataFim,
                    'catrelO'  => $categrel,
                    'catrelP'  => $categrel,
                ]);
        }else{
            $resultados = DB::select("
            SELECT *
            FROM (
                SELECT 
                    tipo_lc, categorias.nome, categorias.classif,
                    SUM(movimentos.valor) as valor
                FROM
                    movimentos
                INNER JOIN
                    categorias ON movimentos.categoria_id = categorias.id
                WHERE
                    tipo_lc = 'O' AND dt BETWEEN :dataInicioO AND :dataFimO
                GROUP BY
                    tipo_lc, categorias.nome, categorias.classif

                UNION ALL

                SELECT
                    tipo_lc, categorias.nome, categorias.classif,
                    SUM(movimentos.valor) as valor
                FROM
                    movimentos
                INNER JOIN
                    categorias ON movimentos.categoria_id = categorias.id
                WHERE
                    tipo_lc = 'P' AND dt BETWEEN :dataInicioP AND :dataFimP
                GROUP BY
                    tipo_lc, categorias.nome, categorias.classif
            ) AS subquery_combinada
            ORDER BY
                classif;
            ", [
                // Passar os bindings para prevenir SQL Injection
                'dataInicioO' => $dataInicio,
                'dataFimO' => $dataFim,
                'dataInicioP' => $dataInicio,
                'dataFimP' => $dataFim,
            ]);
        }

            // order by classif, tipo_lc; 

        /*
        $movimentos = Movimento::select('movimentos.*')
        ->join('categorias', 'movimentos.categoria_id', '=', 'categorias.id')
        ->with(['categoria', 'forma_pgto'])
        ->when($request->filled('descricao'), function ($q) use ($request) {
            $q->where('descricao', 'like', '%' . $request->descricao . '%');
        })
        ->when($request->filled('categoriabusca'), function ($q) use ($request) {
            $q->where('categorias.nome', '=', $request->categoriabusca );
        })
        ->when($request->filled('tipo_filtro'), function ($q) use ($request) {
            $q->where('tipo_lc', $request->tipo_filtro);
        })
        ->whereBetween('dt', [$dataDe, $dataAte])
        ->orderBy('movimentos.dt', 'asc')
        ->orderBy('categorias.classif', 'asc')
        ->get();
        */

        $sel = "";
        if( $request->has('descricao') && $request->descricao ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Descrição:{$request->descricao}";
        }

              

        if( $request->has('categoriabusca') && $request->categoriabusca ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Categoria:{$request->categoriabusca}";
        }
        // if( $request->has('tipo_filtro') && $request->tipo_filtro ){
        //     $sel .= $sel ? ', ' : '' ;
        //     if( $request->tipo_filtro == 'O' ){
        //         $sel .= " Tipo:Obrigação";
        //     }else{
        //         $sel .= " Tipo:Previsão";
        //     }                    
        // }
        if ( $request->filled('data_de') && $request->data_de ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Data de:" . \Carbon\Carbon::parse($request->data_de)->format('d-m-Y');
        }
        if ( $request->filled('data_ate') && $request->data_ate ){
            $sel .= $sel ? ', ' : '' ;
            $sel .= " Data ate:" . \Carbon\Carbon::parse($request->data_ate)->format('d-m-Y');
        }

        //$categorias = Categoria::orderBy('classif', 'ASC')
        // teste alerta nenhum registro encontrado ->where('id', 1000)
        //->get();

        // return view('sua_view', ['dados' => $resultados]);
        // ou return response()->json($resultados);
        $dados = $resultados;

        //dd(DB::getQueryLog()); 

        return view('Movimentos.relatoriovisualizacao',
         ['menu' => 'movimentos-relatorio',
          'sel' => $sel,
         'dados' => $dados]);

    }
}
