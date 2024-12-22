<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $fillable = ['judul', 'penulis', 'penerbit', 'kategoriBuku_id', 'image', 'tahunTerbit'];

    /**
     * Relasi ke model Kategori.
     * Satu buku memiliki satu kategori (belongsTo).
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategoriBuku_id');
    }

    // Model Buku
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id', 'id');
    }

    public function getStatusBukuAttribute(): string
    {
        // Periksa jika semua peminjaman terkait sudah selesai (dikembalikan)
        $isTersedia = $this->peminjaman->every(fn($p) => $p->statusPeminjaman === 'dikembalikan');

        return $isTersedia ? 'tersedia' : 'dipinjam';
    }

    // Relasi Buku ke Ulasan (One to Many)
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'buku_id'); // 'buku_id' adalah kolom foreign key di tabel ulasans
    }

    public function koleksi()
    {
        return $this->hasMany(Koleksi::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'koleksi');
    }
}
