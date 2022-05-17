<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

use App\Http\Resources\SiswaResource;

class SiswaController extends Controller
{

    public function index()
    {
        //get posts
        $siswa = User::AllUsers();

        //return collection of posts as a resource
        // return new SiswaResource(true, 'List of Users', $siswa);
    }

}
