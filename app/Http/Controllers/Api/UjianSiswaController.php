<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\UjianSiswa;

use App\Enums\StateKoreksi;
use App\Enums\ResponseStatus;

use App\Http\Resources\SiswaResource;

class UjianSiswaController extends Controller
{
    public function ShowUjianByUjianId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_ujian_id'   => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jadwal_ujian_id = $request->jadwal_ujian_id;
        $stateKoreksi = StateKoreksi::CAMPUR()->value;
        $ujian_siswa = UjianSiswa::UjianSiswaTableByUjianID($jadwal_ujian_id)->get()->toArray();

        $jumlah_soal = UjianSiswa::GetUjianJumlahSoal($ujian_siswa[0]->id);

        // return new SiswaResource(true, ResponseStatus::SUCCESS()->value, $stateKoreksi, $ujian_siswa);

        foreach($ujian_siswa as $ujian) {
            if ($ujian->jumlah_benar == '-2') continue;
            $ujian->jumlah_benar = intval($ujian->jumlah_benar) / intval($jumlah_soal) * 100;
        }

        return new SiswaResource(true, ResponseStatus::SUCCESS()->value, $stateKoreksi, $ujian_siswa);
    }
}
