<?php

namespace App\Http\Controllers\Peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Users;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->role === 'petugas') {
            // Admin melihat semua riwayat
            $borrowedBooks = Peminjaman::with('buku', 'user')
                ->where('statusPeminjaman', 'Dipinjam')
                ->get()
                ->map(function ($peminjaman) {
                    // Pastikan tanggalPeminjaman adalah objek Carbon
                    $peminjaman->formattedTanggalPeminjaman = Carbon::parse($peminjaman->tanggalPeminjaman)->format('d M Y');

                    // Pastikan tenggatWaktu adalah objek Carbon jika tidak null
                    $peminjaman->formattedTenggatWaktu = $peminjaman->tenggatWaktu ? Carbon::parse($peminjaman->tenggatWaktu)->format('d M Y') : 'Tidak Ditetapkan';

                    return $peminjaman;
                });

            $returnedBooks = Peminjaman::with('buku', 'user')
                ->where('statusPeminjaman', 'dikembalikan')
                ->get()
                ->map(function ($peminjaman) {
                    $peminjaman->formattedTanggalPengembalian = $peminjaman->tanggalPengembalian->format('d M Y');
                    return $peminjaman;

                    if ($tanggalPengembalian->greaterThan($peminjaman->tenggatWaktu)) {
                        $hariTerlambat = $tanggalPengembalian->diffInDays($peminjaman->tenggatWaktu);
                        $peminjaman->denda = $hariTerlambat * 10000; // Rp. 10.000 per hari
                    }
                });
        } else {
            // Peminjam hanya melihat miliknya
            $borrowedBooks = Peminjaman::with('buku', 'user')
                ->where('user_id', $user->id) // Hanya peminjaman oleh pengguna saat ini
                ->where('statusPeminjaman', 'Dipinjam')
                ->get()
                ->map(function ($peminjaman) {
                    // Pastikan tanggalPeminjaman adalah objek Carbon
                    $peminjaman->formattedTanggalPeminjaman = Carbon::parse($peminjaman->tanggalPeminjaman)->format('d M Y');

                    // Pastikan tenggatWaktu adalah objek Carbon jika tidak null
                    $peminjaman->formattedTenggatWaktu = $peminjaman->tenggatWaktu ? Carbon::parse($peminjaman->tenggatWaktu)->format('d M Y') : 'Tidak Ditetapkan';

                    return $peminjaman;
                });

            $returnedBooks = Peminjaman::with('buku')
                ->where('user_id', $user->id) // Hanya pengembalian oleh pengguna saat ini
                ->where('statusPeminjaman', 'dikembalikan')
                ->get()
                ->map(function ($peminjaman) {
                    $peminjaman->formattedTanggalPengembalian = $peminjaman->tanggalPengembalian->format('d M Y');
                    if ($peminjaman->tanggalPengembalian->greaterThan($peminjaman->tenggatWaktu)) {
                        $hariTerlambat = $peminjaman->tanggalPengembalian->diffInDays($peminjaman->tenggatWaktu);
                        $peminjaman->denda = $hariTerlambat * 10000; // Rp. 10.000 per hari
                    }
                    return $peminjaman;
                });
        }

        return view('peminjaman.index', compact('borrowedBooks', 'returnedBooks'));
    }


    public function pinjamBuku(Request $request, $buku_id)
    {
        $buku = Buku::findOrFail($buku_id);
        $user = Auth::user();

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

        return redirect()->route('dashboard')->with('success', 'Buku berhasil dipinjam!');
    }



    public function returnBook($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $tanggalPengembalian = now();
        $peminjaman->tanggalPengembalian = $tanggalPengembalian;

        // Hitung denda jika terlambat
        if ($tanggalPengembalian->greaterThan($peminjaman->tenggatWaktu)) {
            $hariTerlambat = $tanggalPengembalian->diffInDays($peminjaman->tenggatWaktu);
            $peminjaman->denda = $hariTerlambat * 10000; // Rp. 10.000 per hari
        }

        $peminjaman->statusPeminjaman = 'dikembalikan';
        $peminjaman->save();

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dikembalikan!');
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
        $peminjaman = Peminjaman::findOrFail($id);

        // Tambahkan logika tambahan jika diperlukan, misalnya validasi hak akses
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'petugas') {
            $peminjaman->delete();
            return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
        }

        return redirect()->route('peminjaman.index')->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
    }
}
