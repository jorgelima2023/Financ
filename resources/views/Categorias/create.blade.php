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
        foreach, qual eh a colecao de erros, erros->all todos
        percorre a lista de erros, e em error imprime o erro
    --}}
    @if ($errors->any)
        <span style="color:#f00">
            @foreach ( $errors->all() as $error )
                {{ $error }} <br>
            @endforeach
        </span>
    @endif

    {{-- 
        @csrf protecao dados / metodo POST reforcar quanto ao uso metodo, 
        mesmo estando no metodo do form  
    --}}
    
    <form action="{{ route('categorias.store') }}" method="POST">
        @csrf
        @method('POST')

        <label>Nome: </label>
        <input type="text" name="nome" id="nome" 
            placeholder="Nome do categorias" value="{{ old('nome')}}" 
            ><br><br>

        <label>Classificação: </label>
        <input type="text" name="classif" id="classif" 
            placeholder="Classificação da categorias" value="{{ old('classif')}}" 
            ><br><br>

        <label>Somar: </label>
        <input type="text" name="soma" id="soma" 
            placeholder="Somar a categoria (S/N)" value="{{ old('soma')}}" 
            ><br><br>

        <button type="submit">Cadastrar</button>

    </form>

@endsection
