@extends('layouts.admin')

@section('content')
    
{{-- dd($dados) --}}

<!-- Horizontal Alignment 
<td class="text-left">Left-aligned content</td>
<td class="text-center">Centered content</td>
<td class="text-right">Right-aligned content</td> -->

<!-- Vertical Alignment 
<td class="align-top">Top-aligned content</td>
<td class="align-middle">Middle-aligned content</td>
<td class="align-bottom">Bottom-aligned content</td> -->

<!-- <div class="container-fluid px-4"> -->
<div>
<!-- <div class="mb-1 hstack gap-2"> -->
        <h4 class="mt-3">Movimento - Relatório</h2>
        
    <!-- </div> -->
    <!-- <div class="card mb-1 border-light"> -->
        <!-- <div class="card-header border border-light-subtle"> -->
        <div>    
            <span class="pesquisar">Pesquisar - Filtros: {{ $sel }}</span>
            
        </div>
    <!-- </div> -->
    
    <!-- Listar -->
    <!-- <div class="card mb-4 border-light shadow"> -->
        <!-- <div class="card-header hstack gap-2"> -->
            <span class="pesquisar">Relatório movimentos</span>
                <x-alert />
        <!-- </div> -->
    <!-- </div> -->

    <!-- <div class="card-body"> -->
        <!-- table-striped table-hover -->
         <!-- table-dark table-striped -->
        <!-- <table class="table table-striped"> -->
        <table>
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
                
                @php 
                $catAnterior = null;
                $totrecebido = 0;
                $totpago     = 0;
                @endphp

                @forelse ($dados as $dado)

                <!-- <tr class="{{-- {{ ($classifAnterior !== null && $classifAnterior !== $dado->categoria) --}} ? 'table-info' : '' }}"> -->

                @php
                if( $catAnterior !== $dado->categoria){
                    
                }
                @endphp
                <tr class="table_td">
                    <td>{{ $dado->tipo_mov }}</td>
                    <td style="width: 90px;" class="text-left">{{ \Carbon\Carbon::parse($dado->dt)->tz('America/Sao_Paulo')->format('d/m/Y') }}</td>
                    <td style="width: 200px;" class="text-left">{{ $dado->descricao }}</td>
                    <td style="width: 60px;" class="text-left">{{ $dado->dcto }}</td>
                    <td style="width: 70px;" class="text-left">{{ $dado->categoria }}</td>
                    <td style="width: 70px;" class="text-left">{{ $dado->classif }}</td>
                    <td style="width: 90px;" class="text-left" nowrap>{{ $dado->formanome }}</td>
                    <td style="width: 90px;" class="text-right" nowrap>{{ $dado->conta }}</td>
                    <td style="width: 90px;" class="text-right">{{ number_format($dado->recebido, 2, ',', '.') }}</td>
                    <td style="width: 90px;" class="text-right">{{ number_format($dado->pago, 2, ',', '.') }}</td>
                </tr>

                @php 
                $catAnterior = trim($dado->categoria);
                $totrecebido += $dado->recebido;
                $totpago     += $dado->pago;

                @endphp

                @empty
                    <div class="alert alert-danger" role="alert">Nenhum movimento encontrado!</div>

                @endforelse
            </tbody>
        </table>
    <!-- </div> -->
</div>

@endsection
