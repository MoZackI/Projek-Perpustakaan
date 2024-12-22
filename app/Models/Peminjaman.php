<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggalPeminjaman',
        'tenggatWaktu',
        'statusPeminjaman',
        'tanggalPengembalian',
        'denda'
    ];

    protected $date = ['tenggatWaktu', 'tanggalPeminjaman'];
    use SoftDeletes;

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
    protected $dates = ['deleted_at'];
    // Cast tanggalPeminjaman dan tanggalPengembalian ke Carbon
    protected $casts = [
        'tanggalPeminjaman' => 'datetime',
        'tanggalPengembalian' => 'datetime',
    ];
}
