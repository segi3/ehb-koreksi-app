<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\UjianSiswa;
use App\Models\AgregasiHasil;

use App\Http\Resources\GenericResponse;
use App\Enums\ResponseStatus;

// TODO: MOVE QUERY TO MODEL
use Illuminate\Support\Facades\DB;
class AgregasiController extends Controller
{
    private function _Stand_Deviation($arr, $avg) {
        $num_of_elements = count($arr);

        $variance = 0.0;

        foreach($arr as $i) {
            $variance += pow(($i - $avg), 2);
        }

        return (float)sqrt($variance/$num_of_elements);
    }

    public function ShowAgregasiRayon($kd_rayon) {
        if ($kd_rayon == 'semua') {
            $agg = DB::table('ujian_siswa')
                ->selectRaw('avg(jumlah_benar)/JSON_LENGTH(random_soal)*100 as avg, min(jumlah_benar)/JSON_LENGTH(random_soal)*100 as min, max(jumlah_benar)/JSON_LENGTH(random_soal)*100 as max, paket.nama, rayon_nama')
                ->join('paket', 'paket.id', 'ujian_siswa.paket_id')
                ->where('rayon_nama', '!=', 'null')
                ->groupBy('rayon_kd', 'paket_id')
                ->get();
        } else {
            $agg = DB::table('ujian_siswa')
                ->selectRaw('avg(jumlah_benar)/JSON_LENGTH(random_soal)*100 as avg, min(jumlah_benar)/JSON_LENGTH(random_soal)*100 as min, max(jumlah_benar)/JSON_LENGTH(random_soal)*100 as max, paket.nama, rayon_nama')
                ->join('paket', 'paket.id', 'ujian_siswa.paket_id')
                ->where('rayon_nama', '!=', 'null')
                ->where('rayon_kd', $kd_rayon)
                ->groupBy('rayon_kd', 'paket_id')
                ->get();
        }

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $agg);
    }

    public function ShowAgregasiRayonNoKD() {
        $agg = DB::table('ujian_siswa')
            ->selectRaw('avg(jumlah_benar)/JSON_LENGTH(random_soal)*100 as avg, min(jumlah_benar)/JSON_LENGTH(random_soal)*100 as min, max(jumlah_benar)/JSON_LENGTH(random_soal)*100 as max, paket.nama, rayon_nama')
            ->join('paket', 'paket.id', 'ujian_siswa.paket_id')
            ->where('rayon_nama', '!=', 'null')
            ->groupBy('paket_id')
            ->get();

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $agg);
    }

    public function ShowAgregasiSekolah($rayon_kd, $sekolah_id) {
        if ($sekolah_id == 'semua') {
            $agg = DB::table('ujian_siswa')
                ->selectRaw('sekolah_nama, sekolah_id, avg(jumlah_benar)/JSON_LENGTH(random_soal)*100 as avg, min(jumlah_benar)/JSON_LENGTH(random_soal)*100 as min, max(jumlah_benar)/JSON_LENGTH(random_soal)*100 as max, paket.nama, rayon_nama')
                ->join('paket', 'paket.id', 'ujian_siswa.paket_id')
                ->where('rayon_nama', '!=', 'null')
                ->where('rayon_kd', $rayon_kd)
                ->groupBy('sekolah_id', 'paket_id')
                ->get();
        } else {
            $agg = DB::table('ujian_siswa')
                ->selectRaw('sekolah_nama, sekolah_id, avg(jumlah_benar)/JSON_LENGTH(random_soal)*100 as avg, min(jumlah_benar)/JSON_LENGTH(random_soal)*100 as min, max(jumlah_benar)/JSON_LENGTH(random_soal)*100 as max, paket.nama, rayon_nama')
                ->join('paket', 'paket.id', 'ujian_siswa.paket_id')
                ->where('rayon_nama', '!=', 'null')
                ->where('rayon_kd', $rayon_kd)
                ->where('sekolah_id', $sekolah_id)
                ->groupBy('sekolah_id', 'paket_id')
                ->get();
        }

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $agg);
    }

    public function ShowAgregasiSekolahNoKd($rayon_kd) {
        $agg = DB::table('ujian_siswa')
            ->selectRaw('sekolah_nama, sekolah_id, avg(jumlah_benar)/JSON_LENGTH(random_soal)*100 as avg, min(jumlah_benar)/JSON_LENGTH(random_soal)*100 as min, max(jumlah_benar)/JSON_LENGTH(random_soal)*100 as max, paket.nama, rayon_nama')
            ->join('paket', 'paket.id', 'ujian_siswa.paket_id')
            ->where('rayon_nama', '!=', 'null')
            ->where('rayon_kd', $rayon_kd)
            ->groupBy('paket_id')
            ->get();

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $agg);
    }

    public function ShowAllAgregasiUjian(Request $request) {
        $jadwal_ujian_id = $request->jadwal_ujian_id;

        $data = AgregasiHasil::GetAllHasilUjian($jadwal_ujian_id)->get();

        $jumlah_soal = UjianSiswa::GetUjianJumlahSoal($data[0]->id);

        $sum_nilai = [];
        $predikat= [];
        $predikat['sangat_baik'] = 0;
        $predikat['baik'] = 0;
        $predikat['cukup'] = 0;
        $predikat['kurang'] = 0;
        $predikat['n'] = 0;

        foreach ($data as $agg) {

            if ($agg->sekolah_id == null || $agg->sekolah_id == '' || $agg->sekolah_id == ' ') {
                continue;
            }

            switch($agg->predikat){
                case 'Sangat Baik':
                    $predikat['sangat_baik']++;
                    break;
                case 'Baik':
                    $predikat['baik']++;
                    break;
                case 'Cukup':
                    $predikat['cukup']++;
                    break;
                case 'Kurang':
                    $predikat['kurang']++;
                    break;
                default:
                    $predikat['n']++;
                    break;
            }

            if (!array_key_exists($agg->sekolah_id, $sum_nilai)) {
                $sum_nilai[$agg->sekolah_id] = array();
                array_push($sum_nilai[$agg->sekolah_id], $agg->sekolah_id);
                array_push($sum_nilai[$agg->sekolah_id], $agg->sekolah_nama);
                array_push($sum_nilai[$agg->sekolah_id], $agg->rayon_nama);
                array_push($sum_nilai[$agg->sekolah_id], $agg->rayon_kd);
            }

            $nilai = floatval($agg->jumlah_benar) / $jumlah_soal * 100;
            array_push($sum_nilai[$agg->sekolah_id], $nilai);
        }

        $response_data = [];
        $all_avgs = [];
        $all_min = 100.0;
        $all_max = 0.0;

        foreach ($sum_nilai as $agg) {

            $sekolah_id = array_shift($agg);
            $sekolah_nama = array_shift($agg);
            $rayon_nama = array_shift($agg);
            $rayon_kd = array_shift($agg);

            $tmp = [];

            if (!array_key_exists($sekolah_id, $response_data)) {
                $tmp['sekolah_id'] = $sekolah_id;
                $tmp['sekolah_nama'] = $sekolah_nama;
                $tmp['rayon_nama'] = $rayon_nama;
                $tmp['rayon_kd'] = $rayon_kd;
            }

            $jumlah_siswa = count($agg);

            $tmp['jumlah_siswa'] = $jumlah_siswa;

            if ($jumlah_siswa) {
                $avg = array_sum($agg) / $jumlah_siswa;
                $tmp['avg'] = $avg;
                array_push($all_avgs, $avg);
            } else {
                $tmp['avg'] = 0;
                array_push($all_avgs, $avg);
            }

            $tmp['min'] = min($agg);
            $tmp['max'] = max($agg);
            $tmp['standar_deviasi'] = $this->_Stand_Deviation($agg, $avg);

            $all_min = min($all_min, $tmp['min']) > 0 ? min($all_min, $tmp['min']) : 0;
            $all_max = max($all_max, $tmp['max']);

            array_push($response_data, $tmp);
        }

        $response = [];

        $response['total_average'] = array_sum($all_avgs) / count($all_avgs);
        $response['total_min'] = $all_min;
        $response['total_max'] = $all_max;
        $response['predikat'] = $predikat;
        $response['data'] = $response_data;

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $response);
    }

    public function IsUjianCorrected(Request $request) {
        $jadwal_ujian_id = $request->jadwal_ujian_id;

        $sisa = UjianSiswa::IsUjianFinishedCorrected($jadwal_ujian_id)->get();

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $sisa);
    }
}
