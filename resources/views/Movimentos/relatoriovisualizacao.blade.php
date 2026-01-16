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
            <span class="pesquisar">Listar movimentos: Obrigação x Previsão </span>
                <x-alert />
        </div>
    </div>

    <div class="card-body">
        <!-- table-striped table-hover -->
         <!-- table-dark table-striped -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-left">Tipo</th>
                    <th class="text-left">Classif.</th>
                    <th class="text-left">Nome</th>
                    
                    <th class="text-right">Valor</th>
                </tr>
            </thead>
            <tbody>
                
                @php $classifAnterior = null; @endphp

                @forelse ($dados as $dado)

<tr class="{{ ($classifAnterior !== null && $classifAnterior !== $dado->classif) ? 'table-info' : '' }}">


                    <td>{{ $dado->tipo_lc == 'O' ? 'Obrigação' : 'Previsão' }}</td>
                    <td>{{ $dado->classif }}</td>
                    <td>{{ $dado->nome }}</td>
                    
                    <td>{{ number_format($dado->valor, 2, ',', '.') }}</td>

                </tr>

                @php $classifAnterior = trim($dado->classif); @endphp

                @empty
                    <div class="alert alert-danger" role="alert">Nenhum movimento encontrado!</div>

                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
