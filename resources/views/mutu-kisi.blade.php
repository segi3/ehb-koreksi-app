@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Download Mutu Kisi-kisi</h1>
@stop

@section('content')
    <p>Presentase siswa yang menjawab benar pada setiap paket.</p>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2">
                    Pelajaran
                </div>
                <div class="col-lg-5" id="pelajaran-selector">
                    <select class="form-select" aria-label="Default select example" id="select-pelajaran">
                        <option selected disabled>Pilih pelajaran</option>
                    </select>
                </div>
                <div class="offset-lg-3 col-lg-2">
                    <form id="download-form" action="">
                        <button type="submit" class="btn btn-primary" id="download-button">Download data</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2">
                    Paket
                </div>
                <div class="col-lg-5" id="paket-pel-selector">
                    <select class="form-select" aria-label="Default select example" id="select-paket-pel">
                        <option selected disabled>Pilih paket</option>
                    </select>
                </div>
            </div>

        </div>
    </div>

    <div class="alert alert-primary" role="alert" id="" style="display:none">
        alert
    </div>

    <table class="table table-dark" id ="agregasi-kisi-table">
        <thead>
          <tr>
            {{-- <th></th> --}}
            <th scope="col">No Soal</th>
            <th scope="col">IBS</th>
            <th scope="col">Tipe Soal</th>
            <th scope="col">Rata-rata benar</th>
            <th scope="col">Predikat</th>
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
    <style>
    td.details-control {
        text-align:center;
        color:forestgreen;
        cursor: pointer;
    }
    tr.shown td.details-control {
        text-align:center;
        color:red;
    }
    </style>
@stop

@section('js')
    <script src="{{ asset('js/components/agregasi-kisi.js')}}"></script>
    <script src="{{ asset('js/components/number_format.js')}}"></script>
    {{-- <script src="{{ asset('js/components/ujian-siswa-table-loader.js')}}"></script> --}}

    <script>
    $(document).ready( function () {

        var tableUjian = $('#agregasi-kisi-table').DataTable({
            language: {
                "emptyTable": "Silahkan pilih jadwal ujian",
                "zeroRecords": " ",
                'loadingRecords': '&nbsp;',
                'processing': 'Loading...'
            },
            responsive: true,
            retrieve: true,
            select: 'single',
            data: {},
            fixedColumns : true,
            pageLength: 50,
            columns: [
                // {
                //     "className": 'details-control',
                //     "orderable": false,
                //     "data": null,
                //     "defaultContent": '',
                //     "render": function () {
                //         return '<i class="fa fa-plus-square" aria-hidden="true"></i>';
                //     },
                //     width:"15px"
                // },
                { data: 'no_soal', width: "10%" },
                { data: 'ibs', width: "40%" },
                { data: 'tipe_soal' },
                { data: 'rata_rata', render: function (data, type, row) {
                    return formatTwoDecimalPlace(data);
                } },
                { data: 'predikat',
                    render: function (data, type, row) {
                        pred = ''
                        if (row.rata_rata > 0.85) {
                            pred = 'Sangat Baik'
                        } else if (row.rata_rata > 0.7) {
                            pred = 'Baik'
                        } else if (row.rata_rata > 0.55) {
                            pred = 'Cukup'
                        } else {
                            pred = 'Kurang'
                        }

                        return pred;
                    }
                },
            ]
        })

        // fetch all active jadwal
        $.ajax({
            type: 'GET',
            url: 'http://127.0.0.1:8000/api/pelajaran',
            success: (data) => {
                const ujian = data.data

                ujian.forEach(el => {
                    var option = document.createElement("option")
                    option.value = el.nama
                    option.innerHTML = el.nama
                    $('#select-pelajaran').append(option);
                })
            },
            error: (err) => {
                console.log(err)
            }
        })
    } );

    // $('#agregasi-kisi-table tbody').on('click', 'td.details-control', (e) => {
    //     var aggkisi_datatable = $('#agregasi-kisi-table').DataTable()

    //     var tr = $(this).closest('tr');
    //     var tdi = tr.find("i.fa");
    //     var row = aggkisi_datatable.row(tr);

    //     // console.log('clicked')
    //     // console.log(tr, tdi, row)
    //     console.log(tr.text())

    //     if (row.child.isShown()) {
    //         // This row is already open - close it
    //         row.child.hide();
    //         tr.removeClass('shown');
    //         tdi.first().removeClass('fa-minus-square');
    //         tdi.first().addClass('fa-plus-square');
    //     }
    //     else {
    //         // Open this row
    //         row.child(format(row.data())).show();
    //         tr.addClass('shown');
    //         tdi.first().removeClass('fa-plus-square');
    //         tdi.first().addClass('fa-minus-square');

    //         console.log('open')
    //     }
    // })

    // const format = (d) => {
    //     return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
    //         '<tr>' +
    //             '<td>Full name:</td>' +
    //             '<td>' + 'raf' + '</td>' +
    //         '</tr>' +
    //         '<tr>' +
    //             '<td>Extension number:</td>' +
    //             '<td>' + '1527' + '</td>' +
    //         '</tr>' +
    //         '<tr>' +
    //             '<td>Extra info:</td>' +
    //             '<td>And any further details here (images etc)...</td>' +
    //         '</tr>' +
    //     '</table>';
    // }

    </script>


@stop
