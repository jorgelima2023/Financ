@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="mb-1 hstack gap-2">
            <h2 class="mt-3">Categoria</h2>

            <ol class="breadcrumb mb-3 mt-3 ms-auto">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Categoria</li> <!-- Curso -->
            </ol>
        </div>

        <div class="card mb-3">

            <div class="card-header hstack gap-2">
                <span>Visualizar</span>

                <span class="ms-auto d-sm-flex flex-row">

                    <a href="{{ route('categorias.index') }}" class="btn btn-info btn-sm me-1 mb-1 mb-sm-0">Listar</a>

                    <a href="{{ route('categorias.edit', ['categoria' => $categoria->id ] ) }}" class="btn btn-warning btn-sm me-1 mb-1 mb-sm-0">Editar</a>

                    <form action="{{ route('categorias.destroy', ['categoria' => $categoria->id ] ) }}" method="POST">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirma apagar esse registro {{ $categoria->id }} ?')">Apagar</button>
                    </form>

                </span>
            </div>

            <div class="card-body">

                <x-alert />

                <dl class="row">
                    <dt class="col-sm-3">ID: </dt>
                    <dd class="col-sm-9">{{ $categoria->id }}</dd>

                    <dt class="col-sm-3">Nome: </dt>
                    <dd class="col-sm-9">{{ $categoria->nome }}</dd>

                    <dt class="col-sm-3">Classificação: </dt>
                    <dd class="col-sm-9">{{ $categoria->classif }}</dd>

                    <dt class="col-sm-3">Somar: </dt>
                    <dd class="col-sm-9">{{ $categoria->soma }}</dd>

                    <dt class="col-sm-3">Cadastrado: </dt>
                    <dd class="col-sm-9">{{ \Carbon\Carbon::parse($categoria->created_at)->format('d/m/Y H:i:s') }}</dd>

                    <dt class="col-sm-3">Editado: </dt>
                    <dd class="col-sm-9">{{ \Carbon\Carbon::parse($categoria->updated_at)->format('d/m/Y H:i:s') }}</dd>

                </dl>

            </div>
        </div>

    </div>
@endsection
