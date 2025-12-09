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

{{-- dd('piedata.php'); --}}

<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="assets/demo/chart-pie-demo.js"></script>


    

<script>
async function loadPieChart() {
    //alert('loadpiedata...');
    const response = await fetch('/pie-data'); // rota Laravel
    const data = await response.json();
    //alert('loadpiedata1...');
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

