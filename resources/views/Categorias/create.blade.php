@extends('layouts.admin')

@section('content')
    <h2>Cadastrar o Curso</h2>

    <a href="{{ route('categorias.index') }} ">
        <button type="button">Listar</button>
    </a><br><br>

    @if (session('success'))
        <p style="color: #082">
            {{ session('success') }}
        </p>
    @endif

    {{-- 
        @csrf protecao dados / metodo POST reforcar quanto ao uso metodo, 
        mesmo estando no metodo do form  
    --}}
    
    <form action="{{ route('categorias.store') }}" method="POST">
        @csrf
        @method('POST')

        <label>Nome: </label>
        <input type="text" name="nome" id="nome" placeholder="Nome do categorias" value="{{ old('nome')}}" required><br><br>

        <button type="submit">Cadastrar</button>

    </form>

@endsection
