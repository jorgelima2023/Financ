@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="mb-1 hstack gap-2">
            <h2 class="mt-3">Papel</h2>

            <ol class="breadcrumb mb-3 mt-3 ms-auto">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('role.index') }}" class="text-decoration-none">Papéis</a>
                </li>
                <li class="breadcrumb-item active">Papel</li>
            </ol>
        </div>

        <div class="card mb-4 border-light shadow">

            <div class="card-header hstack gap-2">
                <span>Cadastrar</span>

                <span class="ms-auto d-sm-flex flex-row">
                    @can('index-classe')
                        <a href="{{ route('role.index') }}" class="btn btn-info btn-sm me-1"><i class="fa-solid fa-list"></i>
                            Listar</a>
                    @endcan
                </span>
            </div>

            <div class="card-body">

                <x-alert />

                <form action="{{ route('role.store') }}" method="POST" class="row g-3">
                    @csrf
                    @method('POST')

                    <div class="col-12">
                        <label for="name" class="form-label">Nome: </label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nome do papel"
                            value="{{ old('name') }}">
                    </div>

                    <div class="col-12">
                        <button type="submit"class="btn btn-success btn-sm me-1">Cadastrar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
