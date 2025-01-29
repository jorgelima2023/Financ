@extends('layouts.admin')

@section('content')
    <h2>Listar os categorias</h2>

    <a href="{{ route('categorias.show') }}">Visualizar</a><br>
    <a href="{{ route('categorias.create') }}">Cadastrar</a><br>

    {{-- Imprimir os registros --}}
    @forelse ($categorias as $categoria)
        {{ $categoria->id }}<br>
        {{ $categoria->nome }}<br>
        {{ \Carbon\Carbon::parse($categoria->created_at)->format('d/m/Y H:i:s') }}<br>
        {{ \Carbon\Carbon::parse($categoria->updated_at)->format('d/m/Y H:i:s') }}<br>
        <hr>

    @empty
        <p style="color: #f00">Nenhum categoria encontrado!</p>
    @endforelse
    
    {{-- Imprimir a paginação --}}
    {{-- {{ $courses->links() }} --}}
@endsection