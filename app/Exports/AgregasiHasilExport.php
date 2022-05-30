<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use App\Models\AgregasiHasil;
use App\Models\UjianSiswa;

class AgregasiHasilExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles
{
    protected $jadwal_ujian_id;

    function __construct($jadwal_ujian_id) {
            $this->jadwal_ujian_id = $jadwal_ujian_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = AgregasiHasil::GetAllHasilUjian($this->jadwal_ujian_id)->get();

        $jumlah_soal = UjianSiswa::GetUjianJumlahSoal($data[0]->id);

        $sum_nilai = [];

        foreach ($data as $agg) {

            if ($agg->sekolah_id == null || $agg->sekolah_id == '' || $agg->sekolah_id == ' ') {
                continue;
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

        foreach ($sum_nilai as $agg) {

            $sekolah_id = array_shift($agg);
            $sekolah_nama = array_shift($agg);
            $rayon_nama = array_shift($agg);
            $rayon_kd = array_shift($agg);

            $tmp = [];

            if (!array_key_exists($sekolah_id, $response_data)) {
                $tmp['sekolah_nama'] = $sekolah_nama;
                $tmp['rayon_nama'] = $rayon_nama;
                $tmp['rayon_kd'] = $rayon_kd;
            }

            $jumlah_siswa = count($agg);

            if ($jumlah_siswa) {
                $avg = array_sum($agg) / $jumlah_siswa;
                $tmp['avg'] = $avg;
            } else {
                $tmp['avg'] = 0;
            }

            $tmp['min'] = min($agg);
            $tmp['max'] = max($agg);

            array_push($response_data, $tmp);
        }

        return collect($response_data);


    }

    public function columnFormats(): array {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Sekolah',
            'Rayon',
            'Kode Rayon',
            'Nilai Rata-rata',
            'Nilai Min',
            'Nilai Max'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]]
        ];
    }
}
