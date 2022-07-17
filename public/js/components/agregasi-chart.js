const annotation1 = {
    type: 'line',
    borderColor: 'rgb(100, 149, 237)',
    borderDash: [6, 6],
    borderDashOffset: 0,
    borderWidth: 3,
    label: {
        enabled: true,
        backgroundColor: 'rgba(201, 203, 207, 0.9)',
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
        backgroundColor: 'rgba(201, 203, 207, 0.9)',
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
        backgroundColor: 'rgba(201, 203, 207, 0.9)',
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
    const h = $('#agregasichart')
    h.height(440)
    const config = {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rata-rata per rayon',
                data: data,
                fill: false,
                borderColor: "rgba(220,220,220,1)",
                backgroundColor: 'rgb(100, 149, 237)',
                borderWidth: 1,
                pointStyle: 'rectRot',
                pointRadius: 5,
                pointBorderColor: 'rgb(255, 0, 0)'
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Rata Rata Nilai Per Rayon'
                },
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
    const h = $('#piechart')
    h.height(440)
    const config = {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rata-rata per rayon',
                data: data,
                backgroundColor: [
                    'rgb(44,229,116)',
                    'rgb(205,240,58)',
                    'rgb(255,229,0)',
                    'rgb(255,150,0)',
                    'rgb(255,57,36)'
                ],
                hoverOffset: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Jumlah Predikat Ujian'
                },
                legend: {
                    position: 'bottom'
                },
                datalabels: {
                    anchor: "end",
                    backgroundColor: function (context) {
                        return context.dataset.backgroundColor;
                    },
                    display: function (ctx) {
                        return ctx.chart.width > 256
                    },
                    borderColor: "white",
                    borderRadius: 16,
                    borderWidth: 2,
                    color: "white",
                    padding: 6,
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        let percentage = (value*100 / sum).toFixed(2)+"%";
                        return percentage;
                      },
                }
            }
        },
        plugins: [ChartDataLabels]
    };

    const myChart = new Chart(ctx, config);
};

const NewStackedAgregasiBar = (min, mean, max, label) => {
    ResetChartCanvas()

    $('#graph-container').show()
    console.log('creating graph')
    const ctx = document.getElementById('agregasichart').getContext('2d')
    const h = $('#agregasichart')
    h.height(440)

    const labels = label;
    const data = {
        labels: labels,
        datasets: [{
                label: 'Terendah',
                data: min,
                backgroundColor: 'rgb(255,57,36)',
            },
            {
                label: 'Rata-Rata',
                data: mean,
                backgroundColor: 'rgb(117, 221, 221)',
            },
            {
                label: 'Tertinggi',
                data: max,
                backgroundColor: 'rgb(44,229,116)',
            },
        ]
    };
    const config = {
        type: 'bar',
        data: data,
        options: {
            maintainAspectRatio: false,
            plugins: {
                 tooltip: {
                    displayColors: false,
                    callbacks: {
                        label: (context) => {

                            let nmin = parseFloat(min[context.dataIndex])
                            let nmean = parseFloat(mean[context.dataIndex]) + parseFloat(min[context.dataIndex])
                            let nmax = parseFloat(max[context.dataIndex]) + parseFloat(mean[context.dataIndex]) + parseFloat(min[context.dataIndex])

                            // console.log({label: labels[context.dataIndex],
                            //     min: parseFloat(min[context.dataIndex]),
                            //     mean: parseFloat(mean[context.dataIndex]) + parseFloat(min[context.dataIndex]),
                            //     max: parseFloat(max[context.dataIndex]) + parseFloat(mean[context.dataIndex]) + parseFloat(min[context.dataIndex])})

                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                            }
                            return [
                                'Tertinggi: ' + nmax,
                                'Terendah: ' + nmin,
                                'Rata-rata: ' + nmean
                            ]
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Agregasi Hasil Ujian'
                },
            },
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true
                }
            }
        }
    };
    const myChart = new Chart(ctx, config);
}
