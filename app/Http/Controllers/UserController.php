<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        // Recuperar os registros do banco dados
        // $users = User::orderByDesc('created_at')->paginate(10);

        // when pesquisa pelo nome
        // function user(request) tudo que veio no request
        // when($request->has('name') vai executar se tiver valor passado get http 

        // withQueryString por causa da paginacao...

        $users = User::when($request->has('name'), function($whenQuery) use($request){
            $whenQuery->where('name', 'like' , '%' . $request->name . '%' );
        } )
        ->when($request->has('email'), function($whenQuery) use($request){
            $whenQuery->where('email', 'like' , '%' . $request->email . '%' );
        } )
        ->when($request->filled('start_date_registration'), function($whenQuery) use($request){
            $whenQuery->where('created_at', '>=' , \Carbon\Carbon::parse($request->start_date_registration)->format('Y-m-d H:i:s') );
        } )
        ->when($request->filled('end_date_registration'), function($whenQuery) use($request){
            $whenQuery->where('created_at', '<=' , \Carbon\Carbon::parse($request->end_date_registration)->format('Y-m-d H:i:s') );
        } )
        ->orderByDesc('created_at')
        ->paginate(1000)
        ->withQueryString()
        ;

        // Carregar a VIEW
        // return view('users.index', ['menu' => 'users', 'users' => $users]);
        return view('users.index',
         ['menu' => 'users',
          'users' => $users,
          'name' => $request->name,
          'email' => $request->email,
          'start_date_registration' => $request->start_date_registration,
          'end_date_registration' => $request->end_date_registration
         ]);
    }

    // Detalhes do usuario
    public function show(User $user)
    {
        // Carregar a VIEW
        return view('users.show', ['menu' => 'users', 'user' => $user]);
    }

    // Carregar o formulário cadastrar novo usuário
    public function create()
    {
        // Recuperar todos os papéis
        $roles = Role::pluck('name')->all();

        // Carregar a VIEW
        return view('users.create', ['menu' => 'users', 'roles' => $roles ]);
        // Carregar a VIEW
        //return view('users.create', ['menu' => 'users']);
    }

    // Cadastrar no banco de dados o novo curso
    public function store(UserRequest $request)
    {

        // Validar o formulário
        $request->validated();

        // Marca o ponto inicial de uma transação
        DB::beginTransaction();

        try {

            // Cadastrar no banco de dados na tabela usuários
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Cadastrar papel para o usuário
            $user->assignRole($request->roles);

            // Salvar log
            //Log::info('Usuário cadastrado.', ['id' => $user->id, $user]);

            // Operação é concluída com êxito
            DB::commit();

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('user.show', ['user' => $user->id])->with('success', 'Usuário cadastrado com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::info('Usuário não cadastrado.', ['error' => $e->getMessage()]);

            // Operação não é concluída com êxito
            DB::rollBack();

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Usuário não cadastrado!');
        }
    }

    // Carregar o formulário editar usuário
    public function edit(User $user)
    {

        // Recuperar os papéis
        $roles = Role::pluck('name')->all();

        // Recuperar o papel do usuário
        $userRoles = $user->roles->pluck('name')->first();

        // Carregar a VIEW
        // return view('users.edit', ['menu' => 'users', 'user' => $user]);
        return view('users.edit', ['menu' => 'users', 'user' => $user, 'roles' => $roles, 'userRoles' => $userRoles]);

    }

    // Editar no banco de dados o usuário
    public function update(UserRequest $request, User $user)
    {

        // Validar o formulário
        $request->validated();

        // Marca o ponto inicial de uma transação
        DB::beginTransaction();

        try {

            // Editar as informações do registro no banco de dados
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Editar papel para o usuário
            $user->syncRoles($request->roles);

            // Salvar log
            //Log::info('Usuário editado.', ['id' => $user->id]);

            // Operação é concluída com êxito
            DB::commit();

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('user.show', ['user' => $request->user])->with('success', 'Usuário editado com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::info('Usuário não editado.', ['error' => $e->getMessage()]);

            // Operação não é concluída com êxito
            DB::rollBack();

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Usuário não editado!');
        }
    }

    // Carregar o formulário editar senha do usuário
    public function editPassword(User $user)
    {

        // Carregar a VIEW
        return view('users.editPassword', ['menu' => 'users', 'user' => $user]);
    }

    // Editar no banco de dados a senha do usuário
    public function updatePassword(Request $request, User $user)
    {

        // Validar o formulário
        $request->validate([
            'password' => 'required|min:6',
        ], [
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
        ]);

        // Marca o ponto inicial de uma transação
        DB::beginTransaction();

        try {

            // Editar as informações do registro no banco de dados
            $user->update([
                'password' => $request->password,
            ]);

            // Salvar log
            //Log::info('Senha do usuário editada.', ['id' => $user->id]);

            // Operação é concluída com êxito
            DB::commit();

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('user.show', ['user' => $request->user])->with('success', 'Senha do usuário editada com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::info('Senha do usuário não editada.', ['error' => $e->getMessage()]);

            // Operação não é concluída com êxito
            DB::rollBack();

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Senha do usuário não editada!');
        }
    }

    // Excluir o usuário do banco de dados
    public function destroy(User $user)
    {
        try {
            // Excluir o registro do banco de dados
            $user->delete();

            // Remove todos os papéis atribuídos ao usuário
            $user->syncRoles([]);
            
            // Salvar log
            //Log::info('Usuário excluído.', ['id' => $user->id]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('user.index')->with('success', 'Usuário excluído com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::info('Usuário não excluído.', ['error' => $e->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return redirect()->route('course.index')->with('error', 'Usuário não excluído!');
        }
    }

    /*   listar todos os usuarios , chamada pelo rota
    public function generatePdf()
    {
        // recuperar os registros do banco de dados
        $users = User::orderByDesc('id')->get();
        // $users = User::where('id', 100)->orderByDesc('id')->get();
        // dd($user);

        // carregar a string com o HTML/conteudo e determinar a orientacao e o tamanho do arquivo
        $pdf = PDF::loadView('users.generatePdf', ['users' => $users ] )->setPaper('a4', 'portrait');

        // fazer o download do arquivo
        return $pdf->download('list_users.pdf');

    }
    */
    
    public function generatePdf(Request $request)
    {
        // dd($request);

        // recuperar os registros do banco de dados
        $users = User::when($request->has('name'), function($whenQuery) use($request){
            $whenQuery->where('name', 'like' , '%' . $request->name . '%' );
        } )
        ->when($request->has('email'), function($whenQuery) use($request){
            $whenQuery->where('email', 'like' , '%' . $request->email . '%' );
        } )
        ->when($request->filled('start_date_registration'), function($whenQuery) use($request){
            $whenQuery->where('created_at', '>=' , \Carbon\Carbon::parse($request->start_date_registration)->format('Y-m-d H:i:s') );
        } )
        ->when($request->filled('end_date_registration'), function($whenQuery) use($request){
            $whenQuery->where('created_at', '<=' , \Carbon\Carbon::parse($request->end_date_registration)->format('Y-m-d H:i:s') );
        } )
        ->orderByDesc('created_at')
        ->get();

        // somar total registros
        $totalRecords = $users->count('id');

        // verificar se a quantidade de registros ultrapassa o limite para gerar o pdf
        // qtde registros, voce define.
        $totalRecordsAllowed = 500;

        if($totalRecords > $totalRecordsAllowed){
            return  redirect()->route('user.index',
            ['name' => $request->name,
            'email' => $request->email,
            'start_date_registration' => $request->start_date_registration,
            'end_date_registration' => $request->end_date_registration
            ])->with('error', "Limite de registros ultrapassado para gerar o pdf. o limtie e de $totalRecordsAllowed registros! ");
        }

        // carregar a string com o HTML/conteudo e determinar a orientacao e o tamanho do arquivo
        $pdf = PDF::loadView('users.generatePdf', ['users' => $users ] )->setPaper('a4', 'portrait');

        // fazer o download do arquivo
        return $pdf->download('list_users.pdf');

    }

    public function generateCsv(Request $request)
    {
        // dd($request->name);
        // recuperar os registros do banco de dados
        $users = User::when($request->has('name'), function($whenQuery) use($request){
            $whenQuery->where('name', 'like' , '%' . $request->name . '%' );
        } )
        ->when($request->has('email'), function($whenQuery) use($request){
            $whenQuery->where('email', 'like' , '%' . $request->email . '%' );
        } )
        ->when($request->filled('start_date_registration'), function($whenQuery) use($request){
            $whenQuery->where('created_at', '>=' , \Carbon\Carbon::parse($request->start_date_registration)->format('Y-m-d H:i:s') );
        } )
        ->when($request->filled('end_date_registration'), function($whenQuery) use($request){
            $whenQuery->where('created_at', '<=' , \Carbon\Carbon::parse($request->end_date_registration)->format('Y-m-d H:i:s') );
        } )
        ->orderByDesc('created_at')
        ->get();

        // somar total registros
        $totalRecords = $users->count('id');

        // verificar se a quantidade de registros ultrapassa o limite para gerar o csv
        // qtde registros, voce define.
        $totalRecordsAllowed = 500;

        if($totalRecords > $totalRecordsAllowed){
            return  redirect()->route('user.index',
            ['name' => $request->name,
            'email' => $request->email,
            'start_date_registration' => $request->start_date_registration,
            'end_date_registration' => $request->end_date_registration
            ])->with('error', "Limite de registros ultrapassado para gerar a planilha. o limtie e de $totalRecordsAllowed registros! ");
        }

        // criar arq temporario
        $csvFileName = tempnam(sys_get_temp_dir(), 'csv_' . Str::ulid());

        // abrir arq forma scrita
        $openFile = fopen($csvFileName, 'w');

        // criar o cabecalho do excel
        // id minusculo caso contratio nao funciona
        $header = ['id', 'Nome', 'e_mail', 'Cadastro'];

        // escrever o cabecalho no arquivo
        fputcsv($openFile, $header, ';');

        // ler os registros recuperados do banco de dados
        foreach($users as $user){
            // criar o array com os dados da linha do excel
            $userArray = [
                'id' => $user->id,
                'nome' => mb_convert_encoding($user->name, 'ISO-8859-1', 'UTF-8'),
                'email' => $user->email,
                'created_at' => \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i:s'),
            ];

            // escrever conteudo arquivo
            fputcsv($openFile, $userArray, ';');            
        }

        // fechar o arquivo
        fclose($openFile);

        // realizar o download do arquivo
        // funcao ulid gera numero aleatorio
        return response()->download($csvFileName, 'list_users_' . str::ulid() . '.csv' );

    }
}
