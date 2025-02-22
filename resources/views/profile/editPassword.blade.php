@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="mb-1 hstack gap-2">
            <h2 class="mt-3">Perfil</h2>
            <ol class="breadcrumb mb-3 mt-3 ms-auto">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Perfil</li>
            </ol>
        </div>

        <div class="card mb-4 border-light shadow">
            <div class="card-header hstack gap-2">
                <span>Editar Senha</span>
                <span class="ms-auto d-sm-flex flex-row">

                    <a href="{{ route('profile.show') }}" class="btn btn-primary btn-sm me-1"><i
                            class="fa-regular fa-eye"></i> Visualizar
                    </a>

                </span>
            </div>
            <div class="card-body">

                <x-alert />

                <form action="{{ route('profile.update-password') }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-12">
                        <label for="password" class="form-label">Senha: </label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Senha com no mínimo 6 caracteres"
                            value="{{ old('password') }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-warning btn-sm">Salvar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
