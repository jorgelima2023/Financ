@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Botão / Link -->
    <a href="{{ route('Movimentos.grafico') }}" id="loadChart" class="btn btn-primary mb-4">
        Carregar Gráfico
    </a>

    <!-- Card do gráfico -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Vendas por Produto
            </h6>
        </div>
        <div class="card-body">
            <canvas id="pieChart"></canvas>
        </div>
    </div>

</div>
@endsection

@section('scripts')

<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

<script>
    let pieChart = null;

    document.getElementById('loadChart').addEventListener('click', function (e) {
        e.preventDefault();

        fetch('{{ route("chart.data") }}')
            .then(response => response.json())
            .then(data => {

                const ctx = document.getElementById('pieChart');

                // Destroi o gráfico anterior (evita bug)
                if (pieChart) {
                    pieChart.destroy();
                }

                pieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: [
                                '#4e73df',
                                '#1cc88a',
                                '#36b9cc'
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

            })
            .catch(error => console.error('Erro ao carregar gráfico:', error));
    });
</script>

@endsection
