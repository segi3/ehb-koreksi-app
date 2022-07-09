var selected_jadwal_ujian_id = ''
const base_url = window.location.origin

$('#paket-selector').on('change', function () {
    selected_jadwal_ujian_id = $(this).find(":selected").val()

    var body = {
        "test": "test text asdasd",
        "jadwal_ujian_id": selected_jadwal_ujian_id
    }

    $('#download-form').attr('action', base_url + '/export/agregasi-ujian/' + selected_jadwal_ujian_id);

    $.ajax({
        type: 'POST',
        data: JSON.stringify(body),
        contentType: "application/json",
        dataType: "json",
        url: base_url + '/api/agregasi/is-already-corrected',
        success: (data) => {
            console.log(data.data)

            if (!data.data.length > 0) {
                GetHasilAgregasi(body)
                $('#agregasi-alert').hide()
                $("#download-button").prop("disabled", false)
            } else {
                var ujian_datatable = $('#agregasi_table').DataTable({
                    retrieve: true
                })

                ujian_datatable.clear()
                ujian_datatable.draw()
                $('#agregasi-alert').hide();
                $('#agregasi-alert').html('Data belum dikoreksi semua!')
                setTimeout(function() {
                    $('#agregasi-alert').show();
                }, 200);

                $('#graph-container').hide()
                $("#download-button").prop("disabled", true)
            }

        },
        error: (err) => {
            console.log(err)
        }
    })


});

$('#download-button').on('click', () => {
    selected_jadwal_ujian_id = $('#select-paket').find(':selected').val()

    $.ajax({
        type: 'GET',
        url: base_url + '/export/agregasi-ujian/' + selected_jadwal_ujian_id,
        success: (data) => {

        },
        error: (err) => {
            console.log(err)
        }
    })
})

const GetHasilAgregasi = async (body) => {

    console.log('gethasilagregasi')

    var ujian_datatable = $('#agregasi_table').DataTable({
        retrieve: true
    })

    $.ajax({
        type: 'GET',
        contentType: "application/json",
        dataType: "json",
        url: base_url + '/api/is-any-onprogress',
        success: (data) => {

            // console.log(data.data.on_progress)

            if (data.data.on_progress) {
                $("#koreksi-button").prop("disabled", true)

                $('#koreksi-alert').hide();
                $('#koreksi-alert').html('Sedang melakukan koreksi pada ' + data.data.nama.slice(0, -3))
                setTimeout(function () {
                    $('#koreksi-alert').show();
                }, 200);

                UpdateTable(body) // tetep tampilkna data

            } else {
                $("#koreksi-button").prop("disabled", false)
                $('#koreksi-alert').hide();
                UpdateTable(body)
            }

        },
        error: (err) => {
            console.log(err)
        }
    })
}

const UpdateTable = async (body) => {

    console.log('update table')

    var ujian_datatable = $('#agregasi_table').DataTable({
        retrieve: true
    })

    ujian_datatable.clear()
    ujian_datatable.draw()

    var row = document.createElement("tr");
    var msg = document.createElement('td');
    msg.innerHTML = 'Loading ///'
    msg.setAttribute("colspan", "100")
    msg.setAttribute('style', 'text-align:center;')
    row.append(msg)
    $('#ujian_siswa_table').append(row);

    // -----

    $.ajax({
        type: 'POST',
        data: JSON.stringify(body),
        contentType: "application/json",
        dataType: "json",
        url: base_url + '/api/agregasi',
        success: (data) => {
            console.log(data.data.data)
            // LoadUjianSiswaTableData(data.data)

            ujian_datatable.rows.add(data.data.data)
            ujian_datatable.draw()

            const result = PrepareChartData(data.data)

            NewAverageChart(result.chart.chart_labels, result.chart.chart_data)
            NewPredikatChart(result.pie.pie_labels, result.pie.pie_data)

        },
        error: (err) => {
            console.log(err)
        }
    })
}

function GetStandardDeviation(array) {
    const n = array.length
    const mean = array.reduce((a, b) => a + b) / n
    return Math.sqrt(array.map(x => Math.pow(x - mean, 2)).reduce((a, b) => a + b) / n)
}

const PrepareChartData = (raw) => {
    var generalData = raw
    var detailsData = raw.data

    var labels = []
    var labels_avg = {}

    // group data per rayon
    detailsData.forEach(el => {

        var rayon = el.rayon_nama

        if (labels.indexOf(el.rayon_nama) === -1) {
            labels.push(el.rayon_nama)
            labels_avg[rayon] = []
        }

        labels_avg[rayon].push(el.avg)
    });

    // cari avg per rayon
    labels = []
    var averages = []

    for (let key in labels_avg) {
        let total = 0
        for (var i = 0; i < labels_avg[key].length; i++) {
            total += labels_avg[key][i]
        }
        const avg = total / labels_avg[key].length;

        labels.push(key)
        averages.push(avg)
    }

    // sd, min, max
    const standar_deviasi = GetStandardDeviation(averages)
    const total_min = generalData.total_min
    const total_max = generalData.total_max

    // pie data
    var pie_labels = []
    var pie_data = []

    for (let key in generalData.predikat) {
        if (key == 'n') continue
        pie_labels.push(key)
        pie_data.push(generalData.predikat[key])
    }

    // final object
    const res = {
        chart: {
            chart_labels: labels,
            chart_data: averages,
        },
        pie: {
            pie_labels: pie_labels,
            pie_data: pie_data
        }
    }

    return res
}
