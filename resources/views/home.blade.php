@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>

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
                <div class="offset-lg-4 col-lg-1">
                    <button type="button" class="btn btn-secondary" id="refresh-button">Refresh</button>
                </div>
            </div>

        </div>
    </div>

    <div class="alert alert-primary" role="alert" id="koreksi-alert" style="display:none">
        This is a primary alert—check it out!
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
                "emptyTable": " ",
                "zeroRecords": " ",
                'loadingRecords': '&nbsp;',
                'processing': 'Loading...'
            },
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
                { data: 'predikat' },
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
            url: 'http://127.0.0.1:8000/api/is-any-onprogress',
            success: (data) => {

                tableUjian.clear()
                tableUjian.draw()

                if (data.data.on_progress) {
                    $('#koreksi-alert').html('Sedang melakukan koreksi pada ' + data.data.nama)
                    $('#koreksi-alert').show();

                    var row = document.createElement("tr");
                    var msg = document.createElement('td');
                    msg.innerHTML = 'Tidak bisa melihat data saat sedang melakukan koreksi ///'
                    msg.setAttribute("colspan", "100")
                    msg.setAttribute('style', 'text-align:center;')
                    row.append(msg)
                    $('#ujian_siswa_table').append(row);
                } else {
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
            url: 'http://127.0.0.1:8000/api/active-jadwal',
            success: (data) => {
                const ujian = data.data

                ujian.forEach(el => {
                    var option = document.createElement("option")
                    option.value = el.jadwal_ujian_id
                    option.innerHTML = el.nama + ' sesi ' + el.sesi
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
