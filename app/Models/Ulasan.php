<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;
    // Pastikan nama tabel sesuai
    protected $table = 'ulasan';  // Nama tabel yang benar
    protected $fillable = [
        'buku_id',
        'user_id',
        'ulasan',
        'rating',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
