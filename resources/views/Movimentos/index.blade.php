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
                <span class="pesquisar">Pesquisar - Filtros:</span>
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
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>Pesquisar
                                </button>
                                <a href="{{ route('movimentos.index') }}" 
                                class="btn btn-warning btn-sm"><i class="fa-solid fa-trash"></i>Limpar</a>
                                <!-- data-bs-toggle receber info como modal
                                     data-bs-target apontar para ID do modal -->
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

        <!-- Modal -->
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
                            <!-- Conteúdo do modal vai aqui.
                            deixar label e input na mesma linha. 
                            d-flex: Coloca o conteúdo interno (label + input) em linha.
                            align-items-center: Alinha verticalmente o label e input no centro.
                            me-2: Dá uma margem à direita do label para separar do input.
                            mb-0: Remove a margem inferior padrão do label (evita desalinhamento com o input).
                            form-control: O input ainda mantém o estilo Bootstrap.
                            -->
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
                            <th>Classif.</th>
                            <th>Data</th>
                            <th>Descrição</th>
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
                                <th>{{ $movimento->categoria->classif }}</th>
                                <td>{{ \Carbon\Carbon::parse($movimento->dt)->tz('America/Sao_Paulo')->format('d/m/Y') }}</td>
                                <td>{{ $movimento->descricao }}</td>
                                <th>{{ $movimento->categoria->nome }}</th>
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
