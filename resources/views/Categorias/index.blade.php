@extends('layouts.admin')

@section('content')
    <h2>Listar os categorias</h2>

    <a href="{{ route('categorias.show') }}">Visualizar</a><br>
    <a href="{{ route('categorias.create') }}">Cadastrar</a><br>
@endsection