<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoreksiLog extends Model
{
    use HasFactory;

    protected $table = 'koreksi_log';

    public $timestamps = false;

    protected $fillable = [
        'jadwal_ujian_id', 'state', 'proses',
        'waktu_mulai', 'waktu_selesai'
    ];
}
