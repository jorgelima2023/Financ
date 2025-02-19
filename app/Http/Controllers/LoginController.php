<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    // Login
    public function index()
    {
        // Carregar a VIEW
        return view('login.index');
    }
}
