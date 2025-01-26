@extends('layouts.admin')

@section('content')
    <h2>Detalhes do categorias</h2>

    <a href="{{ route('categorias.index') }}">Listar</a><br>
    <a href="{{ route('categorias.edit') }}">Editar</a><br>
@endsection
