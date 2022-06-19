<?php

namespace App\Http\Controllers;
use App\Models\ShortUrls;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        return view('login');
    }
    public function login(Request $request)
    {
        return view('dashboard');
    }
    public function register(Request $request)
    {
        return view('register');
    }
    public function dashboard(Request $request)
    {
        $url_count = ShortUrls::orderBy('id','desc')->count();
        
        return view('dashboard', compact('url_count'));
    }
    
}
