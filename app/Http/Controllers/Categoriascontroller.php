<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;

class Categoriascontroller extends Controller
{
    // Listar os cursos
    public function index(){
        // Recuperar os registros do banco dados
        // $courses = Course::where('id', 1000)->get();
        // $courses = Course::paginate(10);
        $categorias = Categoria::orderBy('nome', 'ASC')
        // teste alerta nenhum registro encontrado ->where('id', 1000)
        ->get();

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
    public function store(CategoriaRequest $request){

        // validar o fomulario
        $validated = $request->validated();

        // Cadastrar no banco de dados na tabela categorias os valores de todos os campos
        // dd($request->name);
        $categoria = Categoria::create([
            'nome' => $request->nome , 
            'classif' => '01' /*$request->classif*/ , 
            'soma' => 'N' /*$request->soma*/ , 
        ]);
        
        // Redirecionar o usuário, enviar a mensagem de sucesso
        // 'course->id' veio da funcao create , da model, que cadastrou o curso
        // e na variavel tem o id novo
        return redirect()->route('categorias.show', ['categoria' => $categoria->id ] )->with('success', 'categoria cadastrado com sucesso!');
    }
    // Carregar o formulário editar categorias
    // a model 'Categoria' recebe o id na variavel '$categoria'
    public function edit(Categoria $categoria){
        // dd($categoria);
        return view('categorias.edit', ['categoria' => $categoria]);  // Carregar a VIEW
    }

    // Editar no banco de dados o categorias
    // receber os dados formulario via 'request'
    // e na model 'Categoria' receber o id do categoria
    public function update(CategoriaRequest $request, Categoria $categoria){

        // validar o fomulario
        $validated = $request->validated();

        // editar as informacoes do banco de dados
        $categoria->update([
            'nome' => $request->nome
        ]);

        // dd("Editar no banco de dados o categorias");

        // redirecionar para o form show
        // na sessao 'success' e a msg 'Curso editado com sucesso'
        return redirect()->route('categorias.show', ['categoria' => $categoria->id ])
            ->with('success', 'categoria editado com sucesso');
    }
    // Excluir o categorias do banco de dados
    public function destroy(Categoria $categoria){

        // dd("Excluir o categorias do banco de dados");

        // excluir o registro do banco de dados, no metodo 'delete' pode adiconar condicao
        $categoria->delete();
        // redirecionar o usuario, enviar msg de sucesso
        return redirect()->route('categorias.index')->with('success', 'categoria apagado com sucesso');
    }
}
