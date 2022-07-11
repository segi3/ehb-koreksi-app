@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Agregasi Hasil Ujian Per Rayon</h1>
@stop

@section('content')
    <p>Pilih jadwal ujian dan klik tampilkan untuk memulai koreksi.</p>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2">
                    Rayon
                </div>
                <div class="col-lg-5" id="rayon-selector">
                    <select class="form-select" aria-label="Default select example" id="select-rayon">
                        <option selected>Semua</option>
                      </select>
                </div>
                <div class="offset-lg-3 col-lg-2">

                    {{-- <form id="download-form" action="">
                        <button type="submit" class="btn btn-primary" id="download-button">Download data</button>
                    </form> --}}
                    <button class="btn btn-secondary" id="rayon-detail-button">Detail Rayon</button>
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
            <th scope="col">Rayon</th>
            <th scope="col">Paket</th>
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

    <script src="{{ asset('js/components/agregasi-rayon.js')}}"></script>
    <script src="{{ asset('js/components/number_format.js')}}"></script>

    <script>
    // const base_url = window.location.origin
    $("#rayon-detail-button").prop("disabled", true)

    $(document).ready( function () {
        $('#graph-container').hide()
        $("#download-button").prop("disabled", true)

        var tableUjian = $('#agregasi_table').DataTable({
            language: {
                "emptyTable": "Silahkan pilih rayon",
                "zeroRecords": " ",
                'loadingRecords': '&nbsp;',
                'processing': 'Loading...'
            },
            responsive: true,
            autoWidth: false,
            retrieve: true,
            data: {},
            columns: [
                { data: 'rayon_nama' },
                { data: 'nama' },
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

        // init data, semua rayon
        $.ajax({
        type: 'GET',
        url: base_url + '/api/agregasi/rayon/' + selected_kd_rayon,
        success: (data) => {


            tableUjian.rows.add(data.data)
            tableUjian.draw()

            // const result = PrepareChartData(data.data)

            // NewAverageChart(result.chart.chart_labels, result.chart.chart_data)
            // NewPredikatChart(result.pie.pie_labels, result.pie.pie_data)

        },
        error: (err) => {
            console.log(err)
        }
    })

        // fetch all active kab kota
        $.ajax({
            type: 'GET',
            url: base_url + '/api/active-kab-kota',
            success: (data) => {
                const kabkota = data.data
                console.log(kabkota)
                kabkota.forEach(el => {

                    var option = document.createElement("option")
                    option.value = el.kd_rayon
                    option.innerHTML = el.nama
                    $('#select-rayon').append(option);
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
