<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Si el usuario es admin, mostrar el dashboard
        if (Auth::user()->hasRole('admin')) {
            return view('dashboard');
        }
    }
}
