<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function DownloadMutuKisiMapel($mapel) {
        $target = $mapel . '_mutusoal.xls';
        $target = 'Antropologi_mutusoal.xls';
        return Storage::download('public/storage/mutu_kisi/' . $target);
    }
}
