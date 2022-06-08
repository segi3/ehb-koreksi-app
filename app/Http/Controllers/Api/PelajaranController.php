<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pelajaran;

use App\Http\Resources\GenericResponse;
use App\Enums\ResponseStatus;

class PelajaranController extends Controller
{
    public function GetAvailablePelajaran() {
        $pelajaran = Pelajaran::GetAvailablePelajaran()->get();

        return new GenericResponse(true, ResponseStatus::SUCCESS()->value, $pelajaran);
    }
}
