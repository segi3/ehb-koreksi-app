@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Log pengoreksian {{ $title }}</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">
        <div class="row px-5" id="log-container">

            @if (count($logs) < 1) <p>
                Tidak ada data log
                </p>

                @else
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Waktu Mulai</th>
                            <th scope="col">Waktu Selesai</th>
                            <th scope="col">Durasi (sekon)</th>
                            <th scope="col">Status</th>
                            <th scope="col">Jumlah Proses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log['waktu_mulai'] }}</td>
                            <td>{{ $log['waktu_selesai'] }}</td>
                            <td>{{ $log['diff'] }}</td>
                            <td>{{ $log['state'] }}</td>
                            <td>{{ $log['proses'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
        </div>
    </div>
</div>


@stop

@section('css')
<style>
    .summary-header {
        font-size: 15px !important;
    }

    #summary-container>div {
        font-size: 20px;
    }

    .loader {
        border: 9px solid #f3f3f3;
        /* Light grey */
        border-top: 9px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
    }

    .hide-loader {
        display: none;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

</style>
@stop

@section('js')

<script src="{{ asset('js/components/number_format.js')}}"></script>

<script>
    const base_url = window.location.origin
    $(document).ready(function () {



    });

</script>


@stop
