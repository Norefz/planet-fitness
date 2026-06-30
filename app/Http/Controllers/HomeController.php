<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }
    public function showLoginSelection()
    {
        return view('member.login');
    }

    // Show the general registration selection page
    public function showRegisterSelection()
    {
        return view('member.register');
    }
}
