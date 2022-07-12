var selected_pelajaran = ''
var paket_obj = {}
const base_url = window.location.origin

$('#pelajaran-selector').on('change', () => {
    selected_pelajaran = $('#select-pelajaran').find(":selected").val()

    console.log('selected ' + selected_pelajaran)

    $('#loading-alert').show()

    GetMutuKisi(selected_pelajaran)
})

$('#paket-pel-selector').on('change', () => {
    var aggkisi_datatable = $('#agregasi-kisi-table').DataTable({
        retrieve: true
    })

    selected_paket_pel = $('#select-paket-pel').find(":selected").val()

    console.log(selected_paket_pel)
    InsertPieCharts(paket_obj[selected_paket_pel])
})

const UpdatePaketSelectorOption = () => {
    $("option[value='paket_1']").remove()
    $("option[value='paket_2']").remove()
    $("option[value='paket_3']").remove()

    Object.keys(paket_obj).forEach(key => {
        var option = document.createElement("option")
        option.value = key
        option.innerHTML = key
        $('#select-paket-pel').append(option);
    });
}

// https://stackoverflow.com/questions/57645062/create-multiple-chart-js-charts-from-jsonfile-data

const GetMutuKisi = (pelajaran) => {

    $.ajax({
        type: 'GET',
        url: base_url + '/api/agregasi/kisi/' + pelajaran,
        success: (data) => {
            console.log(data.data)

            if (data.data.length == 0) {
                // TODO zero condition

                $('#loading-alert').hide()
                return
            }

            var paketExists = [];
            paket_obj = {};

            data.data.forEach(el => {
                let paket = 'paket_' + String(el.paket)
                if (paketExists.includes(el.paket)) {
                    paket_obj[paket].push(el)
                } else {
                    paketExists.push(el.paket)
                    paket_obj[paket] = []
                    paket_obj[paket].push(el)
                }
            });
            UpdatePaketSelectorOption()

            if (paket_obj.paket_1 != null) { // case paket yang tidak ada paket_1
                $('#select-paket-pel').val('paket_1')
                InsertPieCharts(paket_obj.paket_1)
            } else {
                const key = Object.keys(paket_obj)[0]
                $('#select-paket-pel').val(key)
                InsertPieCharts(paket_obj[key])
            }



        },
        error: (err) => {
            console.log(err)
        }
    })
}

const deletePieCharts = () => {
    $('#graph-container').empty()
}

const InsertPieCharts = (paket_data) => {

    deletePieCharts()

    var canvasArray = []
    $('#loading-alert').show()

    for(let i=0; i<paket_data.length; i++) {
        let id_ = 'ctx' + i

        let canvas = document.createElement('canvas')
        canvas.id = id_

        pred = ''
        if (paket_data[i].rata_rata > 0.85) {
            pred = 'Sangat Baik'
        } else if (paket_data[i].rata_rata > 0.7) {
            pred = 'Baik'
        } else if (paket_data[i].rata_rata > 0.55) {
            pred = 'Cukup'
        } else {
            pred = 'Kurang'
        }

        $('#graph-container').append(
            $('<div/>', {class: 'card col-lg-6'}).append(
                $('<div/>', {class: 'card-body'}).append(
                    $('<div/>', {class: 'row'})
                        .append(
                            $('<div/>', {class: 'col-lg-5 graph-size'})
                                .append(
                                    $('<canvas/>', {id: id_})
                                )
                        )
                        .append(
                            $('<div/>', {class: 'col-lg-7'})
                                .append($('<div/>', {class: 'mt-4'})
                                    .append($('<b/>', {text: 'Soal Nomor  '}))
                                    .append($('<span/>', {text: paket_data[i].no_soal}))
                                )
                                .append($('<div/>', {class: 'mb-3'})
                                    .append($('<b/>', {text: 'Tipe Soal  '}))
                                    .append($('<span/>', {text: paket_data[i].tipe_soal}))
                                )
                                .append($('<div/>', {})
                                    .append($('<b/>', {text: 'KD'}))
                                    .append($('<p/>', {text: paket_data[i].kd}))
                                )
                                .append($('<div/>', {})
                                    .append($('<b/>', {text: 'IBS'}))
                                    .append($('<p/>', {text: paket_data[i].ibs}))
                                )
                                .append($('<div/>', {class: 'row'})
                                    .append($('<div/>', {class: 'col-6'})
                                        .append($('<b/>', {text: 'Rata-rata benar'}))
                                        .append($('<p/>', {text: formatTwoDecimalPlace(paket_data[i].rata_rata)}))
                                    )
                                    .append($('<div/>', {class: 'col-6'})
                                        .append($('<b/>', {text: 'Predikat'}))
                                        .append($('<p/>', {text: pred}))
                                    )
                                )
                        )
                )
            )
        )

        // ---- create chart

        const ctx = document.getElementById('ctx' + i).getContext('2d')

        const config = {
            type: 'pie',
            data: {
                labels: [
                    'Jumlah Benar', 'Jumlah Salah', 'Jumlah Kosong'
                ],
                datasets: [{
                    label: 'perbandingan jawaban siswa',
                    data: [
                        paket_data[i].jumlah_benar, paket_data[i].jumlah_salah, paket_data[i].jumlah_kosong
                    ],
                    backgroundColor: [
                        'rgb(44,229,116)',
                        'rgb(255,57,36)',
                        'rgb(58, 68, 84)',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
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
        }

        const myChart = new Chart(ctx, config)

        // break
    }
    $('#loading-alert').hide()
}

const GenerateCardWrapper = () => {


    return card
}
