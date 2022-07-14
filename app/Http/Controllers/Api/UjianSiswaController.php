<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\UjianSiswa;
use App\Models\JadwalUjian;
use App\Models\KoreksiSummary;

use App\Enums\StateKoreksi;
use App\Enums\ResponseStatus;

use App\Http\Resources\SiswaResource;
use App\Http\Resources\GenericResponse;


use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $stateKoreksi = StateKoreksi::SUDAH_DIKOREKSI()->value;
        $ujian_siswa = UjianSiswa::UjianSiswaTableByUjianID($jadwal_ujian_id)->get()->toArray();

        $jumlah_soal = UjianSiswa::GetUjianJumlahSoal($ujian_siswa[0]->id);

        foreach($ujian_siswa as $ujian) {
            if ($ujian->jumlah_benar == '-2') continue;
            $ujian->jumlah_benar = intval($ujian->jumlah_benar) / intval($jumlah_soal) * 100;
        }

        return new SiswaResource(true, ResponseStatus::SUCCESS()->value, $stateKoreksi, $ujian_siswa);
    }

    public function KoreksiSummary() {
        $summ = KoreksiSummary::all()->toArray();

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $summ);
    }

    public function KoreksiSummaryRefresh() {
        $count = UjianSiswa::UjianKoreksiCount()->get()->toArray();
        $ujian = JadwalUjian::UjianSiswaByUjianID()->get();

        $response = [];

        KoreksiSummary::truncate();

        foreach($count as $c) {
            $tmp = [];

            foreach($ujian as $u) {
                if ($u->jadwal_ujian_id == $c->jadwal_ujian_id) {
                    $tmp['nama_ujian'] = $u->deskripsi;
                }
            }

            if ($tmp['nama_ujian'] == null) {
                $tmp['nama_ujian'] = 'not_found';
            }

            // sample string: PEMINATAN | Geografi | IPS | 2013 | UTAMA | 1
            $desc_arr = explode(" | ", $tmp['nama_ujian']);

            $tmp['jadwal_ujian_id'] = $c->jadwal_ujian_id;
            $tmp['done'] = $c->done;
            $tmp['not_done'] = $c->not_done;

            $tmp['mata_pelajaran'] = $desc_arr[1];
            $tmp['jurusan'] = $desc_arr[2];
            $tmp['jenis'] = $desc_arr[0];
            $tmp['sesi'] = $desc_arr[5];
            $tmp['updated_at'] = Carbon::now()->toDateTimeString();

            array_push($response, $tmp);

            KoreksiSummary::create([
                'jadwal_ujian_id' => $tmp['jadwal_ujian_id'],
                'nama_ujian' => $tmp['nama_ujian'],
                'done' => $tmp['done'],
                'not_done' => $tmp['not_done'],
                'jurusan' => $desc_arr[2],
                'jenis' => $desc_arr[0],
                'sesi' => $desc_arr[5],
                'mata_pelajaran' => $desc_arr[1],
                'updated_at' => $tmp['updated_at']
            ]);
        }

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $response);
    }
}
