<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    // Detalhes do perfil
    public function show()
    {
        // Recuperar do banco de dados as informações do usuário logado
        $user = User::where('id', Auth::id())->first();

        // Carregar a VIEW
        return view('profile.show', ['user' => $user]);
    }

    // Carregar o formulário editar perfil
    public function edit()
    {
        // Recuperar do banco de dados as informações do usuário logado
        $user = User::where('id', Auth::id())->first();

        // Carregar a VIEW
        return view('profile.edit', ['user' => $user]);
    }

    // Editar no banco de dados o usuário
    public function update(ProfileRequest $request)
    {

        // Validar o formulário
        $request->validated();

        // Marca o ponto inicial de uma transação
        DB::beginTransaction();

        try {
            // Recuperar do banco de dados as informações do usuário logado
            $user = User::where('id', Auth::id())->first();

            // Editar as informações do registro no banco de dados
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Salvar log
            //Log::info('Perfil editado.', ['id' => $user->id]);

            // Operação é concluída com êxito
            DB::commit();

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('profile.show', ['user' => $request->user])->with('success', 'Perfil editado com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::info('Perfil não editado.', ['error' => $e->getMessage()]);

            // Operação não é concluída com êxito
            DB::rollBack();

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Perfil não editado!');
        }
    }

    // Carregar o formulário editar senha do perfil
    public function editPassword()
    {
        // Recuperar do banco de dados as informações do usuário logado
        $user = User::where('id', Auth::id())->first();

        // Carregar a VIEW
        return view('profile.editPassword', ['user' => $user]);
    }

    // Editar no banco de dados a senha do perfil
    public function updatePassword(Request $request)
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

            // Recuperar do banco de dados as informações do usuário logado
            $user = User::where('id', Auth::id())->first();

            // Editar as informações do registro no banco de dados
            $user->update([
                'password' => $request->password,
            ]);

            // Salvar log
            //Log::info('Senha do perfil editada.', ['id' => $user->id]);

            // Operação é concluída com êxito
            DB::commit();

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('profile.show')->with('success', 'Senha do perfil editada com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::info('Senha do perfil não editada.', ['error' => $e->getMessage()]);

            // Operação não é concluída com êxito
            DB::rollBack();

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Senha do perfil não editada!');
        }
    }
}
