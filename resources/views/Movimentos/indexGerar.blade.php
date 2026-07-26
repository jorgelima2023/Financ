@extends('layouts.admin')

@section('content')

    <div class="container-fluid px-4">

        <div class="mb-1 hstack gap-3">

            <button type="button" class="btn btn-info btn-sm px-1 py-0 fs-7" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fa-solid fa-question"></i>
            </button>

            <h2 class="mt-3">Lançamentos em lote</h2>
            <ol class="breadcrumb mb-3 mt-3 ms-auto">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Movimentos-Gerar Lançamentos em lote</li>
            </ol>
        </div>

        <!-- pesquisar -->
        <div class="card mb-1 border-light shadow">
            <div class="card-header border border-light-subtle">
                
                <span class="pesquisar">Seleção:</span>

                <x-alert />
                
                <div class="card-body fs-6">
                    
                    <form action="{{ route('movimentos.indexGerar') }}">

                        <input type="hidden" name="indexGerarLctos" id="indexGerarLctos" value="1">

                        <div class="row mb-2 align-items-center">  
                            <div class="col-2">
                                <label for="descricao" class="col-form-label">Descrição</label>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control flex-grow-1" placeholder="Descricao do movimento"
                                    name="descricao" id="descricao" value="" required>
                            </div>                            
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="data_de" class="col-form-label">Vencimento inicial</label>
                            </div>
                            <div class="col-3">                  
                                <input type="date" class="form-control" name="data_de" id="data_de" value="" required>
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
                                <label for="tipo_filtro" class="col-form-label">Tipo Lcto</label>
                            </div>
                            <div class="col-3">
                                <input class="form-check-input" type="radio" name="tipo_lcto" id="tipo_lcto_receita" value="1" {{ old('tipo') == '1' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="tipo_lcto">Receita</label>
                                <input class="form-check-input" type="radio" name="tipo_lcto" id="tipo_lcto_despesa" value="0" {{ old('tipo') == '0' ? 'checked' : '' }} required checked>
                                <label class="form-check-label" for="tipo_lcto">Despesa</label>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="categoriabusca" class="col-form-label">Categoria</label>
                            </div>
                            <div class="col-6">
                                

                                <select name="categoria_id" id="categoria" class="form-select" required>
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

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="contabusca" class="col-form-label">Conta</label>
                            </div>
                            <div class="col-5">

                                <select name="contabusca" id="contabusca" class="form-select" required>
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

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="Forma" class="col-form-label">Forma Pgto</label>
                            </div>
                            <div class="col-5">
                                <select name="eforma_pgto_id" id="eforma_pgto_id" class="form-select" required>
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

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="valor" class="col-form-label">Valor Parcela</label>
                            </div>
                            <div class="col-2">
                                <input type="text" name="valor" class="form-control" id="valor" value="{{ old('valor') }}" required>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="qtde" class="col-form-label">Quantidade Parc.</label>
                            </div>
                            <div class="col-2">
                                <input type="text" name="qtde" class="form-control" id="qtde" value="{{ old('qtde') }}" required>
                            </div>
                        </div>

                        <!-- ====================================================================================================  -->

                        <div class="row">

                            <div class="col-md-4 col-sm-12 mt-2 pt-0">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>Gerar
                                </button>
                                <a href="{{ route('movimentos.indexGerar') }}" 
                                class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i>Limpar</a>
                            </div>

                        </div>
                        
                    </form>
                </div>
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
                    <!-- <div style="color: #3fb4b8;">Lançamentos em lote:</div> -->

                    <div class="pesquisar">Lançamentos em lote:</div>
                    
                    <div>🏷️ Seleção - Preencher os campos:</div>
                    <div>🏷️ Descrição - a descrição do lançamento.</div>
                    <div>🏷️ Vencimemto inicial - Data inicial de vencimento do primeiro lançamento.</div>
                    <div>🏷️ Valor parcela - informar o valor da parcela a ser gerada.</div>
                    <div>🏷️ Quantidade Parc. - Quantidade parcelas a ser gerada.</div>
                    <div>🏷️ Gerar - Faz a geração dos movimentos conforme a quantidade e valor de parcelas.</div>
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
