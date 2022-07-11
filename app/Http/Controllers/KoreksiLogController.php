<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\KoreksiLog;
use App\Models\JadwalUjian;

use App\Enums\ResponseStatus;

use App\Http\Resources\GenericResponse;
use Exception;

use Carbon\Carbon;

class KoreksiLogController extends Controller
{
    public function ShowKoreksiLog($jadwal_ujian_id) {
        $log = KoreksiLog::GetLogForJadwalUjianId($jadwal_ujian_id)->get()->toArray();

        $logs = [];

        foreach($log as $l) {
            $tmp = [];

            $start = Carbon::parse($l->waktu_mulai);
            $finish = Carbon::parse($l->waktu_selesai);

            $diff = $start->diffInSeconds($finish);

            $tmp['diff'] = $diff;
            $tmp['jadwal_ujian_id'] = $l->jadwal_ujian_id;
            $tmp['state'] = $l->state;
            $tmp['waktu_mulai'] = $l->waktu_mulai;
            $tmp['waktu_selesai'] = $l->waktu_selesai;
            $tmp['proses'] = $l->proses;
            $tmp['id'] = $l->id;
            $tmp['deskripsi_jadwal'] = $l->deskripsi;

            array_push($logs, $tmp);
        }

        $title = JadwalUjian::GetNamaUjian($jadwal_ujian_id)->nama;

        // dd($title);

        return view('log')
            ->with('logs', $logs)
            ->with('title', $title);
        // return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $logs);
    }
}
