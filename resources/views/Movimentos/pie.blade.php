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
                    Movimentos - Filtros:Descrição: {{ $descricao ?? '-' }}
                    Tipo: {{ $tipo_filtro ?? '-' }}
                    Período: {{ \Carbon\Carbon::parse($data_de)->tz('America/Sao_Paulo')->format('d/m/Y') ?? '-' }} até {{ \Carbon\Carbon::parse($data_ate)->tz('America/Sao_Paulo')->format('d/m/Y') ?? '-' }}
                </div>

            </div>
        </div>
    </div>
</div>

<!-- <div class="container">

    <h4>Gráfico de Movimentos</h4>

    <p>
        <strong>Filtros:</strong>
        Descrição: {{ $descricao ?? '-' }}
        Tipo: {{ $tipo_filtro ?? '-' }}
        Período: {{ $data_de ?? '-' }} até {{ $data_ate ?? '-' }}
    </p>

    <canvas id="pieChart" width="30" height="10"></canvas>

</div> -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('pieChart');
    // type: 'pie'  type: 'bar'
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($labels),
            datasets: [{
                data: @json($values),
                backgroundColor: [
                    '#4CAF50',
                    '#FF9800',
                    '#FF7800',
                    '#FF5800',
                    '#2196F3',
                    '#2176F3',
                    '#2156F3',
                    '#651ee9ff',
                    '#E91E63'
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
