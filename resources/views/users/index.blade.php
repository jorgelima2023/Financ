@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="mb-1 hstack gap-2">
            <h2 class="mt-3">Usuário</h2>
            <ol class="breadcrumb mb-3 mt-3 ms-auto">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Usuários</li>
            </ol>
        </div>

        <!-- pesquisar  -->
        <div class="card mb-4 border-light shadow">
            <div class="card-header">
                <span class="pesquisar">Pesquisar</span>
                <div class="card-body">
                    <!-- usar metodo get, nao precisa colocar metodo 
                        col-md-4  dispositivos medios ocupar 4 grids
                        col-sm-12 dispositivos pequenos em uma unica linha
                    -->
                    <form action="{{ route('user.index') }}">
                        <div class="row">
                        
                            <div class="col-md-4 col-sm-12">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ $name }}"
                                placeholder="Nome do usuario">
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" id="email" value="{{ $email }}"
                                placeholder="email do usuario">
                            </div>
                            
                            <div class="col-md-4 col-sm-12 mt-4 pt-3">
                                <button type="submit" class="btn btn-info btn-sm"><i class="fa-solid fa-magnifying-glass"></i>Pesquisar</button>
                                <a href="{{ route('user.index') }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i>Limpar</a>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                    <label for="start_date_registration" class="form-label">Cadastro de</label>
                                    <input type="datetime-local" name="start_date_registration" class="form-control" 
                                    id="start_date_registration" value="{{ $start_date_registration }}">
                            </div>
                            <div class="col-md-4 col-sm-12">
                                    <label for="end_date_registration" class="form-label">Cadastro ate</label>
                                    <input type="datetime-local" name="end_date_registration" class="form-control" 
                                    id="end_date_registration" value="{{ $end_date_registration }}">
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>

        <div class="card mb-4 border-light shadow">
            <div class="card-header hstack gap-2">
                <span>Listar</span>
                <span class="ms-auto">
                    @can('create-user')
                        <a href="{{ route('user.create') }}" class="btn btn-success btn-sm"><i
                                class="fa-regular fa-square-plus"></i> Cadastrar</a>
                    @endcan

                    @can('generate-pdf-user')
                        <!-- route envia pelo nome da rota
                        <a href="{{ route('user.generate-pdf') }}" 
                            class="btn btn-warning btn-sm">
                            <i class="fa-regular fa-file-pdf"></i>Gerar PDF</a> -->

                        <!-- enviar pela url com a pesquisa para gerar o pdf  
                            generate-pdf-user 
                            a funcao request , recupera o que vier na url -->
                        <a href="{{ url('generate-pdf-user?' . request()->getQueryString() ) }}" class="btn btn-warning btn-sm"> 
                            <i class="fa-regular fa-file-pdf"></i>Gerar PDF</a> 
                    @endcan
                        
                    <a href="{{ url('generate-csv-user?' . request()->getQueryString() ) }}" class="btn btn-success btn-sm"> 
                            <i class="fa-regular fa-file-excel"></i>Gerar Planilha</a>
                            
                </span>
            </div>
            <div class="card-body">

                <x-alert />

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th class="d-none d-md-table-cell">E-mail</th>
                            <th class="d-none d-md-table-cell">Papel</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($users as $user)
                            <tr>
                                <th>{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td class="d-none d-md-table-cell">{{ $user->email }}</td>
                                <td class="d-none d-md-table-cell">
                                    @forelse ($user->getRoleNames() as $role)
                                        {{ $role }}
                                    @empty
                                        {{ "-" }}
                                    @endforelse
                                </td>
                                <td class="d-md-flex flex-row justify-content-center">
                                    @can('show-user')
                                        <a href="{{ route('user.show', ['user' => $user->id]) }}"
                                            class="btn btn-primary btn-sm me-1 mb-1">
                                            <i class="fa-regular fa-eye"></i> Visualizar
                                        </a>
                                    @endcan

                                    @can('edit-user')
                                        <a href="{{ route('user.edit', ['user' => $user->id]) }}"
                                            class="btn btn-warning btn-sm me-1 mb-1">
                                            <i class="fa-solid fa-pen-to-square"></i> Editar
                                        </a>
                                    @endcan

                                    @can('destroy-user')
                                        <form method="POST" action="{{ route('user.destroy', ['user' => $user->id]) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm me-1 mb-1"
                                                onclick="return confirm('Tem certeza que deseja apagar este registro?')"><i
                                                    class="fa-regular fa-trash-can"></i> Apagar</button>
                                        </form>
                                    @endcan

                                </td>
                            </tr>
                        @empty
                            <div class="alert alert-danger" role="alert">Nenhum usuário encontrado!</div>
                        @endforelse

                    </tbody>
                </table>

                    {{ $users->onEachSide(0)->links() }}

            </div>
        </div>
    </div>
@endsection
