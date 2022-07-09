@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row"  id="summary-container">

                <div class="col-lg-12 d-flex justify-content-center summary-header">
                    <div class="col-lg-6">
                        Jadwal Ujian
                    </div>
                    <div class="col-lg-2">
                        Siswa Selesai Dikoreksi
                    </div>
                    <div class="col-lg-2">
                        Siswa Belum Dikoreksi
                    </div>
                </div>

                <div class="col-lg-12 d-flex justify-content-center mt-5 pt-5 mb-5 pt-5 loader-container" id="loader-container">
                    <div class="loader"></div>
                </div>

            </div>

        </div>
    </div>


@stop

@section('css')
    <style>
        .summary-header {
            font-size: 15px !important;
        }
        #summary-container > div {
            font-size: 20px;
        }
        .loader {
            border: 9px solid #f3f3f3; /* Light grey */
            border-top: 9px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }
        .hide-loader{
            display:none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@stop

@section('js')

    <script>
    const base_url = window.location.origin
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


        // fetch summary
        $.ajax({
            type: 'GET',
            url: base_url + '/api/koreksi/summary',
            success: (data) => {

                data.data.forEach(el => {
                    $('#summary-container').append(
                    '<div class="col-lg-12 d-flex justify-content-center">' +
                        '<div class="col-lg-6">' +
                            el.nama_ujian +
                        '</div>' +
                        '<div class="col-lg-2">' +
                            el.done +
                        '</div>' +
                        '<div class="col-lg-2">' +
                            el.not_done +
                        '</div>' +
                    '</div>'
                    );
                });


                $('#loader-container').remove()
                // $('.loader').addClass('hide-loader')

            },
            error: (err) => {
                console.log(err)
            }
        })
    } );
    </script>


@stop
