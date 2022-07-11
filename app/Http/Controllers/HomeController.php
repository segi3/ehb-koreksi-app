<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        if (Gate::allows('isAdmin')) return view('home');
        else return view('hasil');

        return view('hasil');
    }

    public function ShowKoreksi() {
        return view('koreksi');
    }

    public function ShowHasilBak() {
        return view('hasil');
    }

    public function ShowHasilDev() {
        return view('hasil');
    }

    public function ShowAgregasiRayon() {
        return view('hasil-rayon');
    }

    public function ShowAgregasiKisi() {
        return view('agregasi-kisi');
    }

    public function ShowDetailKisi() {
        return view('detail-kisi');
    }

    public function ShowMutuKisi() {
        return view('mutu-kisi');
    }
}

