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

                <span>Visualizar</span>

                <span class="ms-auto d-sm-flex flex-row">

                    <a href="{{ route('profile.edit') }}" class="btn btn-warning btn-sm me-1"><i
                            class="fa-solid fa-pen-to-square"></i> Editar
                    </a>

                    <a href="{{ route('profile.edit-password') }}" class="btn btn-warning btn-sm me-1"><i
                            class="fa-solid fa-pen-to-square"></i> Editar Senha
                    </a>

                </span>
            </div>
            <div class="card-body">

                <x-alert />

                <dl class="row">

                    <dt class="col-sm-3">ID: </dt>
                    <dd class="col-sm-9">{{ $user->id }}</dd>

                    <dt class="col-sm-3">Nome: </dt>
                    <dd class="col-sm-9">{{ $user->name }}</dd>

                    <dt class="col-sm-3">E-mail: </dt>
                    <dd class="col-sm-9">{{ $user->email }}</dd>

                </dl>
            </div>
        </div>
    </div>
@endsection
