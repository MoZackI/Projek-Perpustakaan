<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $maxData = 10; // Batas jumlah data per halaman
        $search = $request->input('search', ''); // Ambil input pencarian jika ada
        $query = Buku::query();

        // Filter berdasarkan pencarian
        if (!empty($search)) {
            $query->where('judul', 'like', '%' . $search . '%')
                ->orWhere('penulis', 'like', '%' . $search . '%')
                ->orWhereHas('kategori', function ($kategoriQuery) use ($search) {
                    $kategoriQuery->where('namaKategori', 'like', '%' . $search . '%');
                });
        }

        // Tambahkan eager loading untuk relasi peminjaman
        $buku = $query->with('peminjaman')
            ->orderBy('judul', 'asc') // Urutkan berdasarkan judul
            ->paginate($maxData)
            ->withQueryString(); // Tambahkan parameter query ke pagination links

        // Ambil semua kategori
        $kategori = Kategori::all();

        // Kembalikan ke view dengan data
        return view('dashboard', [
            'buku' => $buku,
            'kategori' => $kategori,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // Fungsi untuk meminjam buku
    public function pinjamBuku(Request $request, $buku_id)
    {
        $buku = Buku::findOrFail($buku_id);
        $user = Auth::user();

        // Periksa apakah user sudah meminjam buku ini dan belum mengembalikannya
        $existingPeminjaman = Peminjaman::where('buku_id', $buku->id)
            ->where('user_id', $user->id)
            ->where('statusPeminjaman', 'Dipinjam')
            ->first();

        if ($existingPeminjaman) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya.');
        }

        // Setel tanggal peminjaman ke tanggal saat ini
        $tanggalPeminjaman = Carbon::now();

        // Hitung tenggat waktu (4 hari setelah tanggal peminjaman)
        $tenggatWaktu = $tanggalPeminjaman->copy()->addDays(4);

        // Simpan peminjaman
        Peminjaman::create([
            'user_id' => $user->id,
            'buku_id' => $buku->id,
            'tanggalPeminjaman' => $tanggalPeminjaman,
            'tenggatWaktu' => $tenggatWaktu,
            'tanggalPengembalian' => null,
            'statusPeminjaman' => 'Dipinjam',
            'denda' => 0, // Denda awal adalah 0
        ]);

        // Redirect ke halaman dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Buku berhasil dipinjam!');
    }
}
