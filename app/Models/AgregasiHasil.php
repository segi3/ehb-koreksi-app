<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class AgregasiHasil extends Model
{
    use HasFactory;

    protected $table = 'ujian_siswa';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'random_soal',
        'random_jawaban',
        'jawaban_siswa',
        'waktu_mulai',
        'waktu_selesai',
        'durasi',
        'jumlah_benar',
        'jumlah_salah',
        'jumlah_kosong',
        'nilai_uraian',
        'paket_id',
        'user_id',
        'server_id',
        'jadwal_ujian_id',
        'status',
        'kunci_jawaban',
        'pg_benar',
        'pgk_l1_benar',
        'pg_bs1_benar',
        'pg_bsl1_benar',
        'mjdk_benar',
        'ijs_benar'
    ];

    protected function GetAllHasilUjian($jadwal_ujian_id) {

        $data = DB::table('ujian_siswa')
            ->where('jadwal_ujian_id', $jadwal_ujian_id)
            ->where('jumlah_benar', '!=', '-2')
            ->select('id', 'predikat', 'sekolah_id', 'sekolah_nama', 'rayon_nama', 'rayon_kd', 'jumlah_benar');

        return $data;
    }
}
