<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
