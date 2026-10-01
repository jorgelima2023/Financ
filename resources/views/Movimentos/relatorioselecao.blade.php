@extends('layouts.admin')

@section('content')
        
    <div class="container-fluid px-4">

        <div class="mb-1 hstack gap-3">

            <!-- Button trigger modal -->
            <!-- <button background-color: #D3D3D3 type="button" class="btn-outline-secondary btn-sm px-1 py-0 fs-7" data-bs-toggle="modal" data-bs-target="#exampleModal"> -->

            <button type="button" class="btn btn-info btn-sm px-1 py-0 fs-7" data-bs-toggle="modal" data-bs-target="#exampleModal">
            
            <i class="fa-solid fa-question"></i>
            <!-- <i class="fa-solid fa-person-circle-question"></i> -->
            <!-- <i class="fa-solid fa-clipboard-question"></i> -->

            </button>

            <!-- <button type="submit" class="btn btn-info btn-sm">
                <i class="fa-solid fa-magnifying-glass"></i>ajuda
            </button> -->


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

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="data_de" class="col-form-label">Movimento de</label>
                            </div>
                            <div class="col-3">
                                <input type="date" class="form-control" name="data_de" id="data_de" value="">
                            </div>
                            <div class="col-1 text-center">até</div>
                            <div class="col-3">
                                <input type="date" class="form-control" name="data_ate" id="data_de" value="">
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="categoriabusca" class="col-form-label">Categoria</label>
                            </div>
                            <div class="col-6">

                                <select name="categoria_id" id="categoria" class="form-select">
                                    <option value="">Selecione uma categoria ou digite para pesquisar...</option>

                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="tipo_filtro" class="col-form-label">Tipo</label>
                            </div>
                            <div class="col-3">
                                <input class="form-check-input" type="radio" name="tipo_filtro" id="tipo_filtroO" value="O" checked>
                                <label class="form-check-label" for="tipo_filtro">Obrigação</label>
                                <input class="form-check-input" type="radio" name="tipo_filtro" id="tipo_filtroP" value="P">
                                <label class="form-check-label" for="tipo_filtro">Previsão</label>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="OmitirData" class="col-form-label">Sintético</label>
                            </div>
                            <div class="col-6">

                                <td style="width: 10px;" align="right">
                                    <input type="checkbox" name="OmitirData" 
                                    value="1"
                                    title="Omitir Data">
                                </td>

                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="mesAno" class="col-form-label">Agrupar Mês/Ano</label>
                            </div>
                            <div class="col-6">

                                <td style="width: 10px;" align="right">
                                    <input type="checkbox" name="mesAno" 
                                    value="1"
                                    title="Mês/Ano">
                                </td>

                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-4 col-sm-12 mt-2 pt-0">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>Visualizar
                                </button>
                                <a href="{{ route('movimentos.relatorioselecao') }}" 
                                class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i>Limpar</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" style="color: #3fb4b8;">Ajuda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- <div style="color: #3fb4b8;">Relatório:</div> -->

                        <div class="pesquisar">Relatório:</div>
                       
                        <div>🏷️ Movimentos de / até - apresentar os movimentos do períodoo escolhido.</div>
                        <div>🏷️ Categoria - apresentar os movimentos da categoria.</div>
                        <div>🏷️ Tipo - apresentar os movimentos do tipo.</div>
                        <div>🏷️ Analítico - apresentar os movimentos sem agrupar.</div>
                        <div>🏷️ Sintético - omitir agrupamento por datas, apresentando por categorias x datas os movimentos.</div>
                        <div>🏷️ Agrupar Mês/Ano - Apresentar movimentos agrupados por mês e ano.</div>
                        <div>🏷️ Visualizar - Faz a pesquisa dos movimentos com os filtros escolhidos.</div>
                        <div>🏷️ Limpar - Limpar os campos preenchidos.</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div>
            </div>
        </div>



@endsection
