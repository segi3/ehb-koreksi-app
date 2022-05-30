const annotation1 = {
    type: 'line',
    borderColor: 'rgb(100, 149, 237)',
    borderDash: [6, 6],
    borderDashOffset: 0,
    borderWidth: 3,
    label: {
        enabled: true,
        backgroundColor: 'rgb(100, 149, 237)',
        content: (ctx) => 'Rata-rata total: ' + average(ctx).toFixed(2)
    },
    scaleID: 'y',
    value: (ctx) => average(ctx)
};

const annotation2 = {
    type: 'line',
    borderColor: 'rgba(102, 102, 102, 0.5)',
    borderDash: [6, 6],
    borderDashOffset: 0,
    borderWidth: 3,
    label: {
        enabled: true,
        backgroundColor: 'rgba(102, 102, 102, 0.5)',
        color: 'black',
        content: (ctx) => (average(ctx) + standardDeviation(ctx)).toFixed(2),
        position: 'start',
        rotation: -90,
        yAdjust: -28
    },
    scaleID: 'y',
    value: (ctx) => average(ctx) + standardDeviation(ctx)
};

const annotation3 = {
    type: 'line',
    borderColor: 'rgba(102, 102, 102, 0.5)',
    borderDash: [6, 6],
    borderDashOffset: 0,
    borderWidth: 3,
    label: {
        enabled: true,
        backgroundColor: 'rgba(102, 102, 102, 0.5)',
        color: 'black',
        content: (ctx) => (average(ctx) - standardDeviation(ctx)).toFixed(2),
        position: 'end',
        rotation: 90,
        yAdjust: 28
    },
    scaleID: 'y',
    value: (ctx) => average(ctx) - standardDeviation(ctx)
};

function average(ctx) {
    const values = ctx.chart.data.datasets[0].data;
    return values.reduce((a, b) => a + b, 0) / values.length;
}

function standardDeviation(ctx) {
    const values = ctx.chart.data.datasets[0].data;
    const n = values.length;
    const mean = average(ctx);
    return Math.sqrt(values.map(x => Math.pow(x - mean, 2)).reduce((a, b) => a + b) / n);
}

var placeholder_data = {
    labels: ["", "", "", "", "", "", ""],
    datasets: [{
        label: "",
        fillColor: "rgba(220,220,220,0.0)",
        strokeColor: "rgba(220,220,220,0)",
        pointColor: "rgba(220,220,220,0)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        // change this data values according to the vertical scale
        // you are looking for
        data: [0, 0, 0, 0, 0, 0, 0]
    }]
}


function ResetChartCanvas() {
    $('#agregasichart').remove()
    $('#chart-container').append(`
        <canvas id="agregasichart"></canvas>
    `)
}

function NewAverageChart(labels, data) {
    ResetChartCanvas()
    const ctx = document.getElementById('agregasichart').getContext('2d');
    const config = {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rata-rata per rayon',
                data: data,
                fill: false,
                borderColor: "rgba(220,220,220,1)",
                backgroundColor: "rgba(220,220,220,1)",
                borderWidth: 1,
                pointStyle: 'rectRot',
                pointRadius: 5,
                pointBorderColor: 'rgb(255, 0, 0)'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                annotation: {
                    annotations: {
                        annotation1,
                        annotation2,
                        annotation3
                    }
                }
            }
        }
    };

    const myChart = new Chart(ctx, config);
};

function ResetPieCanvas() {
    $('#piechart').remove()
    $('#pie-container').append(`
        <canvas id="piechart"></canvas>
    `)
}


function NewPredikatChart(labels, data) {
    $('#graph-container').show()
    ResetPieCanvas()
    const ctx = document.getElementById('piechart').getContext('2d');
    const config = {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rata-rata per rayon',
                data: data,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(69, 54, 86)',
                    'rgb(96, 33, 86)'
                ],
                hoverOffset: 4
            }]
        }
    };

    const myChart = new Chart(ctx, config);
};
