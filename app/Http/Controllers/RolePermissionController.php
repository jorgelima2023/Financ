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
}
