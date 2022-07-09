@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Koreksi hasil ujian EHB-BKS Jawa Timur 2021</h1>
@stop

@section('content')
    <p>Pilih jadwal ujian dan klik koreksi untuk memulai koreksi.</p>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2">
                    Paket Ujian
                </div>
                <div class="col-lg-5" id="paket-selector">
                    <select class="form-select" aria-label="Default select example" id="select-paket">
                        <option selected disabled>Pilih ujian</option>
                      </select>
                </div>
                <div class="offset-lg-3 col-lg-2">
                    <button type="button" class="btn btn-primary" id="koreksi-button" disabled>Koreksi</button>
                    <button type="button" class="btn btn-secondary" id="refresh-button">Refresh</button>
                </div>
            </div>

        </div>
    </div>

    <div class="alert alert-primary" role="alert" id="koreksi-alert" style="display:none">
        alert koreksi
    </div>

    <table class="table table-dark" id ="ujian_siswa_table">
        <thead>
          <tr>
            <th scope="col">NISN</th>
            <th scope="col">Nama Siswa</th>
            <th scope="col">Sekolah</th>
            <th scope="col">Jurusan</th>
            <th scope="col">Nilai</th>
            <th scope="col">Predikat</th>
            <th scope="col">Status Koreksi</th>
          </tr>
        </thead>
        <tbody>
          {{-- <tr>
            <th scope="row">14045</th>
            <td>Rafi Nizar</td>
            <td>SMA INTI</td>
            <td>MIPA</td>
            <td>80</td>
            <td>BAGUS</td>
            <td>SELESAI DIKOREKSI</td>
          </tr> --}}

        </tbody>
      </table>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

    <script src="{{ asset('js/components/load-siswa.js')}}"></script>
    <script src="{{ asset('js/components/ujian-siswa-table-loader.js')}}"></script>

    <script>
    $(document).ready( function () {

        var tableUjian = $('#ujian_siswa_table').DataTable({
            language: {
                "emptyTable": "Silahkan pilih jadwal ujian",
                "zeroRecords": " ",
                'loadingRecords': '&nbsp;',
                'processing': 'Loading...'
            },
            responsive: true,
            retrieve: true,
            data: {},
            columns: [
                { data: 'nisn' },
                { data: 'nama_siswa' },
                { data: 'nama_sekolah' },
                { data: 'jurusan' },
                { data: 'jumlah_benar',
                    render: function ( data, type, row ) {
                        var nilai = parseInt(data)
                        if (nilai == -2 || nilai == '-2') {
                            nilai = '-'
                        }
                        return nilai;
                    }
                },
                { data: 'predikat',
                    render: function (data, type, row) {
                        var nilai = parseInt(row.jumlah_benar)
                        if (nilai == -2 || nilai == '-2') {
                            return '-'
                        }
                        return data;
                    }},
                { data: 'jumlah_benar',
                    render: function ( data, type, row ) {
                        var nilai = 'SUDAH DIKOREKSI'
                        if (parseInt(data) == -2) {
                            nilai = 'BELUM DIKOREKSI'
                        }
                        return nilai;
                    }
                }
            ]
        })

        $.ajax({
            type: 'GET',
            contentType: "application/json",
            dataType: "json",
            url: base_url+ '/api/is-any-onprogress',
            success: async (data) => {

                // tableUjian.clear()
                // tableUjian.draw()

                if (data.data.on_progress) {
                    $("#koreksi-button").prop("disabled", true)

                    await GetElapsedKoreksiTime(data.data.id, data.data.nama)

                    // $('#koreksi-alert').html('Sedang melakukan koreksi pada ' + data.data.nama.slice(0, -3))
                    // $('#koreksi-alert').show()

                    // var row = document.createElement("tr");
                    // var msg = document.createElement('td');
                    // msg.innerHTML = 'Tidak bisa melihat data saat sedang melakukan koreksi ///'
                    // msg.setAttribute("colspan", "100")
                    // msg.setAttribute('style', 'text-align:center;')
                    // row.append(msg)
                    // $('#ujian_siswa_table').append(row);
                } else {
                    $("#koreksi-button").prop("disabled", false)

                    var row = document.createElement("tr");
                    var msg = document.createElement('td');
                    msg.innerHTML = 'Silahkan pilih jadwal ujian ///'
                    msg.setAttribute("colspan", "100")
                    msg.setAttribute('style', 'text-align:center;')
                    row.append(msg)
                    $('#ujian_siswa_table').append(row);
                }
            },
            error: (err) => {
                console.log(err)
            }
        })

        // fetch all active jadwal
        $.ajax({
            type: 'GET',
            url: base_url + '/api/active-jadwal',
            success: (data) => {
                const ujian = data.data

                var existsEl = []
                var existsElNama = []

                ujian.forEach(el => {
                    if (existsEl.includes(el.jadwal_ujian_id)) {
                        return
                    } else {
                        existsEl.push(el.jadwal_ujian_id)
                    }

                    // var newNama = ''
                    // if (existsElNama.includes(el.nama.slice(0, -3))) {
                    //     newnama = el.nama.slice(0, -3) + '- 2'
                    // } else {
                    //     existsElNama.push(el.nama.slice(0, -3))
                    //     newnama = el.nama.slice(0, -3) + '- 1'
                    // }

                    var deskripsi = el.deskripsi.split(' | ')
                    var nama = deskripsi[1] + ' - ' + deskripsi[5]

                    var option = document.createElement("option")
                    option.value = el.jadwal_ujian_id
                    option.innerHTML = el.deskripsi
                    $('#select-paket').append(option);
                })
            },
            error: (err) => {
                console.log(err)
            }
        })
    } );
    </script>


@stop
