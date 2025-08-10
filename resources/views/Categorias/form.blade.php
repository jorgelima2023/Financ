@extends('layouts.admin')

@section('content')

<div class="container-fluid px-4">
    <!-- 'mb-1' margem de 1 em relacao ao rodape 'hstack gap-2' alinhamento vertical cfme no. itens (curso) (Dashboard)  -->  
    <!-- 'mt' margem top e 'mb' margem bottom -->
    <div class="mb-1 hstack gap-2">   
        <h2 class="mt-3">Categoria formulario</h2>
        <!-- mb-3 mt-3 margem em relacao ao topo (active) marca item como ativo -->
        <ol class="breadcrumb mb-3 mt-3 ms-auto">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Categorias</li>
        </ol>
    </div>
    
    <!-- <div class="card mb-5"> -->
    <div class="card mb-5 border-light shadow">
        <!-- cabecalho -->
        
        

        <div class="card-body">
            <x-alert />
            
            <form class="row g-3" action="{{ route('categorias.form.update', ['categorias' => $categorias ] ) }}" method="POST">
                @csrf
                @method('PUT')
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <!-- <th class="d-none d-md-table-cell">id</th> -->
                            <th>nome</th>
                            <th class="d-none d-sm-table-cell">Classificação</th>
                            <th class="d-none d-sm-table-cell">Somar</th>
                        </tr>

                    </thead>

                    <tbody>
                        @php
                            $i = 0
                        @endphp

                        @forelse ($categorias as $categoria)
                        
                        <tr>
                            
                            <input type="hidden" name="itens[{{ $i }}][id]" maxlength="5" size="5" class="form-control" id="itens[{{ $i }}][id]" value="{{$categoria->id}}">
                            
                            
                            <td style="width: 570px;" align="left">
                                <input type="text" name="itens[{{ $i }}][nome]" class="form-control" id="itens[{{ $i }}][nome]" maxlength="60" size="60" value="{{$categoria->nome}}" >
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <input type="text" name="itens[{{ $i }}][classif]" class="form-control" id="itens[{{ $i }}][classif]" maxlength="20" size="20" value="{{$categoria->classif}}" >
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <input type="text" name="itens[{{ $i }}][soma]" class="form-control" id="itens[{{ $i }}][soma]" maxlength="1" size="1" value="{{$categoria->soma}}" >
                            </td>
                            @php
                                $i++
                            @endphp
                            
                        </tr>
                        
                        @empty
                            
                            <div class="alert alert-danger" role="alert">
                                Nenhuma categoria encontrada!
                            </div>
                        @endforelse

                    </tbody>
                </table>

                <div class="col-12">
                    <button type="submit" class="btn btn-warning btn-sm">Editar</button>
                </div>



            </form>

        </div>

    </div>

</div>

@endsection