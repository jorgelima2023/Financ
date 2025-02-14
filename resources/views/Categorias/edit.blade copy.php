@extends('layouts.admin')

@section('content')
    <h2>Editar o categorias</h2>    

    <a href="{{ route('categorias.index') }}">
        <button type="button">Listar</button>
    </a><br>

    <x-alert />
    
    {{-- dd($categoria) --}}

    <form action="{{ route('categorias.update', ['categoria' => $categoria->id ] ) }}" method="POST">
        @csrf
        @method('PUT')
        
        <label for="">id:{{ $categoria->id }}</label> <br>

        <label for="">Nome:</label>
        <input type="text" name="nome" id="nome" 
            placeholder="Nome da categoria" 
             value="{{ old('nome', $categoria->nome) }}"><br>
        
        <label for="">classificação:</label>
        <input type="text" name="classif" id="classif" 
            placeholder="classificação da categoria" 
             value="{{ old('classif', $categoria->classif) }}"><br>

        <label for="">Soma categoria:</label>
        <input type="text" name="soma" id="soma" 
            placeholder="soma a categoria (S/N)" 
             value="{{ old('soma', $categoria->soma) }}"><br>

        <br><br>

        <button type="submit">Salvar</button>
    </form>

@endsection
