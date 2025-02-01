@extends('layouts.admin')

@section('content')
    <h2>Listar os categorias</h2>

    <br>
    <a href="{{ route('categorias.create') }}">
    <button type="button">Cadastrar</button>
    </a><br>

    @if (session('success'))
        <p style="color: #082">
            {{ session('success') }}
        </p>
    @endif
    
    {{-- Imprimir os registros --}}
    @forelse ($categorias as $categoria)
        id: {{ $categoria->id }}<br>
        Nome: {{ $categoria->nome }}<br>
        classif: {{ $categoria->classif }}<br>
        Somar: {{ $categoria->soma }}<br>
        Criado: {{ \Carbon\Carbon::parse($categoria->created_at)->format('d/m/Y H:i:s') }}<br>
        Editado: {{ \Carbon\Carbon::parse($categoria->updated_at)->format('d/m/Y H:i:s') }}<br>

        <a href="{{ route('categorias.show', ['categoria' => $categoria->id ] ) }}">
            <button type="button">Visualizar</button>
        </a>
        <a href="{{ route('categorias.edit', ['categoria' => $categoria->id ] ) }}">
            <button type="button">Editar</button>
        </a>

        <form action="{{ route('categorias.destroy', ['categoria' => $categoria->id ] ) }}" method="POST">
            @csrf
            @method('delete')
            <button type="submit" onclick="return confirm('Confirma apagar esse registro {{ $categoria->id }} ?')">Apagar</button>
        </form>

        <hr>

    @empty
        <p style="color: #f00">Nenhum categoria encontrado!</p>
    @endforelse
    
    {{-- Imprimir a paginação --}}
    {{-- {{ $courses->links() }} --}}
@endsection