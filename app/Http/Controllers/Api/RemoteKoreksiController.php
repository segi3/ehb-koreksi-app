<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use GuzzleHttp\Client;

use App\Models\StateKoreksi;
use App\Models\UjianSiswa;
use App\Models\KoreksiLog;

use App\Enums\ResponseStatus;
use App\Enums\StatusKoreksi;
use App\Enums\KoreksiLogState;

use App\Http\Resources\GenericResponse;
use Exception;

use Carbon\Carbon;

class RemoteKoreksiController extends Controller
{

    private function _testConnection() {
        try {
            $client = new Client([
                'timeout'  => 1.0,
                'connect_timeout' => 1.0
            ]);
            $response = $client->get('http://127.0.0.1:5000/health/ping');

        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function StartRemoteKoreksi($jadwal_ujian_id, $proses) {

        // if (!$this->_testConnection()) {
        //     return new GenericResponse(false, ResponseStatus::INTERNAL_SERVER_ERROR()->value, [
        //         'inactive' => true
        //     ]);
        // }

        // create log entry
        $log = KoreksiLog::create([
            'jadwal_ujian_id' => $jadwal_ujian_id,
            'state' => KoreksiLogState::RUNNING()->value,
            'waktu_mulai' => Carbon::now(),
            'waktu_selesai' => null,
            'proses' => $proses
        ]);

        $stateData = StateKoreksi::updateOrCreate([
            'jadwal_ujian_id' => $jadwal_ujian_id
        ], [
            'state' => StatusKoreksi::on_progress()->value,
            'running_log' => $log->id
        ]);

        try {
            $client = new Client([
                'timeout'  => 1.0,
                'connect_timeout' => 1.0
            ]);
            $response = $client->get('http://127.0.0.1:5000/koreksi/' . $jadwal_ujian_id . '/' . $proses);

        } catch (Exception $e) {
            return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $stateData);
            return new GenericResponse(false, ResponseStatus::INTERNAL_SERVER_ERROR()->value, $e);
        }

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $stateData);
    }

    public function FinishHookRemoteKoreksi($jadwal_ujian_id, $state) {

        $update = StateKoreksi::UpdateKoreksiState($jadwal_ujian_id, $state);

        $response = [
            'id' => $jadwal_ujian_id,
            'state' => $state
        ];

        if (!$update) return new GenericResponse(false, ResponseStatus::DENIED()->value, $response);

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $response);
    }

    public function GetElapsedTime($jadwal_ujian_id) {

        $latest = StateKoreksi::GetLatestKoreksi($jadwal_ujian_id)->first();
        $elapsed = StateKoreksi::GetElapsedTime($jadwal_ujian_id);

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value,[
            'jadwal_ujian_id' => $latest->jadwal_ujian_id,
            'elapsed_time_minutes' => $elapsed
        ]);
    }

    public function GetFullStat($jadwal_ujian_id) {
        $latest = StateKoreksi::GetLatestKoreksi($jadwal_ujian_id)->first();
        $elapsed = StateKoreksi::GetElapsedTime($jadwal_ujian_id);
        $selesaiCount = UjianSiswa::UjianSiswaSelesaiKoreksi($jadwal_ujian_id);
        $belumSelesaiCount = UjianSiswa::UjianSiswaBelumSelesaiKoreksi($jadwal_ujian_id);

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value,[
            'jadwal_ujian_id' => $latest->jadwal_ujian_id,
            'elapsed_time_minutes' => $elapsed,
            'selesai' => $selesaiCount,
            'belum_selesai' => $belumSelesaiCount
        ]);
    }
}
