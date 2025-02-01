@extends('layouts.admin')

@section('content')
    <h2>Detalhes do categorias</h2>

        
    <a href="{{ route('categorias.index') }}">
        <button type="button">Listar</button>
    </a><br>

    <a href="{{ route('categorias.edit', ['categoria' => $categoria->id ] ) }}">
        <button type="button">Editar</button>
    </a>
    <br><br>

    <form action="{{ route('categorias.destroy', ['categoria' => $categoria->id ] ) }}" method="POST">
        @csrf
        @method('delete')
        <button type="submit" onclick="return confirm('Confirma apagar esse registro {{ $categoria->id }} ?')">Apagar</button>
    </form>

    <x-alert />
    
    ID: {{ $categoria->id }}<br>
    Nome: {{ $categoria->nome }}<br>
    Cadastrado: {{ \Carbon\Carbon::parse($categoria->created_at)->format('d/m/Y H:i:s') }}<br>
    Editado: {{ \Carbon\Carbon::parse($categoria->updated_at)->format('d/m/Y H:i:s') }}<br>

@endsection
