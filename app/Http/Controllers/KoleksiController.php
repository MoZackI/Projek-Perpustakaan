<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Koleksi;

class KoleksiController extends Controller
{
    /**
     * Menampilkan koleksi buku milik pengguna yang sedang login
     */
    public function index()
    {
        // Mendapatkan koleksi buku milik pengguna yang sedang login
        $koleksi = Auth::user()->koleksi;

        // Mengembalikan view dengan koleksi buku
        return view('koleksi.index', compact('koleksi'));
    }

    // Fungsi untuk menghapus koleksi
    public function remove(Koleksi $koleksi)
    {
        // Hapus koleksi
        $koleksi->delete();

        // Redirect kembali ke halaman koleksi dengan pesan sukses
        return redirect()->route('koleksi.index')->with('success', 'Buku berhasil dihapus dari koleksi.');
    }

    
}
