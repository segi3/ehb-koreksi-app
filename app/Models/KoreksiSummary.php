<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoreksiSummary extends Model
{
    use HasFactory;

    protected $table = 'koreksi_summary';

    public $timestamps = false;

    protected $fillable = [
        'jadwal_ujian_id', 'nama_ujian',
        'done', 'not_done',
        'jurusan', 'jenis', 'sesi', 'mata_pelajaran'
    ];
}
