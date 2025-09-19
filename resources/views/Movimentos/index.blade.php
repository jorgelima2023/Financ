{{-- 
    https://pt.stackoverflow.com/questions/344575/como-retornar-registros-relecionados-entre-tabelas-em-laravel
  
    https://groups.google.com/g/laravel-brasil/c/Wt6Q-eq2tIY
  
    --}}

{{-- dd($movimentos) --}}

{{--
@forelse ( $movimentos as $movimento )
    id movimento: {{ $movimento->id }} <br>
    descricao: {{ $movimento->descricao }} <br>
    Data: {{ $movimento->dt }} <br>
    categoria_id: {{ $movimento->categoria_id }} <br>
    categoria_nome: {{ $movimento->categoria->nome }} <br>
    categoria_classif: {{ $movimento->categoria->classif }} <br> 
    forma_pgto_id:  {{ $movimento->forma_pgto_id }} <br>
    forma_pgto_nome: {{ $movimento->forma_pgto->nome }} <br> <br>
    
@empty
    <p style="color: #f00">Nenhum movimento encontrado!</p>
@endforelse

--}}

@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="mb-1 hstack gap-2">
            <h2 class="mt-3">Movimento</h2>
            <ol class="breadcrumb mb-3 mt-3 ms-auto">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Movimentos</li>
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
                    <form action="{{ route('movimentos.index') }}">
                        <div class="row">
                        
                            <div class="col-md-4 col-sm-12">
                                <label for="descricao" class="form-label">Descrição</label>
                                <input type="text" name="descricao" class="form-control" id="descricao" 
                                value="{{ $descricao }}"
                                placeholder="Descricao do movimento">
                            </div>
                            
                            <div class="col-md-4 col-sm-12">
                                    <label for="data_de" class="form-label">Cadastro de</label>
                                    <input type="date" name="data_de" class="form-control" 
                                    id="data_de" value="{{ $data_de }}"> 
                            </div>
                            <div class="col-md-4 col-sm-12">
                                    <label for="data_ate" class="form-label">Cadastro ate</label>
                                    <input type="date" name="data_ate" class="form-control" 
                                    id="data_ate" value="{{ $data_ate }}">
                            </div>

                            <div class="col-md-4 col-sm-12 mt-4 pt-3">
                                <button type="submit" class="btn btn-info btn-sm"><i class="fa-solid fa-magnifying-glass"></i>Pesquisar</button>
                                <a href="{{ route('movimentos.index') }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i>Limpar</a>
                            </div>

                        </div>

                        <!-- <div class="row">
                            <div class="col-md-4 col-sm-12">
                                    <label for="data_de" class="form-label">Cadastro de</label>
                                    <input type="date" name="data_de" class="form-control" 
                                    id="data_de" value="">
                            </div>
                            <div class="col-md-4 col-sm-12">
                                    <label for="data_ate" class="form-label">Cadastro ate</label>
                                    <input type="date" name="data_ate" class="form-control" 
                                    id="data_ate" value="">
                            </div>
                        </div> -->
                        
                    </form>
                </div>
            </div>
        </div>

        <div class="card mb-4 border-light shadow">
            <div class="card-header hstack gap-2">
                <span>Listar</span>
                
            </div>
            <div class="card-body">

                <x-alert />

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-left">ID</th>
                            <th>Descrição</th>
                            <th>Data</th>
                            <th>Categoria</th>
                            <th>Forma Pgto</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>

                        {{-- format('d/m/Y H:i:s') 
                            @money($valor, 'BRL')
                        --}}
                        
                        @forelse ($movimentos as $movimento)
                            <tr>
                                <th>{{ $movimento->id }}</th>
                                <td>{{ $movimento->descricao }}</td>
                                <td>{{ \Carbon\Carbon::parse($movimento->dt)->tz('America/Sao_Paulo')->format('d/m/Y') }}</td>
                                <td>{{ $movimento->categoria_id }} 
                                    {{ $movimento->categoria->nome }}
                                    {{ $movimento->categoria->classif }}
                                </td>
                                <td>{{ $movimento->forma_pgto_id }}
                                    {{ $movimento->forma_pgto->nome }}
                                </td>
                                <td>{{ number_format($movimento->valor, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <div class="alert alert-danger" role="alert">Nenhum movimento encontrado!</div>
                        @endforelse

                    </tbody>
                </table>

                    {{-- $movimentos->onEachSide(0)->links() --}}

            </div>
        </div>
    </div>
@endsection
