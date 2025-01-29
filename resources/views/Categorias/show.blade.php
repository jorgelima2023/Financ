@extends('layouts.admin')

@section('content')
    <h2>Detalhes do categorias</h2>

    <a href="{{ route('categorias.index') }}">Listar</a><br>
    <a href="{{ route('categorias.edit') }}">Editar</a><br><br>

    ID: {{ $categoria->id }}<br>
    Nome: {{ $categoria->nome }}<br>
    Cadastrado: {{ \Carbon\Carbon::parse($categoria->created_at)->format('d/m/Y H:i:s') }}<br>
    Editado: {{ \Carbon\Carbon::parse($categoria->updated_at)->format('d/m/Y H:i:s') }}<br>

@endsection
