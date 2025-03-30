<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // Listar os papéis
    public function index()
    {
        
        // Recuperar os registros do banco dados
        $roles = Role::orderBy('name')->paginate(40);

        // Salvar log
        //Log::info('Listar papéis', ['action_user_id' => Auth::id()]);

        // Carregar a VIEW
        return view('roles.index', ['menu' => 'roles', 'roles' => $roles]);

    }

    // Carregar o formulário cadastrar novo papel
    public function create()
    {

        // Salvar log
        //Log::info('Carregar formulário cadastrar papel.', ['action_user_id' => Auth::id()]);

        // Carregar a VIEW
        return view('roles.create', [
            'menu' => 'roles',
        ]);
    }

    // Cadastrar no banco de dados o novo papel
    public function store(RoleRequest $request)
    {

        // Validar o formulário
        $request->validated();

        // Marca o ponto inicial de uma transação
        DB::beginTransaction();

        try {

            // Cadastrar no banco de dados
            $role = Role::create([
                'name' => $request->name,
            ]);

            // Salvar log
            //Log::info('Papel cadastrado.', ['id' => $role->id, 'action_user_id' => Auth::id()]);

            // Operação é concluída com êxito
            DB::commit();

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('role.index')->with('success', 'Papel cadastrado com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            //Log::warning('Papel não cadastrado.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Operação não concluída com êxito
            DB::rollBack();

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Papel não cadastrado!');
        }
    }

    // Carregar o formulário editar papel
    public function edit(Role $role)
    {

        // Salvar log
        //Log::info('Carregar formulário editar papel.', ['id' => $role->id, 'action_user_id' => Auth::id()]);

        // Carregar a VIEW
        return view('roles.edit', [
            'menu' => 'roles',
            'role' => $role,
        ]);
    }

    // Editar no banco de dados o usuário
    public function update(RoleRequest $request, Role $role)
    {

        // Validar o formulário
        $request->validated();

        // Marca o ponto inicial de uma transação
        DB::beginTransaction();

        try {

            // Editar as informações do registro no banco de dados
            $role->update([
                'name' => $request->name,
            ]);

            // Salvar log
            //Log::info('Papel editado.', ['id' => $role->id, 'action_user_id' => Auth::id()]);

            // Operação é concluída com êxito
            DB::commit();

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('role.index')->with('success', 'Papel editado com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::warning('Papel não editado.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Operação não é concluída com êxito
            DB::rollBack();

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Papel não editado!');
        }
    }

    // Excluir o papel do banco de dados
    public function destroy(Role $role)
    {
        if ($role->name == 'Super Admin') {
            // Salvar log
            //Log::warning('Papel super admin não pode ser excluído.', ['papel_id' => $role->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return redirect()->route('role.index')->with('error', 'Papel super admin não pode ser excluído!');
        }

        // Não permitir excluir papel quando tem algum usuário utilizando o papel
        if ($role->users->isNotEmpty()) {
            // Salvar log
            //Log::warning('Papel não pode ser excluído porque há usuários associados.', ['papel_id' => $role->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return redirect()->route('role.index')->with('error', 'Não é possível excluir o papel porque há usuários associados a ele.');
        }

        try {
            // Excluir o registro do banco de dados
            $role->delete();

            // Salvar log
            //Log::info('Papel excluído.', ['id' => $role->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('role.index')->with('success', 'Papel excluído com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::warning('Papel não excluído.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return redirect()->route('role.index')->with('error', 'Papel não excluído!');
        }
    }

}
