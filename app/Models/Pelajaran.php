<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Pelajaran extends Model
{
    use HasFactory;

    protected $table = 'pelajaran';

    protected $primaryKey = 'id';

    protected function GetAvailablePelajaran() {
        return DB::table('pelajaran')
                ->select('id', 'nama');
    }

}
