@extends('layouts.admin')

@section('content')

<div class="container-fluid px-4">
    <!-- 'mb-1' margem de 1 em relacao ao rodape 'hstack gap-2' alinhamento vertical cfme no. itens (curso) (Dashboard)  -->  
    <!-- 'mt' margem top e 'mb' margem bottom -->
    <div class="mb-1 hstack gap-2">   
        <h2 class="mt-3">Movimento formulario</h2>
        <!-- mb-3 mt-3 margem em relacao ao topo (active) marca item como ativo -->
        <ol class="breadcrumb mb-3 mt-3 ms-auto">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Movimento</li>
        </ol>
    </div>
    
    <!-- <div class="card mb-5"> -->
    <div class="card mb-5 border-light shadow">
        <!-- cabecalho -->
        
        

        <div class="card-body">
            <x-alert />
            
            <form class="row g-3" action="{{ route('categorias.form.update', ['movimentos' => $movimentos ] ) }}" method="POST">
                @csrf
                @method('PUT')
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <!-- <th class="d-none d-md-table-cell">id</th> -->
                            <th>Data</th> 
                            <th>Descrição</th>
                            <th class="d-none d-sm-table-cell">Tipo</th>
                            <th class="d-none d-sm-table-cell">Documento</th>
                            <th class="d-none d-sm-table-cell">Valor</th>
                            <th class="d-none d-sm-table-cell">Categoria</th>
                        </tr>

                    </thead>

                    <tbody>
                        @php
                            $i = 0
                        @endphp

                        @forelse ($movimentos as $movimento)
                        
                        <tr>

                            {{-- enviar o campo id para controle se incluir ou editar --}}
                            <td>
                                <input type="hidden" name="itens[{{ $i }}][id]" maxlength="5" size="5" 
                                    class="form-control" id="itens[{{ $i }}][id]" value="{{$movimento->id}}">
                            </td>
                            <td style="width: 100px;" align="left">
                                <input type="text" name="itens[{{ $i }}][dt]" 
                                    class="form-control" id="itens[{{ $i }}][dt]" maxlength="15" size="15" 
                                    value="{{ old("itens.$i.dt", $movimento->dt) }}" >
                            </td>
                            <td style="width: 570px;" align="left">
                                <input type="text" name="itens[{{ $i }}][descricao]" 
                                    class="form-control" id="itens[{{ $i }}][descricao]" maxlength="60" size="60" 
                                    value="{{ old("itens.$i.descricao", $movimento->descricao) }}" >
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <input type="text" name="itens[{{ $i }}][tipo]" 
                                    class="form-control" id="itens[{{ $i }}][tipo]" maxlength="10" size="10" 
                                    value="{{ old("itens.$i.tipo", $categoria->tipo) }}" >
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <input type="text" name="itens[{{ $i }}][dcto]" 
                                    class="form-control" id="itens[{{ $i }}][dcto]" maxlength="20" size="20" 
                                    value="{{ old("itens.$i.dcto", $categoria->dcto) }}" >
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <input type="text" name="itens[{{ $i }}][valor]" 
                                    class="form-control" id="itens[{{ $i }}][valor]" maxlength="15" size="15" 
                                    value="{{ old("itens.$i.valor", $categoria->valor) }}" >
                            </td>

                            <td class="d-none d-sm-table-cell">
                                <input type="text" name="itens[{{ $i }}][categoria_id]" 
                                    class="form-control" id="itens[{{ $i }}][categoria_id]" maxlength="15" size="15" 
                                    value="{{ old("itens.$i.categoria_id", $categoria->categoria_id) }}" >
                            </td>

                            @php
                                $i++
                            @endphp
                            
                        </tr>
                        
                        @empty
                            
                            <div class="alert alert-danger" role="alert">
                                Nenhum movimento encontrado!
                            </div>
                        @endforelse

                    </tbody>
                </table>

                <div class="col-12">
                    <button type="submit" class="btn btn-warning btn-sm">Gravar</button>
                </div>



            </form>

        </div>

    </div>

</div>

@endsection