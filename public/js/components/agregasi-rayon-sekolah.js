var selected_sekolah = 'semua'
const base_url = window.location.origin

$('#sekolah-selector').on('change', function () {
    selected_sekolah = $(this).find(":selected").val()

    // $('#download-form').attr('action', base_url + '/export/agregasi-ujian/' + selected_jadwal_ujian_id);

    console.log(selected_sekolah)

    GetHasilAgregasi(selected_sekolah)

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

const GetHasilAgregasi = async (selected_sekolah) => {

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

                UpdateTable(selected_sekolah) // tetep tampilkna data

            } else {
                $("#koreksi-button").prop("disabled", false)
                $('#koreksi-alert').hide();
                UpdateTable(selected_sekolah)
            }

        },
        error: (err) => {
            console.log(err)
        }
    })
}

const UpdateTable = async (selected_sekolah) => {

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
        type: 'GET',
        url: base_url + '/api/agregasi/rayon/'+ KD_RAYON +'/sekolah/' + selected_sekolah,
        success: (data) => {

            ujian_datatable.rows.add(data.data)
            ujian_datatable.draw()

            if (selected_sekolah != 'semua') { // karena semua di grup by rayon
                const res = PrepareStackedBarData(data.data)
                NewStackedAgregasiBar(res.min, res.mean, res.max, res.label)
            } else {
                $.ajax({
                    type: 'GET',
                    url: base_url + '/api/agregasi/rayon/'+ KD_RAYON +'/sekolah/semua/nosk',
                    success: (data) => {

                        ujian_datatable.rows.add(data.data)
                        ujian_datatable.draw()

                        const res = PrepareStackedBarData(data.data)
                        NewStackedAgregasiBar(res.min, res.mean, res.max, res.label)
                    },
                    error: (err) => {
                        console.log(err)
                    }
                })
            }
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


const PrepareStackedBarData = (data) => {

    // sorty by name
    data.sort((a, b) => {
        return a.nama.localeCompare(b.nama)
    })

    var min = []
    var mean = []
    var max = []
    var label = []

    data.forEach(el => {
        // negative check
        let mmin = el.min < 0 ? 0 : el.min
        let mmean = el.avg < 0 ? 0 : el.avg
        let mmax = el.max < 0 ? 0 : el.max

        // initial
        // min.push(mmin)
        // mean.push(mmean)
        // max.push(mmax)
        // label.push(el.nama)

        // new (using differences betweet datasets)
        min.push(mmin)
        mean.push(mmean-mmin)
        max.push(mmax-mmean)
        label.push(el.nama)
    })

    const res = {
        min: min,
        max: max,
        mean: mean,
        label: label
    }

    return res
}
