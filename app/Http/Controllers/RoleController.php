<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
