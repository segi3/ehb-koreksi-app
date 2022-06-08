<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class AgregasiKisi extends Model
{
    use HasFactory;

    protected $table = 'raw_agregasi_kisi';

    protected function GetAgregasiKisi($mapel) {
        return DB::table('raw_agregasi_kisi')
                ->selectRaw('no_soal, kd, ibs, paket, tipe_soal, avg(benar) as rata_rata, sum(benar) as jumlah_benar, sum(salah) as jumlah_salah, sum(kosong) as jumlah_kosong')
                ->where('mapel_nama', $mapel)
                ->groupBy('ibs', 'paket')
                ->orderBy('paket', 'asc')
                ->orderBy('no_soal', 'asc');
    }
}
