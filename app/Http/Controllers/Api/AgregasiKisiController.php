<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AgregasiKisi;

use App\Http\Resources\GenericResponse;
use App\Enums\ResponseStatus;

class AgregasiKisiController extends Controller
{

    public function GetAgregasiKisiMapel($mapel) {
        $agg = AgregasiKisi::GetAgregasiKisi($mapel)->get();
        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $agg);
    }

}
