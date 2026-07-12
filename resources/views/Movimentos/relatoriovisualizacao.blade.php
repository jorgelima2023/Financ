@extends('layouts.admin')

@section('content')
    
{{-- dd($dados) --}}

<div class="container-fluid px-4">
    <div class="mb-1 hstack gap-2">
        <h4 class="mt-3">Movimento - Relatório - Seleção</h2>
        
    </div>
    <div class="card mb-1 border-light">
        <div class="card-header border border-light-subtle">
            <span class="pesquisar">Pesquisar - Filtros: {{ $sel }}</span>
            
        </div>
    </div>
    
    <!-- Listar -->
    <div class="card mb-4 border-light shadow">
        <div class="card-header hstack gap-2">
            <span class="pesquisar">Relatório movimentos</span>
                <x-alert />
        </div>
    </div>

    <div class="card-body">
        <!-- table-striped table-hover -->
         <!-- table-dark table-striped -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-left">Tipo Mov</th>
                    <th class="text-left">Data</th>
                    <th class="text-left">Descrição</th>
                    <th class="text-left">Dcto</th>
                    <th class="text-left">Categoria</th>
                    <th class="text-left">Classif</th>
                    <th class="text-left">Forma</th>
                    <th class="text-left">Conta</th>
                    <th class="text-right">Recebido</th>
                    <th class="text-right">Pago</th>
                </tr>
            </thead>
            <tbody>
                
                @php $classifAnterior = null; @endphp

                @forelse ($dados as $dado)

<tr class="{{ ($classifAnterior !== null && $classifAnterior !== $dado->categoria) ? 'table-info' : '' }}">

                    <td>{{ $dado->tipo_mov }}</td>
                    <td>{{ \Carbon\Carbon::parse($dado->dt)->tz('America/Sao_Paulo')->format('d/m/Y') }}</td>
                    <td>{{ $dado->descricao }}</td>
                    <td>{{ $dado->dcto }}</td>
                    <td>{{ $dado->categoria }}</td>
                    <td>{{ $dado->classif }}</td>
                    <td>{{ $dado->formanome }}</td>
                    <td>{{ $dado->conta }}</td>
                    <td>{{ number_format($dado->recebido, 2, ',', '.') }}</td>
                    <td>{{ number_format($dado->pago, 2, ',', '.') }}</td>
                </tr>

                @php $classifAnterior = trim($dado->categoria); @endphp

                @empty
                    <div class="alert alert-danger" role="alert">Nenhum movimento encontrado!</div>

                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
