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
<div class="table-responsive">
<!-- <div class="mb-1 hstack gap-2"> -->
        <h4 class="mt-3">Movimento - Relatório Balancete</h2>
        
    <!-- </div> -->
    <!-- <div class="card mb-1 border-light"> -->
        <!-- <div class="card-header border border-light-subtle"> -->
        <div>    
            <span class="pesquisar">Filtros: {{ $sel }}</span>
            
        </div>
    <!-- </div> -->
    
    <!-- Listar -->
    <!-- <div class="card mb-4 border-light shadow"> -->
        <!-- <div class="card-header hstack gap-2"> -->
            <span class="pesquisar">
                <x-alert />
            </span>
            
        <!-- </div> -->
    <!-- </div> -->
</div>

<div class="table-responsive table-container2">
    <table class="table table-sm" style="width: 100%;">
        <thead>
            <tr style="background-color: #2c3e50; color: white;">
                <th>Classif.</th>
                <th>Nome</th>
                <th style="text-align: right;">Valor</th>
            </tr>
        </thead>
        <tbody>
            @php
            $cor = 0;
            @endphp

            @foreach($movimentos as $movimento)

                @php
                $qtde_ocorrencias = "px-" . substr_count($movimento->classif, ".") *2 ;
                $qtde_ocorr       = substr_count($movimento->classif, ".");

                if ($cor == 0) { @endphp
                            <tr class="movimento1">
                @php } @endphp

                @php
                // if ($cor == 1)
                if(( $qtde_ocorr == 1 ) || ( $qtde_ocorr == 2 ))
                { @endphp
                            <!-- <tr class="movimento1, zebrado"> -->
                            <!-- <tr class="movimento1" style="background-color: #D3D3D3;"> -->
                            <tr class="movimento1" style="background-color: #DDD;">    

                @php } @endphp

                <td class="{{ $qtde_ocorrencias }}" style="text-align: left;">{{ trim($movimento->classif) }}</td>
                <td class="{{ $qtde_ocorrencias }}" style="text-align: left;">{{ $movimento->nome }}</td>
                <td style="text-align: right;">{{ number_format($movimento->total, 2, ',', '.') }}</td>
                </tr>

                @php
                    //if( $qtde_ocorr == 1 ){
                    //    $cor = $cor == 0 ? 1 : 0;
                    //}
                @endphp
                
            @endforeach
        
        </tbody>
        <tfoot>
            
        </tfoot>
    </table>
</div>



@endsection
