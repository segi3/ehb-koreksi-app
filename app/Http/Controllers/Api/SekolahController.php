<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\ResponseStatus;
use Illuminate\Support\Facades\DB; //TODO: MOVE QUERY TO MODEL
use App\Http\Resources\GenericResponse;

class SekolahController extends Controller
{
    public function GetSekolahOnRayon($kd_rayon) {
        $sekolah = DB::table('ujian_siswa')
            ->selectRaw('distinct sekolah_id, sekolah_nama')
            ->where('rayon_kd', $kd_rayon)
            ->get();

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $sekolah);
    }
}
