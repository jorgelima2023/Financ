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
            <span class="pesquisar">
                <x-alert />
            </span>
            
        <!-- </div> -->
    <!-- </div> -->
</div>

<div class="table-responsive table-container2">
    <table class="table table-sm" style="width: 100%;">

        <thead>
            @php
            if ($OmitirData == "N") { @endphp
                <tr style="background-color: #2c3e50; color: white;">
                    <th>Dcto</th>
                    <th>Descrição</th>
                    <th style="text-align: right;">Recebido</th>
                    <th style="text-align: right;">Pago</th>
                </tr>
            @php } @endphp

            @php
            if ($OmitirData == "S") { @endphp
                @php
                if ($mesAno == "N") { @endphp
                    <tr style="background-color: #2c3e50; color: white;">
                        <th style="text-align: left;">Categoria</th>
                        <th style="text-align: left;">Data</th>
                        <th style="text-align: left;">Dcto</th>
                        <th style="text-align: left;">Descrição</th>
                        <th style="text-align: right;">Recebido</th>
                        <th style="text-align: right;">Pago</th>
                    </tr>
                @php } @endphp

                @php
                if ($mesAno == "S") { @endphp
                    <tr style="background-color: #2c3e50; color: white;">
                        <th style="text-align: left;">Categoria</th>
                        <th style="text-align: left;">Mês/Ano</th>
                        <th style="text-align: right;">Recebido</th>
                        <th style="text-align: right;">Pago</th>
                        <th colspan="6"></th>
                    </tr>
                @php } @endphp

            @php } @endphp
        </thead>
        <tbody>
            @php
            if ($OmitirData == "N") { @endphp

                @foreach($movimentosAgrupados as $data => $categorias)

                    <!-- ====== 1. LINHA DE DATA ====== -->
                    <tr style="background-color: #e8f4f8; font-weight: bold;">
                        <td colspan="4">📅 Data: {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}</td>
                    </tr>

                    @foreach($categorias as $nomeCategoria => $listaMovimentos)
                        
                        <!-- ====== 2. LINHA DE CATEGORIA ====== -->
                        <tr style="background-color: #fcfcfc; font-style: italic; color: #2c3e50; font-weight: bold;">
                            <td colspan="4" style="padding-left: 15px;">🏷️ Categoria: {{ $listaMovimentos->first()->classif }} - {{ $nomeCategoria }}</td>
                        </tr>

                        <!-- ====== 3. DETALHES DOS MOVIMENTOS ====== -->
                        @foreach($listaMovimentos as $movimento)
                            <tr class="movimento1">
                                <td style="padding-left: 25px;">{{ $movimento->dcto ?? '-' }}</td>
                                <td>{{ $movimento->descricao ?? 'Sem descrição' }}</td>
                                <td style="text-align: right;">{{ $movimento->tipo == 1 ? number_format($movimento->valor, 2, ',', '.') : '0,00' }}</td>
                                <td style="text-align: right;">{{ $movimento->tipo == 0 ? number_format($movimento->valor, 2, ',', '.') : '0,00' }}</td>
                            </tr>
                        @endforeach

                        <!-- 💰 SUBTOTAL DA CATEGORIA (Dentro deste dia) -->
                        <tr style="background-color: #fafafa;">
                            <td colspan="2" style="text-align: right; font-size: 0.9em; color: #555;">
                                Subtotal ({{ $nomeCategoria }}):
                            </td>
                            <!-- <td style="border-top: 1px solid #ddd; font-weight: bold;"> -->
                            <!-- <td style="text-align: right;">     -->
                                {{-- number_format($listaMovimentos->sum('valor'), 2, ',', '.') --}}
                            <!-- </td> -->

                            <td style="text-align: right; font-weight: bold;">{{ $movimento->tipo == 1 ? number_format($listaMovimentos->sum('valor'), 2, ',', '.') : '0,00' }}</td>
                            <td style="text-align: right; font-weight: bold;">{{ $movimento->tipo == 0 ? number_format($listaMovimentos->sum('valor'), 2, ',', '.') : '0,00' }}</td>

                        </tr>

                    @endforeach

                    <!-- 💵 TOTAL DO DIA (Soma de todas as categorias daquela data) -->
                    <tr style="background-color: #dbeeff; font-weight: bold;">
                        <td colspan="2" style="text-align: right; padding-right: 15px;">
                            TOTAL DO DIA {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}:
                        </td>
                        <td>
                            <u>{{ number_format($categorias->collapse()->sum('valor'), 2, ',', '.') }}</u>
                        </td>
                        
                    </tr>

                @endforeach
            @php } @endphp

            @php
            if ($OmitirData == "S") { @endphp
                
                @php                
                if ($mesAno == "N") { @endphp
                    @foreach($movimentosAgrupados as $classif => $listaMovimentos)
                        
                        @foreach($listaMovimentos as $movimento)
                            <tr class="movimento1">
                                <td>{{ $classif }} - {{ $listaMovimentos->first()->nome }}</td>

                                @php
                                if ($mesAno == "N") { @endphp
                                    <td>{{ \Carbon\Carbon::parse($movimento->dt)->format('d/m/Y') }}</td>
                                @php } @endphp

                                @php
                                if ($mesAno == "S") { @endphp
                                    <td>{{ $movimento->mes_ano }}</td>
                                @php } @endphp

                                @php
                                if ($mesAno == "N") { @endphp
                                    <td >{{ $movimento->dcto}}</td>
                                    <td >{{ $movimento->descricao ?? 'Sem descrição' }}</td>
                                @php } @endphp

                                <td style="text-align: right;">{{ $movimento->tipo == 1 ? number_format($movimento->valor, 2, ',', '.') : '0,00' }}</td>
                                <td style="text-align: right;">{{ $movimento->tipo == 0 ? number_format($movimento->valor, 2, ',', '.') : '0,00' }}</td>
                            </tr>
                        @endforeach
                        
                        <tr class="subTotal" style="text-align: right; font-size: 0.7em; font-weight: bold;">
                            @php
                            if ($mesAno == "N") { @endphp
                                <td colspan="3"></td>
                            @php } @endphp

                            @php
                            if ($mesAno == "S") { @endphp
                                <td colspan="1"></td>
                            @php } @endphp

                            <!-- <td style="text-align: left;">
                                Subtotal: ( {{ $classif }} - {{ $listaMovimentos->first()->nome }} )
                            </td> -->

                            <td style="text-align: left;">
                                Subtotal: ( {{ $listaMovimentos->first()->ano }} )
                            </td>
                            
                            <td style="text-align: right;">{{ $movimento->tipo == 1 ? number_format($listaMovimentos->sum('valor'), 2, ',', '.') : '0,00' }}</td>
                            <td style="text-align: right;">{{ $movimento->tipo == 0 ? number_format($listaMovimentos->sum('valor'), 2, ',', '.') : '0,00' }}</td>
                        </tr>

                        <!-- <tr class="movimento1"><td colspan="6"></td>
                        </tr> -->

                    @endforeach
                @php } @endphp

                @php
                if ($mesAno == "S") { $cor = 0; @endphp
                    

                    @foreach($movimentos as $movimento)

                        @php
                        if ($cor == 0) { @endphp
                                    <tr class="movimento1">
                        @php } @endphp

                        @php
                        if ($cor == 1) { @endphp
                                    <!-- <tr class="movimento1, zebrado"> -->
                                    <!-- <tr class="movimento1" style="background-color: #D3D3D3;"> -->
                                    <tr class="movimento1" style="background-color: #DDD;">    
                        @php } @endphp

                        @php
                        if ($mesAno == "N") { @endphp

                            <!-- <tr class="movimento1">     -->
                            
                            <td>{{ $movimento->classif }} - {{ $movimento->nome }}</td>
                            <td>{{ \Carbon\Carbon::parse($movimento->dt)->format('d/m/Y') }}</td>
                            <td>{{ $movimento->dcto ?? '-' }}</td>
                            <td>{{ $movimento->descricao ?? 'Sem descrição' }}</td>
                            <td style="text-align: right;">{{ $movimento->tipo == 1 ? number_format($movimento->valor, 2, ',', '.') : '0,00' }}</td>
                            <td style="text-align: right;">{{ $movimento->tipo == 0 ? number_format($movimento->valor, 2, ',', '.') : '0,00' }}</td>
                            </tr>
                        @php } @endphp

                        @php
                        if ($mesAno == "S") { @endphp
                            <td style="text-align: left;">{{ trim($movimento->classif) }} - {{ trim($movimento->nome) }}</td>
                            <td style="text-align: left;">{{ $movimento->mes }}/{{ $movimento->ano }}</td>
                            <td style="text-align: right;">{{ $movimento->tipo == 1 ? number_format($movimento->valor, 2, ',', '.') : '0,00' }}</td>
                            <td style="text-align: right;">{{ $movimento->tipo == 0 ? number_format($movimento->valor, 2, ',', '.') : '0,00' }}</td>
                            <td colspan="6"></td>
                            
                            </tr>
                        @php } @endphp

                        @php
                            $cor = $cor == 0 ? 1 : 0;
                        @endphp
                        
                    @endforeach
                
                @php } @endphp

            @php } @endphp

        </tbody>
        <tfoot>

            @php
            if ($OmitirData == "N") { @endphp 

                <!-- 💰 total receoita x despesa -->
                <tr style="background-color: #fafafa;">
                    <td colspan="2" style="text-align: right; font-size: 0.9em; color: #555;">
                        <b>Total Recebido x Pago:</b>
                    </td>
                
                    <td style="text-align: right; font-weight: bold;">{{ number_format($totalReceita, 2, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($totalDespesa, 2, ',', '.') }}</td>

                </tr>

                <!-- 🏆 TOTAL GERAL -->
                <tr style="background-color: #27ae60; color: white; font-size: 1.1em; font-weight: bold;">
                    <td colspan="2" style="text-align: right; padding-right: 15px;">
                        📢 TOTAL GERAL DO PERÍODO:
                    </td>
                    <td>
                        R$ {{ number_format($totalGeral, 2, ',', '.') }}
                    </td>
                </tr>
            @php } @endphp

            @php
            if ($OmitirData == "S") { @endphp
                
                <tr style="background-color: #fafafa;">
                    @php                
                    if ($mesAno == "N") { @endphp
                        <td colspan="4" style="text-align: right; font-size: 0.9em; color: #555;">  
                    @php } @endphp
                    @php                
                    if ($mesAno == "S") { @endphp
                        <td colspan="2" style="text-align: right; font-size: 0.9em; color: #555;">  
                    @php } @endphp
                    
                    <!-- <td colspan="4" style="text-align: right; font-size: 0.9em; color: #555;"> -->
                        <b>Total Recebido x Pago:</b>
                    </td>
                
                    <td style="text-align: right; font-weight: bold;">{{ number_format($totalReceita, 2, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($totalDespesa, 2, ',', '.') }}</td>

                </tr>

                <tr style="background-color: #27ae60; color: white; font-size: 1.1em; font-weight: bold;">
                    @php                
                    if ($mesAno == "N") { @endphp
                        <td colspan="4" style="text-align: right; padding-right: 15px;">
                    @php } @endphp

                    @php                
                    if ($mesAno == "S") { @endphp
                        <td colspan="3" style="text-align: right; padding-right: 15px;">
                    @php } @endphp

                        TOTAL GERAL DO PERÍODO:
                    </td>
                    <td>
                        R$ {{ number_format($totalGeral, 2, ',', '.') }}
                    </td>
                </tr>
            @php } @endphp
        </tfoot>
    </table>
</div>



@endsection
