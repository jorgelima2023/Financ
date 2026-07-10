@extends('layouts.admin')

@section('content')


    <div class="container-fluid px-4">

        <div class="mb-1 hstack gap-2">
            <h2 class="mt-3">Movimento importar</h2>
            <ol class="breadcrumb mb-3 mt-3 ms-auto">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Movimentos</li>
            </ol>
        </div>

        <!-- Filtros -->
        <div class="card mb-2 border-light shadow">
            <div class="card-header border border-light-subtle">
                <span class="pesquisar">Filtros - CSV:</span>

                <x-alert />
                
                <div class="card-body fs-6">

                    <!-- ----------------- importar csv  -->
                    <form method="POST" action="/movimentos/form" enctype="multipart/form-data"> 
                        @csrf

                        <!-- ----------------- tipo  -->
                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="tipo_filtro" class="col-form-label">Tipo</label>
                            </div>
                            <div class="col-3">
                                <input class="form-check-input" type="radio" name="tipo_filtro" id="tipo_filtroO" value="O">
                                <label class="form-check-label" for="tipo_filtro">Obrigação</label>
                                <input class="form-check-input" type="radio" name="tipo_filtro" id="tipo_filtroP" value="P">
                                <label class="form-check-label" for="tipo_filtro">Previsão</label>
                            </div>
                        </div>
                        <!-- ----------------- categoria  -->
                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="categoriabusca" class="col-form-label">Categoria</label>
                            </div>
                            <div class="col-6">
                                <select name="categoria_id" id="categoria" class="form-select">
                                    <option value="">Selecione uma categoria</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- ----------------- conta  -->
                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="contabusca" class="col-form-label">Conta</label>
                            </div>
                            <div class="col-5">
                                <select name="contabusca" id="contabusca" class="form-select" >
                                    <option value="">Selecione uma conta</option>
                                    @foreach ($contascorrentes as $contascorrente)
                                        <option value="{{ $contascorrente->id }}"
                                            {{ old('contascorrente->id') == $contascorrente->id ? 'selected' : '' }}>
                                            {{ $contascorrente->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- ----------------- forma  -->
                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="contabusca" class="col-form-label">Forma Pgto</label>
                            </div>
                            <div class="col-5">
                                <select name="forma_pgto_id" id="forma_pgto_id" class="form-select" required>
                                        <option value="">Selecione uma Forma de Pagamento</option>
                                            @foreach ($forma_pgtos as $forma_pgto)
                                            <option value="{{ $forma_pgto->id }}"
                                                {{ old('forma_pgto_id') == $forma_pgto->id ? 'selected' : '' }}>
                                                {{ $forma_pgto->nome }}
                                            </option>
                                        @endforeach
                                </select>
                            </div>    
                        </div>
                        <!-- ----------------- arq  -->
                        <div class="row mb-2 align-items-center">
                        <div class="col-5">
                            <div class="card mb-2 mt-2 pt-1 border-light shadow">
                                <div class="mb-1 hstack gap-1">
                                    <input type="file" name="file" class="form-control form-control-sm" accept=".csv" title=".csv">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm px-1 py-0 fs-7" id="btncsv" name="btn" value="btncsv">
                                        CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- ---------------------------------------------------------------------------- -->
        <div class="card mb-2 mt-2 pt-1 border-light shadow">
                <div class="mb-1 hstack gap-6">

                    
                    <!-- ----------------- importar ofx  -->
                    <form method="POST" action="/movimentos/form" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-1 hstack gap-4">
                            <input type="file" name="file" class="form-control form-control-sm" accept=".ofx" title=".ofx">
                            <button type="submit" class="btn btn-outline-secondary btn-sm px-1 py-0 fs-7" id="btnofx" name="btn" value="btnofx">
                                OFX
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>

@endsection
