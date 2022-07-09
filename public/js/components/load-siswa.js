var selected_jadwal_ujian_id = ''
const base_url = window.location.origin

$('#paket-selector').on('change', function() {
    selected_jadwal_ujian_id = $(this).find(":selected").val()

    var body = {
        "test": "test text asdasd",
        "jadwal_ujian_id": selected_jadwal_ujian_id
    }

    GetSiswaAjax(body)
});

$('#koreksi-button').on('click', () => {
    selected_jadwal_ujian_id = $('#select-paket').find(':selected').val()
    console.log(base_url + '/api/koreksi/start/'+selected_jadwal_ujian_id)

    nama_ujian = $("#select-paket option[value='"+selected_jadwal_ujian_id+"']").text();
    console.log(nama_ujian)

    var ujian_datatable = $('#ujian_siswa_table').DataTable({
        retrieve: true
    })

    $.ajax({
        type: 'GET',
        contentType: "application/json",
        dataType: "json",
        url: base_url + '/api/koreksi/start/'+selected_jadwal_ujian_id,
        success: (data) => {
            console.log('masuk success')

        },
        error: (err) => {
            $("#koreksi-button").prop("disabled", true)

            ujian_datatable.clear()
            ujian_datatable.draw()

            $('#koreksi-alert').hide();
            $('#koreksi-alert').html('Sedang melakukan koreksi pada ' + nama_ujian.slice(0, -3))
            setTimeout(function() {
                $('#koreksi-alert').show();
            }, 200);

            // var row = document.createElement("tr");
            // var msg = document.createElement('td');
            // msg.innerHTML = 'Tidak bisa melihat data saat sedang melakukan koreksi ///'
            // msg.setAttribute("colspan", "100")
            // msg.setAttribute('style', 'text-align:center;')
            // row.append(msg)
            // $('#ujian_siswa_table').append(row);


        }
    })
})

$('#refresh-button').on('click', () => {
    selected_jadwal_ujian_id = $('#select-paket').find(':selected').val()

    var body = {
        "test": "test text asdasd",
        "jadwal_ujian_id": selected_jadwal_ujian_id
    }

    GetSiswaAjax(body)
})


const GetSiswa = async (body) => {
    const response = fetch(base_url + '/api/siswa-paket', {
        method: 'POST',
        body: JSON.stringify(body),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then((response) => response.json())
    .then((responseJSON) => {
       console.log(responseJSON)
    })
    .catch((err) => {
        console.log(err)
    })
}

const GetElapsedKoreksiTime = async (ujian_id, ujian_nama) => {
    $.ajax({
        type: 'GET',
        contentType: "application/json",
        dataType: "json",
        url: base_url + '/api/koreksi/elapsed/' + ujian_id,
        success: (data) => {

            if (data.success) {
                $('#koreksi-alert').hide();
                $('#koreksi-alert').html('Sedang melakukan koreksi pada ' + ujian_nama.slice(0, -3) + ', dimulai ' + data.data.elapsed_time_minutes + ' menit yang lalu')
                setTimeout(function() {
                    $('#koreksi-alert').show();
                }, 200);
            }

        },
        error: (err) => {
            console.log(err)
        }
    })
}

const GetSiswaAjax = async (body) => {

    var ujian_datatable = $('#ujian_siswa_table').DataTable({
        retrieve: true
    })

    $.ajax({
        type: 'GET',
        contentType: "application/json",
        dataType: "json",
        url: base_url + '/api/is-any-onprogress',
        success: async (data) => {

            // console.log(data.data.on_progress)

            if (data.data.on_progress) {
                $("#koreksi-button").prop("disabled", true)

                // ujian_datatable.clear()
                // ujian_datatable.draw()

                await GetElapsedKoreksiTime(data.data.id, data.data.nama)

                UpdateTable(body) // tetep tampilkna data

                // var row = document.createElement("tr");
                // var msg = document.createElement('td');
                // msg.innerHTML = 'Tidak bisa melihat data saat sedang melakukan koreksi ///'
                // msg.setAttribute("colspan", "100")
                // msg.setAttribute('style', 'text-align:center;')
                // row.append(msg)
                // $('#ujian_siswa_table').append(row);
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

    var ujian_datatable = $('#ujian_siswa_table').DataTable({
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
        url: base_url + '/api/siswa-paket',
        success: (data) => {
            // console.log(JSON.parse(data.data[0].random_soal))
            // LoadUjianSiswaTableData(data.data)

            ujian_datatable.rows.add(data.data)
            ujian_datatable.draw()

        },
        error: (err) => {
            console.log(err)
        }
    })
}
