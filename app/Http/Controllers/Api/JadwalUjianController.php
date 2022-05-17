<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JadwalUjian;
use App\Models\StateKoreksi;

use App\Enums\ResponseStatus;
use App\Enums\StatusKoreksi;

use App\Http\Resources\GenericResponse;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class JadwalUjianController extends Controller
{
    public function GetActiveUjian() {

        $jadwal = JadwalUjian::UjianSiswaByUjianID()->get();

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $jadwal);
    }

    public function IsUjianAvailable($jadwal_ujian_id) {

        $isinprogress = StateKoreksi::IsOnProgress($jadwal_ujian_id);

        if (!$isinprogress)  return new GenericResponse(true, ResponseStatus::SUCCESS()->value, [
            'in_progress' => true
        ]);

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, [
            'in_progress' => false
        ]);

    }

    public function IsAnyOnProgress() {

        $resp = [];

        $latest = StateKoreksi::GetLatestKoreksiAll();

        if($latest->state == StatusKoreksi::on_progress()->value) {
            $resp['on_progress'] = true;
        } else {
            $resp['on_progress'] = false;
        }

        $jadwal = JadwalUjian::GetNamaUjian($latest->jadwal_ujian_id);

        $resp['id'] = $jadwal->jadwal_ujian_id;
        $resp['nama'] = $jadwal->nama;

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $resp);
    }
}
