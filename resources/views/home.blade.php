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
                <div class="col-lg-3" id="paket-selector">
                    <select class="form-select" aria-label="Default select example">
                        <option selected disabled>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                      </select>
                </div>
            </div>

        </div>
    </div>

    <table class="table table-dark" id ="table_id">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
          </tr>
          <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>@fat</td>
          </tr>
          <tr>
            <th scope="row">3</th>
            <td>Larry</td>
            <td>the Bird</td>
            <td>@twitter</td>
          </tr>
        </tbody>
      </table>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

    <script src="{{ asset('js/components/load-siswa.js')}}"></script>

    <script>
    $(document).ready( function () {
        $('#table_id').DataTable( {
            "lengthMenu": [[1, 10, 25, 50, -1], [1, 10, 25, 50, "All"]],
            "paging": true
        });
    } );
    </script>


@stop
