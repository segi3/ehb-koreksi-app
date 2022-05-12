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
        return new SiswaResource(true, 'List of Users', $siswa);
    }

    public function ShowByUjian(Request $request)
    {
        $siswa = User::AllUsers();

        $validator = Validator::make($request->all(), [
            'test'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return new SiswaResource(true, $request->test, $siswa);
    }
}
