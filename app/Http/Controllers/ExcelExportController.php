<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\AgregasiHasilExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\AgregasiHasil;
use App\Models\JadwalUjian;

use Carbon\Carbon;

class ExcelExportController extends Controller
{
    public function ExportHasilAgregasiDataUjianLevel($jadwal_ujian_id){

        // dd(AgregasiHasil::GetAllHasilUjian($jadwal_ujian_id)->get()->all());

        $nama = JadwalUjian::GetUjianNamaById($jadwal_ujian_id)->first();

        $nama = preg_replace('/\s+/', '_', $nama->nama);
        $nama = preg_replace('/\-_+/', '', $nama);

        $today = Carbon::now()->format('Y-m-d');

        $nama_file = $nama . '_' . $today . '.xlsx';

        return Excel::download(
            new AgregasiHasilExport($jadwal_ujian_id), $nama_file
        );
    }
}
