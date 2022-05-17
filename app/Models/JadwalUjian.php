<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class JadwalUjian extends Model
{
    use HasFactory;

    protected function GetNamaUjian($jadwal_ujian_id) {

        return DB::table('ujian_paket')
            ->join('paket', 'ujian_paket.paket_id', 'paket.id')
            ->where('ujian_paket.jadwal_ujian_id', '=', $jadwal_ujian_id)
            ->select('ujian_paket.jadwal_ujian_id','paket.nama')
            ->first();
    }

    protected function UjianSiswaByUjianID() {

        $available_jadwal = DB::table('ujian_siswa')->select('jadwal_ujian_id')->distinct()->get()->toArray();

        $j = [];

        foreach ($available_jadwal as $jadwal) {
            array_push($j, $jadwal->jadwal_ujian_id);
        }

        return DB::table('ujian_paket')
            ->join('paket', 'ujian_paket.paket_id', 'paket.id')
            ->join('jadwal_ujian', 'jadwal_ujian.id', 'ujian_paket.jadwal_ujian_id')
            ->whereIn('ujian_paket.jadwal_ujian_id', $j)
            ->select('ujian_paket.jadwal_ujian_id','paket.nama', 'jadwal_ujian.sesi')
            ->orderBy('nama', 'asc');
    }

}
