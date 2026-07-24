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
            <tr style="background-color: #2c3e50; color: white;">
                <th>Dcto</th>
                <th>Descrição</th>
                <th style="text-align: right;">Recebido</th>
                <th style="text-align: right;">Pago</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach($movimentosAgrupados as $data => $categorias)
                
                <!-- ====== 1. LINHA DE DATA ====== -->
                <tr style="background-color: #e8f4f8; font-weight: bold;">
                    <td colspan="4">📅 Data: {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}</td>
                </tr>

                @foreach($categorias as $nomeCategoria => $listaMovimentos)
                    
                    <!-- ====== 2. LINHA DE CATEGORIA ====== -->
                    <tr style="background-color: #fcfcfc; font-style: italic; color: #2c3e50; font-weight: bold;">
                        <td colspan="4" style="padding-left: 15px;">🏷️ Categoria: {{ $nomeCategoria }} - {{ $listaMovimentos->first()->classif }} </td>
                    </tr>

                    <!-- ====== 3. DETALHES DOS MOVIMENTOS ====== -->
                    @foreach($listaMovimentos as $movimento)
                        <tr class="movimento1">
                            <!-- Coluna Documento (dcto) -->
                            <td style="padding-left: 25px;">{{ $movimento->dcto ?? '-' }}</td>
                            <!-- Coluna Descrição -->
                            <td>{{ $movimento->descricao ?? 'Sem descrição' }}</td>
                            <td style="text-align: right;">{{ $movimento->tipo == 1 ? number_format($movimento->valor, 2, ',', '.') : '0,00' }}</td>
                            <!-- Coluna Valor -->
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
        </tbody>
        <tfoot>

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
        </tfoot>
    </table>
<!-- </div> -->



@endsection
