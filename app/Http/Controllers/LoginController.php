<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class LoginController extends Controller
{
    //
    // Login
    public function index()
    {
        // Carregar a VIEW
        return view('login.index');
    }

    // Validar os dados do usuário no login
    public function loginProcess(LoginRequest $request)
    {

        //dd($request);

        // Validar o formulário
        $request->validated();

        // Validar o usuário e a senha com as informações do banco de dados
        $authenticated = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        // Verificar se o usuário foi autenticado
        if(!$authenticated){
            
            // Redirecionar o usuário para página anterior "login", enviar a mensagem de erro
            return back()->withInput()->with('error', 'E-mail ou senha inválido!');
        }

        // Obter o usuário autenticado
        $user = Auth::user();
        $user = User::find($user->id);

        // Verificar se a permissões é Super Admin, tem acesso a todas as páginas
        if($user->hasRole('Super Admin')){

            // O usuário tem todas as permissões
            // recuperar permissoes em um array
            $permissions = Permission::pluck('name')->toArray();
        }else{

            // Recuperar no banco de dados as permissões que o papel possui
            // recuperar permissoes em um array
            $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        }

        // Atribuir as permissões ao usuário
        // o metodo recebe o array de permissoes
        $user->syncPermissions($permissions);

        // Redirecionar o usuário
        return redirect()->route('dashboard.index');
    }

    // Deslogar o usuário
    public function destroy()
    {

        // Deslogar o usuário
        Auth::logout();

        // Redirecionar o usuário, enviar a mensagem de sucesso
        return redirect()->route('login.index')->with('success', 'Deslogado com sucesso!');

    }
}
