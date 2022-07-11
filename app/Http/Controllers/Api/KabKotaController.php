<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\ResponseStatus;
use Illuminate\Support\Facades\DB; //TODO: MOVE QUERY TO MODEL
use App\Http\Resources\GenericResponse;

class KabKotaController extends Controller
{
    public function GetActiveKabKota() {
        $kabkota = DB::table('kab_kota')->get();
        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $kabkota);
    }
}
