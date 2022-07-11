<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Enums\StatusKoreksi;
use App\Enums\KoreksiLogState;

use Illuminate\Support\Facades\DB;

class StateKoreksi extends Model
{
    use HasFactory;

    protected $table = 'state_koreksi';

    protected $fillable = ['jadwal_ujian_id', 'state', 'running_log'];

    protected function IsOnProgress($jadwal_ujian_id) {
        $latest = DB::table('state_koreksi')
            ->where('jadwal_ujian_id', '=', $jadwal_ujian_id)
            ->orderBy('updated_at', 'DESC')->first();

        if(!$latest) return False;

        if ($latest->state == StatusKoreksi::on_progress()->value) {
            return True;
        }

        return False;
    }

    protected function GetLatestKoreksiAll() {
        return DB::table('state_koreksi')
            ->orderBy('updated_at', 'DESC')->first();
    }

    protected function UpdateKoreksiState($jadwal_ujian_id, $state) {
        $latest = DB::table('state_koreksi')
            ->where('jadwal_ujian_id', '=', $jadwal_ujian_id)
            ->orderBy('updated_at', 'DESC')->first();

        if ($latest->state != StatusKoreksi::on_progress()->value) {
            return False;
        }

        $latest = StateKoreksi::find($latest->id);
        $latest->state = $state;
        $latest->save();

        // update log as well
        $log = KoreksiLog::find($latest->running_log);
        $log->waktu_selesai = Carbon::now();
        $log->state = KoreksiLogState::FINISHED()->value;
        $log->save();


        return True;
    }

    protected function GetLatestKoreksi($jadwal_ujian_id) {

        return DB::table('state_koreksi')
            ->where('jadwal_ujian_id', '=', $jadwal_ujian_id)
            ->orderBy('updated_at', 'DESC');
    }

    protected function GetElapsedTime($jadwal_ujian_id) {

        $state = DB::table('state_koreksi')
            ->where('jadwal_ujian_id', '=', $jadwal_ujian_id)
            ->orderBy('updated_at', 'DESC')->first();

        $startTime = Carbon::parse($state->updated_at);
        $endTime =Carbon::now();

        return $startTime->diffInMinutes($endTime);
    }
}
