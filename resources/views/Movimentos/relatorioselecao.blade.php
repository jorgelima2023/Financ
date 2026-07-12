@extends('layouts.admin')

@section('content')
    
    <div class="container-fluid px-4">

        <div class="mb-1 hstack gap-2">
            <h4 class="mt-3">Relatório seleção</h4>
            <ol class="breadcrumb mb-3 mt-3 ms-auto">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Movimentos Relatório - Seleção</li>
            </ol>
        </div>

        <!-- pesquisar -->
        <div class="card mb-1 border-light shadow">
            <div class="card-header border border-light-subtle">
                <!-- <span class="pesquisar" style="font-weight: 350; background-color: #D3D3D3;">Pesquisar - Filtros:</span> -->
                <span class="pesquisar">Pesquisar - Filtros:</span>

                <div class="card-body">
                    
                    <form action="{{ route('movimentos.relatoriovisualizacao') }}" target="_blank">

                        <!-- <div class="row">
                            <div class="col-8 mb-1 d-flex align-items-center">
                                <label for="descricao" class="form-label me-2 mb-0">Descrição</label>
                                <input type="text" name="descricao" class="form-control" id="descricao" 
                                value=""
                                placeholder="Descricao do movimento">
                            </div>*Qualquer posição
                        </div>     -->

                        <!-- ******************************************************************************************* -->
                        
                        <!-- <div class="row mb-2 align-items-center">  
                            <div class="col-2">
                                <label for="descricao" class="col-form-label">Descrição</label>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control flex-grow-1" placeholder="Descricao do movimento (*Qualquer posição)"
                                    name="descricao" id="descricao" value="" >
                            </div>                            
                        </div> -->

                        <!-- <div class="row">    
                            <div class="col-3 mb-1 d-flex align-items-center">
                                    <label for="data_de" class="form-label">Cadastro de</label>
                                    <input type="date" name="data_de" class="form-control" 
                                    id="data_de" value=""> 
                            </div>
                            <div class="col-3 mb-1 d-flex align-items-center"> 
                                    <label for="data_ate" class="form-label">ate</label>
                                    <input type="date" name="data_ate" class="form-control" 
                                    id="data_ate" value="">
                            </div>
                        </div> -->

                        <!-- ********************************************************************** -->
                        
                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="data_de" class="col-form-label">Cadastro de</label>
                            </div>
                            <div class="col-3">
                                <input type="date" class="form-control" name="data_de" id="data_de" value="">
                            </div>
                            <div class="col-1 text-center">até</div>
                            <div class="col-3">
                                <input type="date" class="form-control" name="data_ate" id="data_de" value="">
                            </div>
                        </div>

                        <!-- <div class="row">

                            <div class="col-8 mb-1 d-flex align-items-center">
                                <label class="form-label me-2 mb-0">Tipo</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo_filtro" id="tipo_filtroO" value="O">
                                        <label class="form-check-label" for="tipo_filtro">Obrigação</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo_filtro" id="tipo_filtroP" value="P">
                                        <label class="form-check-label" for="tipo_filtro">Previsão</label>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- <div class="row">
                            <div class="col-8 mb-1 d-flex align-items-center">
                                <label for="categoriabusca" class="form-label me-2 mb-0">Categoria</label>
                                <input type="text" name="categoriabusca" class="form-control" id="categoriabusca" 
                                value=""
                                placeholder="categoria do movimento">
                            </div>
                        </div> -->
                 
                         <!-- *************************************************************************************** -->

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

                        <div class="row">

                            <div class="col-md-4 col-sm-12 mt-2 pt-0">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>Pesquisar
                                </button>
                                <a href="{{ route('movimentos.relatorioselecao') }}" 
                                class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i>Limpar</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>


@endsection
