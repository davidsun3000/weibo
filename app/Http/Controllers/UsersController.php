<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    // DS
    public function create() {
        return view('users.create');
    }
}
