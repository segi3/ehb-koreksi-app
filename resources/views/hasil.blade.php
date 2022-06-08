@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Agregasi Hasil Ujian</h1>
@stop

@section('content')
    <p>Pilih jadwal ujian dan klik tampilkan untuk memulai koreksi.</p>

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
                    {{-- <button type="button" class="btn btn-primary" id="tampilkan-button" disabled>Tampilkan</button> --}}

                    {{-- <form id="download-form" action="">
                        <button type="submit" class="btn btn-primary" id="download-button">Download data</button>
                    </form> --}}
                </div>
            </div>

        </div>
    </div>

    <div class="alert alert-primary" role="alert" id="agregasi-alert" style="display:none">
        alert agregasi
    </div>

    <div id="graph-container">
        <div class="card">
            <div class="card-body row">
                <div class="col-lg-8" id="chart-container">
                    <canvas id="agregasichart"></canvas>
                </div>
                <div class="col-lg-4" id="pie-container">
                    <canvas id="piechart"></canvas>
                </div>
            </div>
        </div>
    </div>



    <table class="table table-dark" id ="agregasi_table">
        <thead>
          <tr>
            <th scope="col">Sekolah</th>
            <th scope="col">Rayon</th>
            <th scope="col">Rata-rata</th>
            <th scope="col">Nilai Min</th>
            <th scope="col">Nilai Max</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

    <script src="{{ asset('js/components/agregasi.js')}}"></script>
    <script src="{{ asset('js/components/number_format.js')}}"></script>

    <script>
    $(document).ready( function () {
        $('#graph-container').hide()
        $("#download-button").prop("disabled", true)

        var tableUjian = $('#agregasi_table').DataTable({
            language: {
                "emptyTable": "Silahkan pilih jadwal ujian",
                "zeroRecords": " ",
                'loadingRecords': '&nbsp;',
                'processing': 'Loading...'
            },
            responsive: true,
            autoWidth: false,
            retrieve: true,
            data: {},
            columns: [
                { data: 'sekolah_nama' },
                { data: 'rayon_nama' },
                { data: 'avg', render: function (data, type, row) {
                    return formatTwoDecimalPlace(data);
                } },
                { data: 'min', render: function (data, type, row) {
                    return formatTwoDecimalPlace(data);
                } },
                { data: 'max', render: function (data, type, row) {
                    return formatTwoDecimalPlace(data);
                } }
            ]
        })

        // fetch all active jadwal
        $.ajax({
            type: 'GET',
            url: 'http://127.0.0.1:8000/api/active-jadwal',
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/1.4.0/chartjs-plugin-annotation.min.js" integrity="sha512-HrwQrg8S/xLPE6Qwe7XOghA/FOxX+tuVF4TxbvS73/zKJSs/b1gVl/P4MsdfTFWYFYg/ISVNYIINcg35Xvr6QQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/components/agregasi-chart.js')}}"></script>

@stop
