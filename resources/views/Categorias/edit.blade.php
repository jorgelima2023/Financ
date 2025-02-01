@extends('layouts.admin')

@section('content')
    <h2>Editar o categorias</h2>    

    <a href="{{ route('categorias.index') }}">
        <button type="button">Listar</button>
    </a><br>

    {{-- dd($categoria) --}}

    <form action="{{ route('categorias.update', ['categoria' => $categoria->id ] ) }}" method="POST">
        @csrf
        @method('PUT')
        
        <label for="">id:{{ $categoria->id }}</label> <br>
        <label for="">Nome:</label>
        <input type="text" name="nome" id="nome" 
            placeholder="Nome da categoria" required value="{{ old('nome', $categoria->nome) }}">
            
        <br><br>

        <button type="submit">Salvar</button>
    </form>

@endsection
