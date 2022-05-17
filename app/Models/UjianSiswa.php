<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class UjianSiswa extends Model
{
    use HasFactory;

    protected $table = 'ujian_siswa';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =[
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

    protected function GetUjianJumlahSoal($ujian_siswa_id) {

        $ujian = DB::table('ujian_siswa')->find($ujian_siswa_id);

        $soal = json_decode($ujian->random_soal);

        return count($soal);
    }

    protected function UjianSiswaByUjianID($jadwal_ujian_id) {

        return DB::table('ujian_siswa')->where('jadwal_ujian_id', $jadwal_ujian_id);
    }

    protected function UjianSiswaTableByUjianID($jadwal_ujian_id) {
        return DB::table('ujian_siswa')
            ->join('user_ehb', 'user_ehb.id', '=', 'ujian_siswa.user_id')
            ->join('sekolah', 'sekolah.id', 'user_ehb.sekolah_id')
            ->where('jadwal_ujian_id', $jadwal_ujian_id)
            ->select('user_ehb.nisn', 'user_ehb.nama as nama_siswa', 'user_ehb.jurusan',
                    'sekolah.nama as nama_sekolah',
                    'ujian_siswa.jumlah_benar', 'ujian_siswa.predikat', 'ujian_siswa.id', 'ujian_siswa.random_soal');
    }

    protected function UjianSiswaSelesaiKoreksi($jadwal_ujian_id) {
        return DB::table('ujian_siswa')
            ->where('jadwal_ujian_id', $jadwal_ujian_id)
            ->where('jumlah_benar', '!=', '-2')
            ->get()->count();
    }

    protected function UjianSiswaBelumSelesaiKoreksi($jadwal_ujian_id) {
        return DB::table('ujian_siswa')->where('jadwal_ujian_id', '34e840e0-9b03-11ec-8c5d-4bdede66813d')->where('jumlah_benar', '=', '-2')->get()->count();
    }
}
