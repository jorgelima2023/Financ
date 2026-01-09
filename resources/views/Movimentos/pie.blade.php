@extends('layouts.admin')

@section('content')

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Pie Chart 
                </div>
                <div class="card-body"><canvas id="pieChart" width="150%" height="60"></canvas></div>
                <div class="card-footer small text-muted">
                    <strong>Movimentos</strong> - Filtros:Descrição: {{ $descricao ?? '-' }}
                    Tipo: {{ $tipo_filtro ?? '-' }}
                    Período: {{ \Carbon\Carbon::parse($data_de)->tz('America/Sao_Paulo')->format('d/m/Y') ?? '-' }} até {{ \Carbon\Carbon::parse($data_ate)->tz('America/Sao_Paulo')->format('d/m/Y') ?? '-' }}
                </div>

            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Pie Chart - Valores
                </div>
                <div class="d-grid">
                    <div class="card-body">
                        @forelse ($dados as $dado)
                            <div class="d-flex align-items-center justify-content-between text-xs">
                            <div class="fw-500">{{ $dado->nome }}</div>
                            <div class="text-body">{{ number_format( $dado->total , 2, ',', '.') }}</div>
                        </div>
                        @empty
                            <div class="d-flex align-items-center justify-content-between text-xs">
                                <div class="fw-500">Nenhum movimento encontrado!</div>
                        @endforelse
                        
                        <!-- <div class="d-flex align-items-center justify-content-between text-xs">
                            <div class="fw-500">Debito Cartão</div>
                            <div class="text-body">19.450,00</div>
                        </div>    
                        <div class="d-flex align-items-center justify-content-between text-xs">
                            <div class="fw-500">Supermercado</div>
                            <div class="text-body">856,39</div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between text-xs">
                            <div class="fw-500">Credito Visa</div>
                            <div class="text-body">1.945,00</div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between text-xs">
                            <div class="fw-500">Internet</div>
                            <div class="text-body">253,68</div>
                        </div> -->
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<script>
    const ctx = document.getElementById('pieChart');

    // backgroundColor: [
    //                 '#4CAF50',
    //                 '#FF9800',
    //                 '#FF7800',
    //                 '#FF5800',
    //                 '#2196F3',
    //                 '#2176F3',
    //                 '#2156F3',
    //                 '#651ee9ff',
    //                 '#E91E63'
    //             ]

    // type: 'pie'  type: 'bar' 'doughnut' 'outlabeledPie'
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: @json($labels),
            datasets: [{
                data: @json($values),
                backgroundColor: [
                    '#006400',
                    '#0000CD',
                    '#FF0000',
                    '#8B4513',
                    '#00BFFF',
                    '#FFD700',
                    '#651ee9ff',
                    '#00FF00',
                    '#8B008B',
                    '#FF5800',
                    '#4682B4',
                    '#40E0D0'
                    
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
