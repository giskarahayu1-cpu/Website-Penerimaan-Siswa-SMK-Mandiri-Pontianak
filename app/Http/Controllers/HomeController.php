<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function about()
    {
        return view('about');
    }

    public function visiMisi()
    {
        return view('visi-misi');
    }

    public function jurusan()
    {
        return view('jurusan');
    }

    public function fasilitas()
    {
        return view('fasilitas');
    }
}
