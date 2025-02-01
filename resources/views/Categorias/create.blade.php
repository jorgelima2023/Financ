@extends('layouts.admin')

@section('content')
    <h2>Cadastrar a categoria</h2>

    <a href="{{ route('categorias.index') }} ">
        <button type="button">Listar</button>
    </a><br><br>

    <x-alert />

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
