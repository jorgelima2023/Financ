<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pie Chart Dinâmico</h6>
    </div>
    <div class="card-body">
        <div class="chart-pie pt-4 pb-2">
            <canvas id="myPieChart"></canvas>
        </div>
        <div id="pieLegend" class="mt-4 text-center small"></div>
    </div>
</div>

<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

<script>
async function loadPieChart() {
    const response = await fetch('/pie-data'); // rota Laravel
    const data = await response.json();

    var ctx = document.getElementById("myPieChart");

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                backgroundColor: data.colors,
                hoverBackgroundColor: data.colors,
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            legend: { display: false },
            cutout: '70%'
        }
    });

    // Legenda dinâmica
    let legendHTML = "";
    data.labels.forEach((label, i) => {
        legendHTML += `
            <span class="mr-2">
                <i class="fas fa-circle" style="color:${data.colors[i]}"></i>
                ${label}
            </span>
        `;
    });

    document.getElementById("pieLegend").innerHTML = legendHTML;
}

loadPieChart();
</script>

