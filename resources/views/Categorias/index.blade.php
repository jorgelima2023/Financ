@extends('layouts.admin')

@section('content')

<div class="container-fluid px-4">
    <!-- 'mb-1' margem de 1 em relacao ao rodape 'hstack gap-2' alinhamento vertical cfme no. itens (curso) (Dashboard)  -->  
    <!-- 'mt' margem top e 'mb' margem bottom -->
    <div class="mb-1 hstack gap-2">   
        <h2 class="mt-3">Categoria</h2>
        <!-- mb-3 mt-3 margem em relacao ao topo (active) marca item como ativo -->
        <ol class="breadcrumb mb-3 mt-3 ms-auto">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Categorias</li>
        </ol>
    </div>
    
    <!-- <div class="card mb-5"> -->
    <div class="card mb-5 border-light shadow">
        <!-- cabecalho -->
        <div class="card-header hstack gap-2">   
            <span>Listar</span>
            <span class="ms-auto">
                <a href="{{ route('categorias.create') }}" class="btn btn-success btn-sm"> <i class="fa-regular fa-square-plus"></i> Cadastrar</a>
            </span>
        </div>

        <div class="card-body">
            <x-alert />

            <!-- <table class="table table-striped table-hover table-bordered"> -->
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                    <th class="d-none d-md-table-cell">id</th>
                    <th>nome</th>
                    <th class="d-none d-sm-table-cell">Classificação</th>
                    <th class="d-none d-sm-table-cell">Somar</th>
                    <th >Açoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorias as $categoria)
                    <tr>
                        <th class="d-none d-md-table-cell">{{ $categoria->id }}</th>
                        <td>{{ $categoria->nome }}</td>
                        <td class="d-none d-sm-table-cell">{{ $categoria->classif }}</td>
                        <td class="d-none d-sm-table-cell">{{ $categoria->soma }}</td>
                        <!-- acoes  -->
                        <td class="d-md-flex flex-row justify-content-center">
                            <a href="{{ route('categorias.show', ['categoria' => $categoria->id ] ) }}" class="btn btn-primary btn-sm me-1 mt-1 mt-md-0"> <i class="fa-regular fa-eye"></i> Visualizar</a>
                            <a href="{{ route('categorias.edit', ['categoria' => $categoria->id ] ) }}" class="btn btn-warning btn-sm me-1 mt-1 mt-md-0"> <i class="fa-regular fa-pen-to-square"></i> Editar</a>
                            <form action="{{ route('categorias.destroy', ['categoria' => $categoria->id ] ) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger btn-sm me-1 mt-1 mt-md-0" onclick="return confirm('Confirma apagar esse registro {{ $categoria->id }} ?')"> <i class="fa-regular fa-trash-can"></i> Apagar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        
                        <div class="alert alert-danger" role="alert">
                            Nenhuma categoria encontrada!
                        </div>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

    <!-- <h2>Listar os categorias</h2>

    <br>
    <a href="{{ route('categorias.create') }}">
    <button type="button">Cadastrar</button>
    </a><br>

    <x-alert />
    
    {{-- Imprimir os registros --}}
    @forelse ($categorias as $categoria)
        id: {{ $categoria->id }}<br>
        Nome: {{ $categoria->nome }}<br>
        classif: {{ $categoria->classif }}<br>
        Somar: {{ $categoria->soma }}<br>
        Criado: {{ \Carbon\Carbon::parse($categoria->created_at)->format('d/m/Y H:i:s') }}<br>
        Editado: {{ \Carbon\Carbon::parse($categoria->updated_at)->format('d/m/Y H:i:s') }}<br>

        <a href="{{ route('categorias.show', ['categoria' => $categoria->id ] ) }}">
            <button type="button">Visualizar</button>
        </a>
        <a href="{{ route('categorias.edit', ['categoria' => $categoria->id ] ) }}">
            <button type="button">Editar</button>
        </a>

        <form action="{{ route('categorias.destroy', ['categoria' => $categoria->id ] ) }}" method="POST">
            @csrf
            @method('delete')
            <button type="submit" onclick="return confirm('Confirma apagar esse registro {{ $categoria->id }} ?')">Apagar</button>
        </form>

        <hr>

    @empty
        <p style="color: #f00">Nenhum categoria encontrado!</p>
    @endforelse -->
    
    {{-- Imprimir a paginação --}}
    {{-- {{ $courses->links() }} --}}
@endsection