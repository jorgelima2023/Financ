@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="mb-1 hstack gap-2">
            <h2 class="mt-3">Permissões do Papel - {{ $role->name }}</h2>

            <ol class="breadcrumb mb-3 mt-3 ms-auto">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('role.index') }}" class="text-decoration-none">Papéis</a>
                </li>
                <li class="breadcrumb-item active">Permissões</li>
            </ol>
        </div>

        <div class="card mb-4 border-light shadow">

            <div class="card-header hstack gap-2">
                <span>Listar</span>

                <span class="ms-auto">
                    @can('index-role')
                        <a href="{{ route('role.index') }}" class="btn btn-info btn-sm me-1"><i class="fa-solid fa-list"></i>
                            Listar</a>
                    @endcan
                </span>
            </div>

            <div class="card-body">

                <x-alert />

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="d-none d-sm-table-cell">ID</th>
                            <th>Título</th>
                            <th class="d-none d-sm-table-cell">Nome</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                        {{-- Imprimir os registros --}}
                        @forelse ($permissions as $permission)
                            
                        <tr>
                                <td class="d-none d-sm-table-cell">{{ $permission->id }}</td>
                                <td>{{ $permission->title }}</td>
                                <td class="d-none d-sm-table-cell">{{ $permission->name }}</td>
                                <td class="text-center">
                                    @if (in_array($permission->id, $rolePermissions ?? []))
                                        @can('update-role-permission')
                                            <a href="{{ route('role-permission.update', ['role' => $role->id, 'permission' => $permission->id ]) }}">
                                                <span class="badge text-bg-success">Liberado</span>
                                            </a>
                                        @else
                                            <span class="badge text-bg-success">Liberado</span>
                                        @endcan
                                    @else
                                        @can('update-role-permission')
                                            <a href="{{ route('role-permission.update', ['role' => $role->id, 'permission' => $permission->id ]) }}">
                                                <span class="badge text-bg-danger">Bloqueado</span>
                                            </a>
                                        @else
                                            <span class="badge text-bg-danger">Bloqueado</span>
                                        @endcan
                                    @endif

                                </td>
                            </tr>

                        @empty
                            <div class="alert alert-danger" role="alert">
                                Nenhuma permissão para o papel encontrado!
                            </div>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
