<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class KoreksiLog extends Model
{
    use HasFactory;

    protected $table = 'koreksi_log';

    public $timestamps = false;

    protected $fillable = [
        'jadwal_ujian_id', 'state', 'proses',
        'waktu_mulai', 'waktu_selesai'
    ];

    protected function GetLogForJadwalUjianId($jadwal_ujian_id) {
        $log = DB::table('koreksi_log')
            ->where('jadwal_ujian_id', '=', $jadwal_ujian_id)
            ->join('jadwal_ujian', 'jadwal_ujian.id', 'koreksi_log.jadwal_ujian_id')
            ->select('koreksi_log.jadwal_ujian_id', 'koreksi_log.state', 'koreksi_log.waktu_mulai', 'koreksi_log.waktu_selesai', 'koreksi_log.proses', 'koreksi_log.id', 'jadwal_ujian.deskripsi')
            ->orderBy('waktu_mulai', 'ASC');

        return $log;
    }
}
