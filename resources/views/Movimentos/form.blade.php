@extends('layouts.admin')

@section('content')

<div class="container-fluid px-4">
    <div class="mb-1 hstack gap-2">   
        <h2 class="mt-3">Movimento formulario - ofx</h2>
        <!-- mb-3 mt-3 margem em relacao ao topo (active) marca item como ativo -->
        <ol class="breadcrumb mb-3 mt-3 ms-auto">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Movimento</li>
        </ol>
    </div>
    <div class="card mb-5 border-light shadow">
        <div class="card-body">
            <x-alert />
            <div>Agência/Conta: {{ $agcc }}</div>

            <form class="row g-3" action="{{ route('Movimentos.form.update', ['movimentos' => $movimentos ] ) }}" method="POST">
                @csrf
                @method('PUT')
                <table class="table table-striped table-hover">
                    <thead>
                        
                        <tr>
                            <!-- <th class="d-none d-md-table-cell">id</th> -->
                            <th></th> 
                            <th>No.</th> 
                            <th>Data</th> 
                            <th>Descrição</th>
                            <th class="d-none d-sm-table-cell">Tipo</th>
                            <th class="d-none d-sm-table-cell">Documento</th>
                            <th class="d-none d-sm-table-cell">Valor</th>
                            <th class="d-none d-sm-table-cell">Categoria</th>
                            <th class="d-none d-sm-table-cell">Forma Pagamento</th>
                        </tr>

                        <tbody>
                            @php 
                            $i = 0;
                            $reg = 1;
                            @endphp

                            @forelse ($movimentos as $movimento)

                                <tr>
                                    <td>
                                        <input type="hidden" name="itens[{{ $i }}][id]" maxlength="5" size="5" 
                                        class="form-control" id="itens[{{ $i }}][id]" value="{{$movimento->id}}">
                                    </td>
                                    <td style="width: 6px;" align="left">
                                        {{ $reg }}
                                    </td>
                                    <td style="width: 145px;" align="left">
                                        <input type="text" name="itens[{{ $i }}][dt]" 
                                            class="form-control" id="itens[{{ $i }}][dt]" maxlength="15" size="15" 
                                            value="{{ $movimento->dt }}" >
                                    </td>
                                    <td style="width: 370px;" align="left">
                                        <input  type="text" name="itens[{{ $i }}][descricao]" 
                                            class="form-control" id="itens[{{ $i }}][descricao]" maxlength="60" size="60" 
                                            value="{{ $movimento->descricao }}" >
                                    </td>
                                    <td style="width: 70px;" align="left">
                                        <input type="text" name="itens[{{ $i }}][tipo]" 
                                            class="form-control" id="itens[{{ $i }}][tipo]" maxlength="10" size="10" 
                                            value="{{ $movimento->tipo }}" >
                                    </td>
                                    <td style="width: 120px;" align="left">
                                        <input type="text" name="itens[{{ $i }}][dcto]" 
                                            class="form-control" id="itens[{{ $i }}][dcto]" maxlength="20" size="20" 
                                            value="{{ $movimento->dcto }}" >
                                    </td>
                                    <td style="width: 123px;" align="left">
                                        <input type="text" name="itens[{{ $i }}][valor]" 
                                            class="form-control" id="itens[{{ $i }}][valor]" maxlength="15" size="15" 
                                            value="{{ number_format($movimento->valor, 2, ',', '.') }}" >
                                    </td>
                                    
                                    <td style="width: 240px;" align="left">
                                        <select name="itens[{{ $i }}][categoria_id]" id="itens[{{ $i }}][categoria_id]" 
                                            class="form-select" required>
                                            <option value="">Selecione a categoria</option>

                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $movimento->categoria_id == $categoria->id ? $movimento->categoria_id : $categoria->id }}"
                                                    {{ $movimento->categoria_id == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    
                                    <td style="width: 240px;" align="left">
                                        <select name="itens[{{ $i }}][forma_pgto_id]" id="itens[{{ $i }}][forma_pgto_id]" 
                                            class="form-select" required>
                                            <option value="">Selecione a forma pagamento</option>

                                            @foreach ($forma_pgtos as $forma_pgto)
                                                <option value="{{ $movimento->forma_pgto_id == $categoria->id ? $movimento->forma_pgto_id : $forma_pgto->id }}"
                                                    {{ $movimento->forma_pgto_id == $forma_pgto->id ? 'selected' : '' }}>
                                                    {{ $forma_pgto->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    @php
                                        $i++;
                                        $reg++;
                                    @endphp
                                </tr>
                            @empty
                                <div class="alert alert-danger" role="alert">
                                    Nenhum movimento encontrado!
                                </div>
                            @endforelse
                        </tbody>
                    </thead>
                </table>
                <div class="col-12">
                    <button type="submit" class="btn btn-warning btn-sm">Gravar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection