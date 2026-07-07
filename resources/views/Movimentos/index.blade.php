@extends('layouts.admin')

@section('content')



 @php 
   // foreach ($movimentos as $value) {
   // echo 'id=' . $value->id . ' descr=' . $value->descricao . ' conta=' . $value->contascorrente_id . "<br>";
   // }
    
 @endphp

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



        <!-- pesquisar -->
        <div class="card mb-1 border-light shadow">
            <div class="card-header border border-light-subtle">
                
                <span class="pesquisar">Pesquisar - Filtros:</span>

                <div class="card-body fs-6">
                    
                    <form action="{{ route('movimentos.index') }}">

                        <div class="row mb-2 align-items-center">  
                            <div class="col-2">
                                <label for="descricao" class="col-form-label">Descrição</label>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control flex-grow-1" placeholder="Descricao do movimento (*Qualquer posição)"
                                    name="descricao" id="descricao" value="{{ $descricao }}" >
                            </div>                            
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="data_de" class="col-form-label">Cadastro de</label>
                            </div>
                            <div class="col-3">                  
                                <input type="date" class="form-control" name="data_de" id="data_de" value="{{ $data_de }}">
                            </div>
                            <div class="col-1 text-center">até</div>
                            <div class="col-3">
                                <input type="date" class="form-control" name="data_ate" id="data_de" value="{{ $data_ate }}">
                            </div>
                        </div>

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

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="categoriabusca" class="col-form-label">Categoria</label>
                            </div>
                            <div class="col-6">
                                <!-- campo texto digitar categoria! 
                                    <input type="text" name="categoriabusca" class="form-control" id="categoriabusca" 
                                    value="{{ $categoriabusca }}" placeholder="categoria do movimento"> -->

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

                        <div class="row mb-2 align-items-center">
                            <div class="col-2">
                                <label for="contabusca" class="col-form-label">Conta</label>
                            </div>
                            <div class="col-5">

                                @php // DESATIVADO !!!  campo select option busca conta corrente
                                 if(  1 == 2 ){ @endphp
                                <select name="contabusca" id="contabusca" class="form-select" >
                                    <option value="">Selecione uma conta</option>

                                    @foreach ($contascorrentes as $contascorrente)
                                        <option value="{{ $contascorrente->id }}"
                                            {{ old('contascorrente->id') == $contascorrente->id ? 'selected' : '' }}>
                                            {{ $contascorrente->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @php } @endphp
                                    
                                <select name="contabusca[]" id="contabusca" class="form-select" multiple size="6">
                                    
                                    @foreach ($contascorrentes as $contascorrente)
                                        <option value="{{ $contascorrente->id }}"
                                            {{ old('contascorrente->id') == $contascorrente->id ? 'selected' : '' }}>
                                            {{ $contascorrente->nome }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <!-- ====================================================================================================  -->

                        <div class="row">

                            <div class="col-md-4 col-sm-12 mt-2 pt-0">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>Pesquisar
                                </button>
                                <a href="{{ route('movimentos.index') }}" 
                                class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i>Limpar</a>
                                
                                <button type="button" class="btn btn-success btn-sm" 
                                        data-bs-toggle="modal" data-bs-target="#cadModal">
                                    <i class="fa-solid fa-magnifying-glass"></i>Cadastrar
                                </button>    
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-12 mt-2 pt-0 fst-italic small">{{ $sel }}</div>
                            <!-- class="col-md-12 col-sm-12 mt-2 pt-0 fs-6"> <small>{{ $sel }}</small> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
     

        <div class="card mb-2 mt-2 pt-1 border-light shadow">
            <div class="row g-0 ">
                    <div class="col-md-6"></div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-1 py-0 fs-7" 
                                title="Clique aqui para gerar - Bar-Chart"> 
                                <a href="{{ route('Movimentos.pie' , 
                                            [ 'descricao' => $descricao , 
                                            'tipo_filtro' => $tipo_filtro ,
                                            'data_de' => $data_de ,
                                            'data_ate' => $data_ate,
                                            'grafico' => 'bar',
                                            'contabusca' => 1
                                            ] ) }}" 
                                        target="_blank">
                                    <i class="fa-solid fa-pie"></i>Bar-Chart
                                </a>
                        </button>
                    </div>
                    
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-1 py-0 fs-7" 
                                title="Clique aqui para gerar - Pie chart"> 
                                <a href="{{ route('Movimentos.pie' , 
                                            [ 'descricao' => $descricao , 
                                            'tipo_filtro' => $tipo_filtro ,
                                            'data_de' => $data_de ,
                                            'data_ate' => $data_ate,
                                            'grafico' => 'doughnut',
                                            'contabusca' => 1 
                                            ] ) }}" 
                                        target="_blank">
                                    <i class="fa-solid fa-pie"></i>Pie-Chart
                                </a>
                        </button>
                    </div>

                    <div class="col-md-4">Gráficos</div>
                
            </div>
        </div>
        
        <div class="card mb-2 mt-2 pt-1 border-light shadow">
            <!-- <div class="card-body"> -->
                <!-- <div class="row-4 col-6"> -->
                <div class="mb-1 hstack gap-6">
                    <i class="fa-solid fa-download"></i>
                            
                    <form method="POST" action="/movimentos/form" enctype="multipart/form-data">
                        @csrf
                        <!-- <input type="file" name="file" accept=".xlsx, .xls"> -->
                        <div class="mb-1 hstack gap-4">
                            <input type="file" name="file" class="form-control form-control-sm" accept=".ofx" title=".ofx">
                            <button type="submit" class="btn btn-outline-secondary btn-sm px-1 py-0 fs-7" id="btnofx" name="btn" value="btnofx">
                                OFX
                            </button>

                            <input type="file" name="file" class="form-control form-control-sm" accept=".csv" title=".csv" id="inputcsv">
                            <button type="submit" class="btn btn-outline-secondary btn-sm px-1 py-0 fs-7" id="btncsv" name="btn" value="btncsv">
                                CSV
                            </button>

                            <button type="button" name="btnfecha" id="btnfecha" title="voltar"
                                        class="btn btn-outline-secondary btn-sm px-1 py-1 fs-7"

                                    
                                    <i class="fa-sharp fa-rectangle-xmark"><</i>
                            </button>
    
                        </div>

                        <div class="mb-2 hstack gap-3">
                            <span id="texto" style="display:none;">
                            <div id="texto" class="d-flex align-items-center gap-2" style="display:none;">    
                                Categoria
                                <select name="categoria_id" id="categoria" class="form-select" required>
                                        <option value="">Selecione uma categoria - CSV</option>
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}"
                                                    {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nome }}
                                                </option>
                                            @endforeach
                                </select>
                                Forma Pgto
                                <select name="forma_pgto_id" id="forma_pgto_id" class="form-select" required>
                                        <option value="">Selecione uma Forma de Pagamento - CSV</option>
                                            @foreach ($forma_pgtos as $forma_pgto)
                                            <option value="{{ $forma_pgto->id }}"
                                                {{ old('forma_pgto_id') == $forma_pgto->id ? 'selected' : '' }}>
                                                {{ $forma_pgto->nome }}
                                            </option>
                                        @endforeach
                                </select>
                            </span>
                    
                        </div>

                    </form>
            </div>
        </div>

        <!-- Modal de cadastro -->
        <div class="modal fade" id="cadModal" tabindex="-1" aria-labelledby="cadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('movimentos.store') }}">
                    @csrf
                    @method('POST')
                    <div class="modal-content fs-7">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadModalLabel">Cadastrar Movimento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        
                        <!-- conteudo -->
                        <div class="modal-body">
                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="data" class="col-form-label">Data</label>
                                </div>
                                <div class="col-4">
                                    <input type="date" name="data" class="form-control" id="data" value="{{ old('data') }}" required>
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="tipo_filtro" class="col-form-label">Tipo Lcto</label>
                                </div>
                                <div class="col-5">
                                    <input class="form-check-input" type="radio" name="tipo_lcto" id="tipo_lcto_receita" value="1" {{ old('tipo') == '1' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="tipo_lcto">Receita</label>
                                    <input class="form-check-input" type="radio" name="tipo_lcto" id="tipo_lcto_despesa" value="0" {{ old('tipo') == '0' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="tipo_lcto">Despesa</label>
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="tipo" class="col-form-label">Tipo</label>
                                </div>
                                <div class="col-5">
                                    <input class="form-check-input" type="radio" name="tipo" id="tipo_obrigacao" value="O" {{ old('tipo') == 'O' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="tipo">Obrigação</label>
                                    <input class="form-check-input" type="radio" name="tipo" id="tipo_previsao" value="P" {{ old('tipo') == 'P' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="tipo">Previsão</label>
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="categoria" class="col-form-label">Categoria</label>
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
                            
                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="conta" class="col-form-label">Conta</label>
                                </div>
                                <div class="col-5">
                                    <select name="conta_id" id="conta_id" class="form-select" required>
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

                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="Forma" class="col-form-label">Forma Pgto</label>
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

                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="descricao" class="col-form-label">Descrição</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" name="descricao" class="form-control" id="descricao" value="{{ old('descricao') }}" required>
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="dcto" class="col-form-label">Dcto</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" name="dcto" class="form-control" id="dcto" value="{{ old('dcto') }}" required>
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="valor" class="col-form-label">Valor</label>
                                </div>
                                <div class="col-4">
                                    <input type="text" name="valor" class="form-control" id="valor" value="{{ old('valor') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
        <!-- <x-alert /> -->
                    <form method="POST" action="" id="formEditMovimento">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-content fs-7">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Alterar Movimento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <!-- conteudo -->
                        <div class="modal-body">

                            <input type="hidden" name="editMovimentoId" id="editMovimentoId">
                            
                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="editdt" class="col-form-label">Data</label>
                                </div>
                                <div class="col-4">
                                    <input type="date" name="editdt" class="form-control" id="editdt" required>
                                </div>
                            </div>
                            
                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="tipo_filtro" class="col-form-label">Tipo Lcto</label>
                                </div>
                                <div class="col-5">
                                    <input class="form-check-input" type="radio" name="edittipo" id="edittipo_lc_1" value="1" required>
                                    <label class="form-check-label" for="edittipo_lc_1">Receita</label>
                                    <input class="form-check-input" type="radio" name="edittipo" id="edittipo_lc_0" value="0">
                                    <label class="form-check-label" for="edittipo_lc_0">Despesa</label>
                                </div>
                            </div>

                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="tipo" class="col-form-label">Tipo</label>
                                </div>
                                <div class="col-5">
                                    <input class="form-check-input" type="radio" name="edittipo_lc" id="edittipo_o" value="O" required>
                                    <label class="form-check-label" for="edittipo_o">Obrigação</label>
                                    <input class="form-check-input" type="radio" name="edittipo_lc" id="edittipo_p" value="P" required>
                                    <label class="form-check-label" for="edittipo_p">Previsão</label>
                                </div>
                            </div>

                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="editcategoria_id" class="col-form-label">Categoria</label>
                                </div>
                                <div class="col-6">
                                    <select name="editcategoria_id" id="editcategoria_id" class="form-select" required>
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

                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="econta_id" class="col-form-label">Conta</label>
                                </div>
                                <div class="col-8">
                                    <select name="econta_id" id="econta_id" class="form-select" required>
                                        <option value="">Selecione uma conta</option>

                                        @foreach ($contascorrentes as $conta)
                                            
                                            <option value="{{ $conta->id }}"
                                     {{ old('contascorrente_id') == $conta->id ? 'selected' : '' }}>
                                     {{ $conta->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
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

                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="editDescricao" class="col-form-label">Descrição</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" name="editDescricao" class="form-control" id="editDescricao" required>
                                </div>
                            </div>

                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="editdcto" class="col-form-label">Dcto</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" name="editdcto" class="form-control" id="editdcto" required>
                                </div>
                            </div>

                            <div class="row mb-1 align-items-center">
                                <div class="col-3">
                                    <label for="editValor" class="col-form-label">Valor</label>
                                </div>
                                <div class="col-4">
                                    <input type="text" name="editValor" class="form-control" id="editValor" required>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        
        <!-- Modal de Exclusão -->
        <div class="modal fade" id="excluiModal" tabindex="-1" aria-labelledby="excluiModalLabel" aria-hidden="true">
            <div class="modal-dialog">

                <x-alert />
                    <!-- action js -->
                    <form method="POST" action="" id="formExcluiMovimento">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-content fs-7">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Excluir Movimento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>

                        <div class="modal-body">
                            
                            <input type="hidden" name="excluiMovimentoId" id="excluiMovimentoId">
                            
                            <div class="col-5 d-flex align-items-center">
                                <label for="data" class="form-label me-2 mb-0">Data</label>
                                <input type="date" name="excluidt" class="form-control" id="excluidt" readonly>
                            </div>
                            
                            <div class="col-12 mb-4 d-flex align-items-center" >
                                <label class="form-label me-2 mb-0">Tipo Lcto</label>
                                <input type="text" name="excluitipo" class="form-control" id="excluitipo" readonly>
                            </div>

                            <div class="col-12 mb-4 d-flex align-items-center" >
                                <label class="form-label me-2 mb-0">Tipo</label>
                                <input type="text" name="excluitipo_lc" class="form-control" id="excluitipo_lc" readonly>
                            </div>

                            <div class="col-10 mb-3 d-flex align-items-center" >
                                <label for="editcategoria_id" class="form-label me-2 mb-0">Categoria</label>
                                <input type="text" name="excluicategoria" class="form-control" id="excluicategoria" readonly>
                            </div>
                    
                            <div class="col-8 mb-3 d-flex align-items-center" >
                                <label for="excluiDescricao" class="form-label me-2 mb-0">Descrição</label>
                                <input type="text" name="excluiDescricao" class="form-control" id="excluiDescricao" readonly>
                            </div>

                            <div class="col-8 mb-3 d-flex align-items-center" >
                                <label for="excluidcto" class="form-label me-2 mb-0">Dcto</label>
                                <input type="text" name="excluidcto" class="form-control" id="excluidcto" readonly>
                            </div>
 
                            <div class="col-4 mb-3 d-flex align-items-center" >
                                <label for="excluiValor" class="form-label me-2 mb-0">Valor</label>
                                <input type="text" name="excluiValor" class="form-control" id="excluiValor" readonly>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Excluir</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <!-- Listar -->
        <div class="card mb-4 border-light shadow">
            <div class="card-header hstack gap-2">
                <span class="pesquisar">Listar</span>
                <!-- <span class="pesquisar" style="font-weight: 350; background-color: #D3D3D3;">Listar</span> -->
                <x-alert />
            </div>
            <div class="card-body">

                <!-- <x-alert /> -->

                <table class="table table-striped table-hover fs-7">
                    <thead>
                        <tr>
                            <th>Excl.</th>
                            <th>Edit.</th>
                            <th class="text-left">Tipo</th>
                            <th class="text-left">Classif.</th>
                            <th class="text-left">Data</th>
                            <th class="text-left">Descrição</th>
                            <th class="text-left">Dcto</th>
                            <th class="text-left">Conta</th>
                            <th class="text-left">Categoria</th>
                            <th class="text-left">Forma Pgto</th>
                            <th class="text-right">Valor</th>
                            <!-- <th class="text-left">tipo_lcto</th>
                            <th class="text-left">tipo:0-Receita/1-Despesa</th>
                            <th class="text-left">Dcto</th> -->
                        </tr>
                    </thead>

                    <tbody>
                            @forelse ($movimentos as $movimento)
                            <tr>
                                <!-- botao exclui -->
                                 <!-- class="btn btn-outline-secondary btn-sm px-1 py-0 fs-7"  -->
                                <th>
                                    <button type="button" title="Clique aqui para excluir esse lançamento: {{ $mov = $movimento->id }}"
                                        class="btn btn-outline-secondary btn-sm px-1 py-0 fs-7" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#excluiModal"
                                            data-id="{{ $movimento->id }}"
                                            data-descricao="{{ $movimento->descricao }}"
                                            data-valor="{{ number_format($movimento->valor, 2, ',', '.') }}"
                                            data-categoria_nome="{{ $movimento->categoria->nome }}"
                                            data-categoria_nome="{{ $movimento->forma_pgto->nome }}"
                                            data-dcto="{{ $movimento->dcto }}"
                                            data-dt="{{ $movimento->dt }}"
                                            data-tipo="{{ $movimento->tipo == 'O' ? 'Obrigação' : 'Provisão' }}"
                                            data-tipo_lc="{{ $movimento->tipo_lc == 0 ? 'Receita' : 'Despesa' }}">
                                            
                                        <!-- <i class="fa-regular fa-trash"></i> -->
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </th>
                                <!-- botao edita -->
                                <th>
                                    <button type="button" title="Clique aqui para editar esse lançamento: {{ $mov = $movimento->id }}"
                                        class="btn btn-outline-secondary btn-sm px-1 py-0 fs-7" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            data-id="{{ $movimento->id }}"
                                            data-descricao="{{ $movimento->descricao }}"
                                            data-valor="{{ number_format($movimento->valor, 2, ',', '.') }}"
                                            data-categoria_id="{{ $movimento->categoria_id }}"
                                            data-conta_id="{{ $movimento->contascorrente_id }}"
                                            data-forma_pgto_id="{{ $movimento->forma_pgto_id }}"
                                            data-dcto="{{ $movimento->dcto }}"
                                            data-dt="{{ $movimento->dt }}"
                                            data-tipo="{{ $movimento->tipo }}"
                                            data-tipo_lc="{{ $movimento->tipo_lc }}">
                                            
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                </th>

                                <td title="Categoria">{{ $movimento->tipo_lc == 'O' ? 'Obrig.' : 'Prev.' }}</td>
                                <td title="Categoria">{{ $movimento->categoria->classif }}</td>
                                <td title="Data">{{ \Carbon\Carbon::parse($movimento->dt)->tz('America/Sao_Paulo')->format('d/m/Y') }}</td>
                                <td title="Descrição">{{ $movimento->descricao }}</td>
                                <td title="Documento">{{ $movimento->dcto }}</td>
                                <td title="Conta">{{ $movimento->contascorrente->nome }} - {{ $movimento->contascorrente->conta }}</td>
                                <td title="Categoria">{{ $movimento->categoria_id }} - {{ $movimento->categoria->nome }}</td>
                                <td title="Forma Pgto">{{ $movimento->forma_pgto_id }} - {{ $movimento->forma_pgto->nome }}</td>

                                @php if(  $movimento->tipo == 0 ){ @endphp
                                    <td title="Despesa" style="color:#C00;"><strong>{{ number_format($movimento->valor, 2, ',', '.') }}</strong></td>
                                @php }else{ @endphp
                                    <td title="Receita" style="color:#050;"><strong>{{ number_format($movimento->valor, 2, ',', '.') }}</strong></td>
                                @php } @endphp

                                <!-- <td>{{-- $movimento->tipo_lc --}}</td>
                                <td>{{-- $movimento->tipo --}}</td>
                                <td>{{-- $movimento->dcto --}}</td> -->
                            </tr>
                            
                        @empty
                            <div class="alert alert-danger" role="alert">Nenhum movimento encontrado!</div>
                        @endforelse
                            <tr>
                                <td colspan="3"><strong>Total Lctos: {{ $qtde_registros }}</strong></td>
                                <td colspan="7"></td>
                                @php if(  $totalMovimento > 0 ){ @endphp
                                    <td style="color:#050;"><strong>{{ number_format($totalMovimento, 2, ',', '.') }}</strong></td>
                                @php }else{ @endphp
                                    <td style="color:#C00;"><strong>{{ number_format($totalMovimento, 2, ',', '.') }}</strong></td>
                                @php } @endphp
                            </tr>

                            <tr>
                                <td colspan="3"><strong>Despesas:</strong></td>
                                <td colspan="5"></td>
                                <td style="color:#C00;"><strong>{{ number_format($totalDespesa, 2, ',', '.') }}</strong></td>
                                <td colspan="3"></td>                                   
                            </tr>
                            <tr>
                                <td colspan="3"><strong>Receitas:</strong></td>
                                <td colspan="5"></td>
                                <td style="color:#050;"><strong>{{ number_format($totalReceita, 2, ',', '.') }}</strong></td>
                                <td colspan="3"></td>    
                            </tr>
                    </tbody>
                </table>

                    {{-- $movimentos->onEachSide(0)->links() --}}

            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        /*
        const linhas = document.querySelectorAll("table tbody tr");

        linhas.forEach(tr => {
            const tipoCell = tr.children[9];  // Coluna tipo: 0 (receita), 1 (despesa)
            const valorCell = tr.children[7]; // Coluna valor

            if (tipoCell && valorCell) {
                const tipo = tipoCell.textContent.trim();
                if (tipo === "1") {
                    valorCell.classList.add("valor-despesa");
                } else if (tipo === "0") {
                    valorCell.classList.add("valor-receita");
                }
            }
        });
        */

        var modal = document.getElementById('editModal');

        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var id = button.getAttribute('data-id');
            var descricao = button.getAttribute('data-descricao');
            var valor = button.getAttribute('data-valor');
            var categoria_id = button.getAttribute('data-categoria_id');

            var conta_id = button.getAttribute('data-conta_id');

            var forma_id = button.getAttribute('data-forma_pgto_id');

            var dcto = button.getAttribute('data-dcto');
            var dt = button.getAttribute('data-dt');
            var tipo = button.getAttribute('data-tipo');
            var tipo_lc = button.getAttribute('data-tipo_lc');

            document.getElementById('editMovimentoId').value = id;
            document.getElementById('editDescricao').value = descricao;
            document.getElementById('editValor').value = valor;
            document.getElementById('editcategoria_id').value = categoria_id;

            document.getElementById('econta_id').value = conta_id;
            document.getElementById('eforma_pgto_id').value = forma_id;

            document.getElementById('editdcto').value = dcto;
            document.getElementById('editdt').value = dt;

            let ix = document.getElementById('editMovimentoId').value;

            // Radios - selecionar correto
            document.querySelectorAll('input[name="edittipo"]').forEach((el) => {
                el.checked = el.value === tipo;
            });

            document.querySelectorAll('input[name="edittipo_lc"]').forEach((el) => {
                el.checked = el.value === tipo_lc;
            });

            // Define o action do form dinamicamente (opcional)
            let form = document.getElementById('formEditMovimento');
            if (form) {  
                form.action = '/update-movimento/' + ix; // Ajuste conforme sua rota de update
            }
        });

        var modalExclui = document.getElementById('excluiModal');
            modalExclui.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var id = button.getAttribute('data-id');
            var descricao = button.getAttribute('data-descricao');
            var valor = button.getAttribute('data-valor');
            var categoria_nome = button.getAttribute('data-categoria_nome');
            var dcto = button.getAttribute('data-dcto');
            var dt = button.getAttribute('data-dt');
            var tipo = button.getAttribute('data-tipo');
            var tipo_lc = button.getAttribute('data-tipo_lc');

            document.getElementById('excluiMovimentoId').value = id;
            document.getElementById('excluiDescricao').value = descricao;
            document.getElementById('excluiValor').value = valor;
            document.getElementById('excluicategoria').value = categoria_nome;
            document.getElementById('excluidcto').value = dcto;
            document.getElementById('excluidt').value = dt;
            document.getElementById('excluitipo').value = tipo;
            document.getElementById('excluitipo_lc').value = tipo_lc;

            let ix = document.getElementById('excluiMovimentoId').value;

            // Define o action do form dinamicamente (opcional)
            let form = document.getElementById('formExcluiMovimento');
            if (form) {  
                form.action = '/destroy-movimento/' + ix; // Ajuste conforme sua rota de update
            }
        });

    });

    const btn = document.getElementById("inputcsv");
    const texto = document.getElementById("texto");
    const btnx = document.getElementById("btnfecha");
    

    // btn.addEventListener("mouseenter", () => {

    btn.addEventListener("click", () => {
        texto.style.display = "inline";
    });

    btnx.addEventListener("click", () => {
        texto.style.display = "none";
    });

    /*
    Eventos mais comuns
        Evento     Quando acontece
        click      Clique do mouse
        mouseenter Mouse entra no elemento
        mouseleave Mouse sai do elemento
        mouseover  Mouse passa sobre o elemento
        mouseout   Mouse sai do elemento
        keydown    Tecla pressionada
        submit     Envio de formulário
    */

    </script>


@endsection
