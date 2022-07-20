var selected_kd_rayon = 'semua'
const base_url = window.location.origin

$('#rayon-selector').on('change', function () {
    selected_kd_rayon = $(this).find(":selected").val()
    nama_rayon = $(this).find(":selected").text()

    // $('#download-form').attr('action', base_url + '/export/agregasi-ujian/' + selected_jadwal_ujian_id);

    // console.log(selected_kd_rayon)

    if (selected_kd_rayon == 'semua') {
        $('#rayon-detail-button').html('Agregasi per sekolah rayon')
        $("#rayon-detail-button").prop("disabled", true)
    } else {
        $('#rayon-detail-button').html('Agregasi per sekolah rayon ' + nama_rayon)
        $("#rayon-detail-button").prop("disabled", false)
        $('#rayon-detail-button').attr('href', base_url + '/agregasi-hasil/rayon/' + selected_kd_rayon);
    }

    $('#loading-alert').show()
    GetHasilAgregasi(selected_kd_rayon)
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

$('#rayon-detail-button').on('click', () => {
    window.location.href = base_url + '/agregasi-hasil/rayon/' + selected_kd_rayon;
})

const GetHasilAgregasi = async (selected_kd_rayon) => {

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

                UpdateTable(selected_kd_rayon) // tetep tampilkna data

            } else {
                $("#koreksi-button").prop("disabled", false)
                $('#koreksi-alert').hide();
                UpdateTable(selected_kd_rayon)
            }

        },
        error: (err) => {
            console.log(err)
        }
    })
}

const UpdateTable = async (selected_kd_rayon) => {

    // console.log('update table')

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
    $('#agregasi_table').append(row);

    // -----

    $.ajax({
        type: 'GET',
        url: base_url + '/api/agregasi/rayon/' + selected_kd_rayon,
        success: (data) => {

            ujian_datatable.rows.add(data.data)
            ujian_datatable.draw()

            if (selected_kd_rayon != 'semua') { // karena semua di grup by rayon
                const res = PrepareStackedBarData(data.data)
                NewStackedAgregasiBar(res.min, res.mean, res.max, res.label)
                $('#loading-alert').hide()
            } else {
                $.ajax({
                    type: 'GET',
                    url: base_url + '/api/agregasi/rayon/semua/nokd',
                    success: (data) => {

                        // ujian_datatable.rows.add(data.data)
                        // ujian_datatable.draw()

                        const res = PrepareStackedBarData(data.data)
                        NewStackedAgregasiBar(res.min, res.mean, res.max, res.label)
                        $('#loading-alert').hide()
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

const PrepareStackedBarData = (data) => {

    // sorty by name
    data.sort((a, b) => {
        return a.nama.localeCompare(b.nama)
    })

    // console.log(data)

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

    // console.log(res)

    return res
}
