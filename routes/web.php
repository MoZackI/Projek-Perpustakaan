<?php

use App\Http\Controllers\Buku\BukuController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Kategori\KategoriController;
use App\Http\Controllers\ManajemenUser\ManajemenUserController;
use App\Http\Controllers\Peminjaman\PeminjamanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleCheck;
use App\Exports\BorrowedBooksExport;
use App\Exports\ReturnedBooksExport;
use App\Http\Controllers\KoleksiController;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/tambahKategori', [KategoriController::class, 'index'])->name('tambahKategori');
    Route::get('/tambahBuku', [BukuController::class, 'index'])->name('tambahBuku');
    Route::get('/manajemenUser', [ManajemenUserController::class, 'index'])->name('manajemenUser');
});

//Input kategori
Route::middleware('role' . ':admin,petugas')->group(function () {
    Route::get('/kategori', function () {
        return view('tambahKategori');
    })->name('kategori');
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.post');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.delete');
});

Route::middleware('role' . ':admin,petugas')->group(function () {
    Route::get('/buku', function () {
        return view('tambahBuku');
    })->name('buku');
    Route::get('/buku', [BukuController::class, 'Index'])->name('buku');
    Route::get('create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('store', [BukuController::class, 'store'])->name('buku.post');
    Route::get('{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('{id}', [BukuController::class, 'destroy'])->name('buku.delete');
    Route::get('/buku/store-file', [BukuController::class, 'storeFile'])->name('buku.store-file');
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');

    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
});
//input buku
// Route::middleware('roleCheck:admin,petugas')->group(function () {

// });

Route::middleware(['role' . ':admin'])->group(function () {
    Route::get('/manajemenUser', function () {
        return view('ManajemenUser');
    })->name('ManajemenUser');
    Route::get('/manajemenUser', [ManajemenUserController::class, 'index'])->name('manajemenUser');
    Route::post('/user', [ManajemenUserController::class, 'store'])->name('user.store');
});

//Tampilan dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/pinjam-buku/{buku_id}', [DashboardController::class, 'pinjamBuku'])->name('dashboard.pinjamBuku');
});

Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
Route::post('/peminjaman/return/{id}', [PeminjamanController::class, 'returnBook'])->name('peminjaman.return');
Route::post('/buku/{id}/ulas', [BukuController::class, 'addUlasan'])->name('buku.addUlasan');
Route::get('/buku/{id}/reviews', [BukuController::class, 'showWithReviews'])->name('buku.showWithReviews');
Route::post('/koleksi/{bukuId}/add', [BukuController::class, 'addToKoleksi'])->name('koleksi.add');
Route::get('/koleksi', [KoleksiController::class, 'index'])->name('koleksi.index');
// Hapus koleksi
Route::delete('/koleksi/{koleksi}', [KoleksiController::class, 'remove'])->name('koleksi.remove');

// export laporan
Route::get('/laporan/sedang-dipinjam', function () {
    return Excel::download(new BorrowedBooksExport, 'laporan_sedang_dipinjam.xlsx');
})->name('laporan.sedangDipinjam');

Route::get('/laporan/sudah-dikembalikan', function () {
    return Excel::download(new ReturnedBooksExport, 'laporan_sudah_dikembalikan.xlsx');
})->name('laporan.sudahDikembalikan');
require __DIR__ . '/auth.php';
