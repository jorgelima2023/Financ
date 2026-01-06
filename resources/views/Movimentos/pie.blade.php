@extends('layouts.admin')

@section('content')
<div class="container">

    <h4>Gráfico de Movimentos</h4>

    <p>
        <strong>Filtros:</strong>
        Descrição: {{ $descricao ?? '-' }}
        Tipo: {{ $tipo_filtro ?? '-' }}
        Período: {{ $data_de ?? '-' }} até {{ $data_ate ?? '-' }}
    </p>

    <canvas id="pieChart"></canvas>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('pieChart');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($labels),
            datasets: [{
                data: @json($values),
                backgroundColor: [
                    '#4CAF50',
                    '#FF9800',
                    '#2196F3',
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
