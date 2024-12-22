<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function showKoleksi()
    {
        // Mendapatkan koleksi buku milik pengguna yang sedang login
        $koleksi = Auth::user()->koleksi;

        return view('koleksi.index', compact('koleksi'));
    }
}
