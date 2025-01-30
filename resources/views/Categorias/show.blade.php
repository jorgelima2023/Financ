@extends('layouts.admin')

@section('content')
    <h2>Detalhes do categorias</h2>

    @if (session('success'))
        <p style="color: #082">
            {{ session('success') }}
        </p>
    @endif
    
    <a href="{{ route('categorias.index') }}">Listar</a><br>
    <a href="{{ route('categorias.edit', ['categoria' => $categoria->id ] ) }}">Editar</a><br><br>

    ID: {{ $categoria->id }}<br>
    Nome: {{ $categoria->nome }}<br>
    Cadastrado: {{ \Carbon\Carbon::parse($categoria->created_at)->format('d/m/Y H:i:s') }}<br>
    Editado: {{ \Carbon\Carbon::parse($categoria->updated_at)->format('d/m/Y H:i:s') }}<br>

@endsection
