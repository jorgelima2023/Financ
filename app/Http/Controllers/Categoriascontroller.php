<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class Categoriascontroller extends Controller
{
    // Listar os cursos
    public function index(){
        // Recuperar os registros do banco dados
        // $courses = Course::where('id', 1000)->get();
        // $courses = Course::paginate(10);
        $categorias = Categoria::orderBy('nome', 'ASC')->get();

        // Carregar a VIEW
        return view('categorias.index', ['categorias' => $categorias]);
    }

    // Visualizar o curso
    public function show(Categoria $categoria){
        // Carregar a VIEW
        return view('categorias.show', ['categoria' => $categoria] );
    }

    // Carregar o formulário cadastrar novo curso
    public function create(){
        return view('categorias.create');  // Carregar a VIEW
    }
    // Cadastrar no banco de dados o novo curso
    public function store(Request $request){
        // Cadastrar no banco de dados na tabela categorias os valores de todos os campos
        // dd($request->name);
        Categoria::create([
            'nome' => $request->nome , 
            'classif' => '01' /*$request->classif*/ , 
            'soma' => 'N' /*$request->soma*/ , 
        ]);
        
        // Redirecionar o usuário, enviar a mensagem de sucesso
        return redirect()->route('categorias.create')->with('success', 'categoria cadastrado com sucesso!');
    }
    // Carregar o formulário editar curso
    public function edit(){
        return view('categorias.edit');  // Carregar a VIEW
    }
    // Editar no banco de dados o categorias
    public function update(){
        dd("Editar no banco de dados o categorias");
    }
    // Excluir o categorias do banco de dados
    public function destroy(){

        dd("Excluir o categorias do banco de dados");
        
    }
}
