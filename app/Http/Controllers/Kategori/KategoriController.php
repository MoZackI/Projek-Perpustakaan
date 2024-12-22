<?php

namespace App\Http\Controllers\Kategori;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\User;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil kata kunci pencarian dari request
        $search = $request->input('search');

        // Query untuk mengambil kategori dengan filter berdasarkan namaKategori
        $kategori = Kategori::when($search, function ($query, $search) {
            return $query->where('namaKategori', 'like', "%{$search}%");
        })->get(); // Jika tidak ada search, semua kategori akan ditampilkan

        // Kirim data kategori dan search ke view
        return view('tambahKategori', compact('kategori', 'search'));
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
        $request->validate([
            'namaKategori' => 'required|min:3|max:50'
        ], [
            'namaKategori.required' => 'Kategori wajib diisi',
            'namaKategori.min' => 'Minimal 3 karakter',
            'namaKategori.max' => 'Maximal 50 karakter',
        ]);

        $kategori = [
            'namaKategori' => $request->namaKategori
        ];

        Kategori::create($kategori);
        return redirect()->route('kategori')->with('success', 'Berhasil disimpan');
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
        $request->validate([
            'namaKategori' => 'required|min:3|max:50'
        ], [
            'namaKategori.required' => 'Kategori wajib diisi',
            'namaKategori.min' => 'Minimal 3 karakter',
            'namaKategori.max' => 'Maximal 50 karakter',
        ]);

        $kategori = [
            'namaKategori' => $request->namaKategori
        ];

        Kategori::where('id', $id)->update($kategori);
        return redirect()->route('kategori')->with('success', 'Berhasil Update Data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Kategori::where('id', $id)->delete();
        return redirect()->route('kategori')->with('success', 'Berhasil Hapus Data');
    }
}
