<?php

namespace App\Http\Controllers\Buku;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Koleksi;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query untuk menampilkan daftar buku, dengan pencarian dan kategori
        $buku = Buku::with('kategori:id,namaKategori')
            ->where(function ($query) use ($search) {
                // Jika ada keyword pencarian, cari berdasarkan judul, penulis, atau kategori
                if ($search) {
                    $query->where('judul', 'like', "%{$search}%")
                        ->orWhere('penulis', 'like', "%{$search}%")
                        ->orWhereHas('kategori', function ($q) use ($search) {
                            $q->where('namaKategori', 'like', "%{$search}%");
                        });
                }
            })
            ->paginate(4);

        $kategori = Kategori::all();  // Mengambil semua kategori

        return view('tambahBuku', compact('buku', 'kategori', 'search'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('tambahBuku', compact('kategori'));
    }


    /**
     * Store a newly created resource in storage.
     */
    // Controller method untuk menyimpan gambar
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahunTerbit' => 'required|integer',
            'kategoriBuku_id' => 'required|exists:kategoribuku,id',
        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }

        Buku::create($input);

        return redirect()->route('buku')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function storeFile()
    {
        // Simpan file
        $path = Storage::disk('private')->put('example.txt', 'Ini adalah isi file.');

        // Ambil file
        $content = Storage::disk('private')->get('example.txt');

        return response()->json([
            'path' => $path,
            'content' => $content,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buku = Buku::with('kategori:id,namaKategori')->findOrFail($id);  // Menampilkan detail buku berdasarkan ID
        return view('buku.show', compact('buku'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil buku berdasarkan ID
        $buku = Buku::findOrFail($id);

        // Ambil semua kategori
        $kategori = Kategori::all();

        // Kirim data ke view
        return view('tambahBuku', compact('buku', 'kategori'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::findOrFail($id);

        $rules = [
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'kategoriBuku_id' => 'required|exists:kategoribuku,id',
            'tahunTerbit' => 'required',
        ];

        // Jika ada gambar yang diupload, tambahkan aturan validasi untuk gambar
        if ($request->hasFile('image')) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        // Validasi request
        $request->validate($rules);

        // Proses data buku tanpa gambar sementara
        $data = $request->except('image');

        // Jika ada gambar baru, simpan gambar baru
        if ($image = $request->file('image')) {
            // Menghapus gambar lama jika ada
            if ($buku->image && Storage::exists('images/' . $buku->image)) {
                Storage::delete('images/' . $buku->image);
            }

            // Menyimpan gambar baru
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            // Menambahkan gambar baru ke data
            $data['image'] = $imageName;
        }

        // Update data buku di database
        $buku->update($data);

        return redirect()->route('buku')->with('success', 'Buku berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);

        // Hapus peminjaman yang terkait
        $buku->peminjaman()->delete();

        // Hapus data buku dari database
        $buku->delete();

        return redirect()->route('buku')->with('success', 'Buku berhasil dihapus!');
    }

    /**
     * Menampilkan ulasan untuk buku tertentu.
     */
    public function showWithReviews(string $id)
    {
        $buku = Buku::with('kategori:id,namaKategori', 'ulasan.user:id,name')->findOrFail($id); // Menampilkan detail buku dan ulasan terkait

        return view('buku.show', compact('buku'));
    }

    /**
     * Menambahkan ulasan untuk buku tertentu.
     */
    public function addUlasan(Request $request, $id)
    {
        // Validasi input ulasan
        $request->validate([
            'ulasan' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Pastikan user yang masuk (login)
        if (Auth::check()) {
            // Menyimpan ulasan
            Ulasan::create([
                'buku_id' => $id,
                'user_id' => Auth::id(),  // Menggunakan ID pengguna yang sedang login
                'ulasan' => $request->ulasan,
                'rating' => $request->rating,
            ]);

            // Redirect ke halaman detail buku setelah ulasan ditambahkan
            return redirect()->route('buku.showWithReviews', $id)->with('success', 'Ulasan berhasil ditambahkan!');
        }

        // Jika user belum login, redirect ke halaman login
        return redirect()->route('login')->with('error', 'Anda harus login untuk memberikan ulasan.');
    }

    public function addToKoleksi($bukuId)
    {
        // Mendapatkan buku berdasarkan ID
        $buku = Buku::findOrFail($bukuId);

        // Memeriksa apakah buku sudah ada di koleksi pengguna
        $existingKoleksi = Koleksi::where('user_id', Auth::id())
            ->where('buku_id', $buku->id)
            ->first();

        if ($existingKoleksi) {
            // Jika buku sudah ada di koleksi, tampilkan pesan
            return redirect()->route('buku.showWithReviews', $bukuId)
                ->with('success', 'Buku ini sudah ada di koleksi Anda.');
        }

        // Menambahkan buku ke koleksi milik user
        $koleksi = new Koleksi();
        $koleksi->user_id = Auth::id();
        $koleksi->buku_id = $buku->id;
        $koleksi->save();

        // Redirect kembali ke halaman buku dengan pesan sukses
        return redirect()->route('buku.showWithReviews', $bukuId)
            ->with('success', 'Buku berhasil ditambahkan ke koleksi Anda.');
    }
}
