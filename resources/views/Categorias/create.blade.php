@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="mb-1 hstack gap-2">
            <h2 class="mt-3">Categoria</h2>

            <ol class="breadcrumb mb-3 mt-3 ms-auto">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('categorias.index') }}" class="text-decoration-none">Categorias</a>
                </li>
                <li class="breadcrumb-item active">Categoria</li>
            </ol>
        </div>

        <div class="card mb-4">

            <div class="card-header hstack gap-2">
                <span>Cadastrar</span>

                <span class="ms-auto d-sm-flex flex-row">

                    <a href="{{ route('categorias.index') }}" class="btn btn-info btn-sm me-1 mb-1 mb-sm-0"> <i class="fa-solid fa-list"></i> Listar</a>

                </span>
            </div>

            <div class="card-body">

                <x-alert />

                <form class="row g-3" action="{{ route('categorias.store') }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="col-12">
                      <label for="nome" class="form-label">Nome</label>
                      <input type="text" name="nome" class="form-control" id="nome" placeholder="Nome da categoria" value="{{ old('nome') }}" required>
                    </div>

                    <div class="col-12">
                      <label for="classif" class="form-label">Classificação</label>
                      <input type="text" name="classif" class="form-control" id="classif" placeholder="Classificação da categoria" value="{{ old('classif') }}" required>
                    </div>
                    
                    <div class="col-12">
                      <label for="soma" class="form-label">Somar</label>
                      <input type="text" name="soma" class="form-control" id="soma" placeholder="Somar a categoria (S/N)" value="{{ old('soma') }}" required>
                    </div>

                    <div class="col-12">
                      <button type="submit" class="btn btn-success btn-sm">Cadastrar</button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
