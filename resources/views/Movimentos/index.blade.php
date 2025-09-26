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

        <div class="card mb-4 border-light shadow">
            <div class="card-header">
                <span class="pesquisar">Pesquisar - Filtros:</span>
                <div class="card-body">
                    
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
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="cadModal" tabindex="-1" aria-labelledby="cadModalLabel" aria-hidden="true">
            <div class="modal-dialog">

                <x-alert />

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
                                        <input class="form-check-input" type="radio" name="tipo_lcto" id="tipo_lcto" value="1"
                                            {{ old('tipo') == '1' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="tipo_lcto">Receita</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo_lcto" id="tipo_lcto" value="0"
                                            {{ old('tipo') == '0' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="tipo_lcto">Despesa</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-5 mb-4 d-flex align-items-center" >
                                <label class="form-label me-2 mb-0">Tipo</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo" id="tipo" value="O"
                                            {{ old('tipo') == 'O' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="tipo">Obrigação</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo" id="tipo" value="P"
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

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">

                <x-alert />

                <form method="POST" action="{{ route('movimentos.store') }}">
                    @csrf
                    @method('POST')
                    
                    
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Alterar Movimento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>

                        <div class="modal-body">
                            
                            <!-- conteudo -->
                            <input type="text" name="id" id="editMovimentoId">

                            <div class="col-5 d-flex align-items-center">
                                <label for="data" class="form-label me-2 mb-0">Data</label>
                                <input type="date" name="data" class="form-control" id="data" value="{{ old('modal-dt') }}" required>
                            </div>
                            
                            <div class="col-6 mb-4 d-flex align-items-center" >
                                <label class="form-label me-2 mb-0">Tipo Lcto</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo_lcto" id="tipo_lcto" value="1"
                                            {{ old('tipo') == '1' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="tipo_lcto">Receita</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo_lcto" id="tipo_lcto" value="0"
                                            {{ old('tipo') == '0' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="tipo_lcto">Despesa</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-5 mb-4 d-flex align-items-center" >
                                <label class="form-label me-2 mb-0">Tipo</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo" id="tipo" value="O"
                                            {{ old('tipo') == 'O' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="tipo">Obrigação</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo" id="tipo" value="P"
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
                                <input type="text" name="descricao" class="form-control" id="editDescricao" required>
                            </div>

                            <div class="col-8 mb-3 d-flex align-items-center" >
                                <label for="dcto" class="form-label me-2 mb-0">Dcto</label>
                                <input type="text" name="dcto" class="form-control" id="dcto" 
                                    value="{{ old('dcto') }}" required>
                            </div>
 
                            <div class="col-4 mb-3 d-flex align-items-center" >
                                <label for="valor" class="form-label me-2 mb-0">Valor</label>
                                <input type="text" name="valor" class="form-control" id="editValor" required>
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
        

        <div class="card mb-4 border-light shadow">
            <div class="card-header hstack gap-2">
                <span>Listar</span>
                
            </div>
            <div class="card-body">

                <x-alert />

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Edit.</th>
                            <th class="text-left">Classif.</th>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Forma Pgto</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($movimentos as $movimento)
                            <tr>
                                
                                <th>
                                    <button type="button"
                                        class="btn btn-outline-secondary btn-sm px-1 py-0 fs-7" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            data-id="{{ $movimento->id }}"
                                            data-descricao="{{ $movimento->descricao }}"
                                            data-valor="{{ $movimento->valor }}">
                                            
                                        <i class="fa-regular fa-pen-to-square"></i>{{ $mov = $movimento->id }}
                                    </button>
                                </th>

                                <th>{{ $movimento->categoria->classif }}</th>
                                <td>{{ \Carbon\Carbon::parse($movimento->dt)->tz('America/Sao_Paulo')->format('d/m/Y') }}</td>
                                <td>{{ $movimento->descricao }}</td>
                                <th>{{ $movimento->categoria_id }} - {{ $movimento->categoria->nome }}</th>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('editModal');

            modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            // Pega os dados do botão
            var id = button.getAttribute('data-id');
            var descricao = button.getAttribute('data-descricao');
            var valor = button.getAttribute('data-valor');

            // Preenche os campos do modal
            document.getElementById('editMovimentoId').value = id;
            document.getElementById('editDescricao').value = descricao;
            document.getElementById('editValor').value = valor;
            });
        });
    </script>

@endsection
