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

        <!-- pesquisar -->
        <div class="card mb-4 border-light shadow">
            <div class="card-header border border-light-subtle">
                <!-- <span class="pesquisar" style="font-weight: 350; background-color: #D3D3D3;">Pesquisar - Filtros:</span> -->
                <span class="pesquisar">Pesquisar - Filtros:</span>

                <div class="card-body">
                    
                    <form action="{{ route('movimentos.index') }}">

                        <div class="row">
                            <div class="col-8 mb-1 d-flex align-items-center">
                                <label for="descricao" class="form-label me-2 mb-0">Descrição</label>
                                <input type="text" name="descricao" class="form-control" id="descricao" 
                                value="{{ $descricao }}"
                                placeholder="Descricao do movimento">
                            </div>
                        </div>    

                        <div class="row">    
                            <div class="col-3 mb-1 d-flex align-items-center">
                                    <label for="data_de" class="form-label">Cadastro de</label>
                                    <input type="date" name="data_de" class="form-control" 
                                    id="data_de" value="{{ $data_de }}"> 
                            </div>
                            <div class="col-3 mb-1 d-flex align-items-center"> 
                                    <label for="data_ate" class="form-label">ate</label>
                                    <input type="date" name="data_ate" class="form-control" 
                                    id="data_ate" value="{{ $data_ate }}">
                            </div>
                        </div>

                        <div class="row">

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
                        </div>

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
                            <div class="col-md-12 col-sm-12 mt-2 pt-0"><b>Seleção</b> = {{ $sel }}</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de cadastro -->
        <div class="modal fade" id="cadModal" tabindex="-1" aria-labelledby="cadModalLabel" aria-hidden="true">
            <div class="modal-dialog">

                <!-- <x-alert /> -->

                <form method="POST" action="{{ route('movimentos.store') }}">
                    @csrf
                    @method('POST')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadModalLabel">Cadastrar Movimento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>

                        <div class="modal-body">
                            <!-- conteudo -->
                            <div class="col-5 d-flex align-items-center">
                                <label for="data" class="form-label me-2 mb-0">Data</label>
                                <input type="date" name="data" class="form-control" id="data" value="{{ old('data') }}" required>
                            </div>
                            
                            <div class="col-6 mb-4 d-flex align-items-center" >
                                <label class="form-label me-2 mb-0">Tipo Lcto</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo_lcto" id="tipo_lcto_receita" value="1"
                                            {{ old('tipo') == '1' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="tipo_lcto">Receita</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo_lcto" id="tipo_lcto_despesa" value="0"
                                            {{ old('tipo') == '0' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="tipo_lcto">Despesa</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-5 mb-4 d-flex align-items-center" >
                                <label class="form-label me-2 mb-0">Tipo</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo" id="tipo_obrigacao" value="O"
                                            {{ old('tipo') == 'O' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="tipo">Obrigação</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo" id="tipo_previsao" value="P"
                                            {{ old('tipo') == 'P' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="tipo">Previsão</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-5 mb-3 d-flex align-items-center" >
                                <label for="categoria" class="form-label me-2 mb-0">Categoria</label>
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
                    
                            <div class="col-8 mb-3 d-flex align-items-center" >
                                <label for="descricao" class="form-label me-2 mb-0">Descrição</label>
                                <input type="text" name="descricao" class="form-control" id="descricao" 
                                    value="{{ old('descricao') }}" required>
                            </div>

                            <div class="col-8 mb-3 d-flex align-items-center" >
                                <label for="dcto" class="form-label me-2 mb-0">Dcto</label>
                                <input type="text" name="dcto" class="form-control" id="dcto" 
                                    value="{{ old('dcto') }}" required>
                            </div>
 
                            <div class="col-4 mb-3 d-flex align-items-center" >
                                <label for="valor" class="form-label me-2 mb-0">Valor</label>
                                <input type="text" name="valor" class="form-control" id="valor" 
                                    value="{{ old('valor') }}" required>
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

                    <!-- action js -->
                    <form method="POST" action="" id="formEditMovimento">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Alterar Movimento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>

                        <div class="modal-body">
                            
                            

                            <input type="hidden" name="editMovimentoId" id="editMovimentoId">
                            
                            <div class="col-5 d-flex align-items-center">
                                <label for="data" class="form-label me-2 mb-0">Data</label>
                                <input type="date" name="editdt" class="form-control" id="editdt" required>
                            </div>
                            
                            <div class="col-12 mb-4 d-flex align-items-center" >
                                <label class="form-label me-2 mb-0">Tipo Lcto</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="edittipo" id="edittipo_lc_1" value="1" required>
                                        <label class="form-check-label" for="edittipo_lc_1">Receita</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="edittipo" id="edittipo_lc_0" value="0" required>
                                        <label class="form-check-label" for="edittipo_lc_0">Despesa</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-4 d-flex align-items-center" >
                                <label class="form-label me-2 mb-0">Tipo</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="edittipo_lc" id="edittipo_o" value="O" required>
                                        <label class="form-check-label" for="edittipo_o">Obrigação</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="edittipo_lc" id="edittipo_p" value="P" required>
                                        <label class="form-check-label" for="edittipo_p">Previsão</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-10 mb-3 d-flex align-items-center" >
                                <label for="editcategoria_id" class="form-label me-2 mb-0">Categoria</label>
                                <select name="editcategoria_id" id="editcategoria_id" class="form-select" required>
                                    <option value="">Selecione uma categoria</option>

                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('editcategoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <div class="col-8 mb-3 d-flex align-items-center" >
                                <label for="editDescricao" class="form-label me-2 mb-0">Descrição</label>
                                <input type="text" name="editDescricao" class="form-control" id="editDescricao" required>
                            </div>

                            <div class="col-8 mb-3 d-flex align-items-center" >
                                <label for="editdcto" class="form-label me-2 mb-0">Dcto</label>
                                <input type="text" name="editdcto" class="form-control" id="editdcto" 
                                     required>
                            </div>
 
                            <div class="col-4 mb-3 d-flex align-items-center" >
                                <label for="editValor" class="form-label me-2 mb-0">Valor</label>
                                <input type="text" name="editValor" class="form-control" id="editValor" required>
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
                    
                    <div class="modal-content">
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
                
            </div>
            <div class="card-body">

                <x-alert />

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Excl.</th>
                            <th>Edit.</th>
                            <th class="text-left">Classif.</th>
                            <th class="text-left">Data</th>
                            <th class="text-left">Descrição</th>
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
                                            data-dcto="{{ $movimento->dcto }}"
                                            data-dt="{{ $movimento->dt }}"
                                            data-tipo="{{ $movimento->tipo == 'O' ? 'Obrigação' : 'Provisão' }}"
                                            data-tipo_lc="{{ $movimento->tipo_lc == 0 ? 'Receita' : 'Despesa' }}">
                                            
                                        <i class="fa-regular fa-pen-to-square"></i>
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
                                            data-dcto="{{ $movimento->dcto }}"
                                            data-dt="{{ $movimento->dt }}"
                                            data-tipo="{{ $movimento->tipo }}"
                                            data-tipo_lc="{{ $movimento->tipo_lc }}">
                                            
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                </th>

                                <td>{{ $movimento->categoria->classif }}</td>
                                <td>{{ \Carbon\Carbon::parse($movimento->dt)->tz('America/Sao_Paulo')->format('d/m/Y') }}</td>
                                <td>{{ $movimento->descricao }}</td>
                                <td>{{ $movimento->categoria_id }} - {{ $movimento->categoria->nome }}</td>
                                <td>{{ $movimento->forma_pgto_id }}
                                    {{ $movimento->forma_pgto->nome }}
                                </td>

                                @php if(  $movimento->tipo == 0 ){ @endphp
                                    <td title="Despesa" style="color:#C00;"><strong>-{{ number_format($movimento->valor, 2, ',', '.') }}</strong></td>
                                @php }else{ @endphp
                                    <td title="Receita" style="color:#050;"><strong>+{{ number_format($movimento->valor, 2, ',', '.') }}</strong></td>
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
                                <td colspan="4"></td>
                                @php if(  $totalMovimento > 0 ){ @endphp
                                    <td style="color:#050;"><strong>+{{ number_format($totalMovimento, 2, ',', '.') }}</strong></td>
                                @php }else{ @endphp
                                    <td style="color:#C00;"><strong>-{{ number_format($totalMovimento, 2, ',', '.') }}</strong></td>
                                @php } @endphp
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
            var dcto = button.getAttribute('data-dcto');
            var dt = button.getAttribute('data-dt');
            var tipo = button.getAttribute('data-tipo');
            var tipo_lc = button.getAttribute('data-tipo_lc');

            document.getElementById('editMovimentoId').value = id;
            document.getElementById('editDescricao').value = descricao;
            document.getElementById('editValor').value = valor;
            document.getElementById('editcategoria_id').value = categoria_id;
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
    </script>


@endsection
