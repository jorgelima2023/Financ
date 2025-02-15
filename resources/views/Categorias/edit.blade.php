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
                <span>Editar</span>

                <span class="ms-auto d-sm-flex flex-row">

                    <a href="{{ route('categorias.index') }}" class="btn btn-info btn-sm me-1 mb-1 mb-sm-0"> <i class="fa-solid fa-list"></i> Listar</a>

                    <a href="{{ route('categorias.show', ['categoria' => $categoria->id ] ) }}"  class="btn btn-primary btn-sm me-1 mb-1 mb-sm-0"> <i class="fa-regular fa-eye"></i> Visualizar</a>

                </span>
            </div>

            <div class="card-body">

                <x-alert />

                <form class="row g-3" action="{{ route('categorias.update', ['categoria' => $categoria->id ] ) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="col-12">
                      <label for="nome" class="form-label">Nome</label>
                      <input type="text" name="nome" class="form-control" id="nome" placeholder="Nome da categoria" value="{{ old('nome', $categoria->nome) }}" required>
                    </div>

                    <div class="col-12">
                      <label for="classif" class="form-label">classificação</label>
                      <input type="text" name="classif" class="form-control" id="classif" placeholder="classificação da categoria" value="{{ old('classif', $categoria->classif) }}" required>
                    </div>

                    <div class="col-12">
                      <label for="soma" class="form-label">Soma categoria</label>
                      <input type="text" name="soma" class="form-control" id="soma" placeholder="classificação da categoria" value="{{ old('soma', $categoria->soma) }}" required>
                    </div>

                    <div class="col-12">
                      <button type="submit" class="btn btn-warning btn-sm">Editar</button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
