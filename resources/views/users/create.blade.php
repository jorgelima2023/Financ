@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="mb-1 hstack gap-2">
            <h2 class="mt-3">Usuário</h2>
            <ol class="breadcrumb mb-3 mt-3 ms-auto">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a class="text-decoration-none" href="{{ route('user.index') }}">Usuários</a></li>
                <li class="breadcrumb-item active">Usuário</li>
            </ol>
        </div>

        <div class="card mb-4 border-light shadow">
            <div class="card-header hstack gap-2">
                <span>Cadastrar</span>
                <span class="ms-auto d-sm-flex flex-row">
                    @can('index-user')
                        <a href="{{ route('user.index') }}" class="btn btn-info btn-sm me-1"><i class="fa-solid fa-list"></i>
                        Listar</a>
                    @endcan

                </span>
            </div>
            <div class="card-body">

                <x-alert />

                <form action="{{ route('user.store') }}" method="POST" class="row g-3">
                    @csrf
                    @method('POST')

                    <div class="col-12">
                        <label for="name" class="form-label">Nome: </label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nome completo"
                            value="{{ old('name') }}">
                    </div>

                    <div class="col-12">
                        <label for="email" class="form-label">E-mail: </label>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="Melhor e-mail do usuário" value="{{ old('email') }}">
                    </div>

                    <div class="col-12">
                        <label for="password" class="form-label">Senha: </label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Senha com no mínimo 6 caracteres" value="{{ old('password') }}">
                    </div>

                    <div class="col-12">
                        <label for="roles" class="form-label">Papel/permissão: </label>
                        <select name="roles" class="form-select" id="roles">
                            <option value="">Selecione</option>
                            @forelse ($roles as $role)
                                @if ($role != 'Super Admin')
                                    <option {{ old('roles') == $role ? 'selected' : '' }} value="{{ $role }}">{{ $role }}</option>
                                @else
                                    @if (Auth::user()->hasRole('Super Admin'))
                                        <option {{ old('roles') == $role ? 'selected' : '' }} value="{{ $role }}">{{ $role }}</option>
                                    @endif
                                @endif
                            @empty
                            @endforelse
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-success btn-sm">Cadastrar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
