@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Mutu Kisi-kisi</h1>
@stop

@section('content')
    <p>Jumlah jawaban siswa pada tiap kisi kisi.</p>

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

    <div class="alert alert-primary" role="alert" id="loading-alert" style="display:none">
        Loading
    </div>
    <div id="graph-container" class="row">
        {{-- <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5 graph-size"></div>
                    <div class="col-lg-7">
                        <div class="">
                            <b>Soal Nomor </b> 1
                        </div>
                        <div>
                            <b>KD</b>
                            <div>
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dicta ducimus inventore laborum laudantium nobis dolor quaerat velit tempore doloremque reiciendis quod porro quisquam, provident, animi neque eos quis, nesciunt nemo?
                            </div>
                        </div>
                        <div>
                            <b>IBS</b>
                            <div>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus laborum dolor veniam deleniti unde architecto quis quibusdam quam nobis laboriosam aut voluptate suscipit nisi possimus est, maiores sint ab saepe.
                            </div>
                        </div>
                        <div>
                            <table class="table table-borderless">
                                <thead>
                                    <th>Jumlah Benar</th>
                                    <th>Jumlah Salah</th>
                                    <th>Jumlah Kosong</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1223</td>
                                        <td>123</td>
                                        <td>3</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}


    </div>

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .graph-size {
            /* width:400px !important;
            height:400px !important; */
        }
    </style>
@stop

@section('js')

    <script src="{{ asset('js/components/detail-agregasi.js')}}"></script>
    <script src="{{ asset('js/components/number_format.js')}}"></script>
    {{-- <script src="{{ asset('js/components/ujian-siswa-table-loader.js')}}"></script> --}}

    <script>
    // const base_url = window.location.origin

    $(document).ready( function () {

        // fetch all active jadwal
        $.ajax({
            type: 'GET',
            url: base_url + '/api/pelajaran',
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

    });
    </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/1.4.0/chartjs-plugin-annotation.min.js" integrity="sha512-HrwQrg8S/xLPE6Qwe7XOghA/FOxX+tuVF4TxbvS73/zKJSs/b1gVl/P4MsdfTFWYFYg/ISVNYIINcg35Xvr6QQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js" integrity="sha512-R/QOHLpV1Ggq22vfDAWYOaMd5RopHrJNMxi8/lJu8Oihwi4Ho4BRFeiMiCefn9rasajKjnx9/fTQ/xkWnkDACg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@stop
