<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    // DS
    public function home() {
        return view('static_pages/home');
    }

    public function help() {
        return view('statis_pages/help');
    }

    public function about() {
        return view('static_pages/about');
    }
}
