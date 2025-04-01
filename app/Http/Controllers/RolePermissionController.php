<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    // Listar as permissões do papel
    public function index(Role $role)
    {
        // Verificar se o papel é super admin, não permitir visualizar as permissões
        if($role->name == 'Super Admin'){

            // Salvar log
            //Log::info('Permissões do super admin não pode ser acessada.', ['action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return redirect()->route('role.index')->with('error', 'Permissão do super admin não pode ser acessada!');
        }

        // Recuperar as permissões do papel
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->pluck('permission_id')
            ->all();

        // Recuperar as permissões
        $permissions = Permission::get();

        // Salvar log
        //Log::info('Listar permissões do papel.', ['role_id' => $role->id, 'action_user_id' => Auth::id()]);

        // Carregar a VIEW
        return view('rolePermission.index', [
            'menu' => 'roles',
            'rolePermissions' => $rolePermissions,
            'permissions' => $permissions,
            'role' => $role,
        ]);
    }

    // Editar no banco de dados a página
    //public function update(Request $request, Role $role)
    public function update(Role $role, Request $request)
    {
        // dd($request->permission);
        // http://127.0.0.1:8000/update-role-permission/4/2
        // '2'

        // "id" => 2
        // "title" => "Visualizar curso"
        // "name" => "show-course"
        // "guard_name" => "web"
        
        // dd($role);
        // role id 4
        // role nome 'Tutor'

        // obter permissao especifica com base id fornececido
        //$permission = Permission::find(2);
        $permission = Permission::find($request->permission);

        //dd($permission);

        if(!$permission){

            Log::info('Permissões nao encontrada.', 
                [ 'role' => $role->id , 'permission' => $request->permission , 
                    'action_user_id' => Auth::id()]);

            return redirect()->route('role-permission.update', 
                ['role' => $role->id , 'permission' => $request->permission ])
                ->with('error', 'Permissões nao encontrada!');

        }

        //verificar se a permissao esta associada ao papel
        if($role->permissions->contains($permission)){

            // exemplo laravel permission
            //$role->revokePermissionTo('edit articles');

            // remover a permissao do papel (bloquear)
            $role->revokePermissionTo($permission);

            return redirect()->route('role-permission.index', 
                ['role' => $role->id ])
                ->with('success', 'Permissao bloqueada com sucesso!');

        }else{
            // adicionar a permissao ao papel (liberar)
            $role->givePermissionTo($permission);

            return redirect()->route('role-permission.index', 
                ['role' => $role->id ])
                ->with('success', 'Permissao liberada com sucesso!');
        }

    }
}
