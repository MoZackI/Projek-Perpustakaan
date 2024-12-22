<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoribuku';
    protected $fillable = ["namaKategori"];

    // protected $casts = [
    //     'namaKategori' => 'string',
    // ];


    /**
     * Relasi ke model Buku.
     * Satu kategori memiliki banyak buku (hasMany).
     */
    public function buku()
    {
        return $this->hasMany(Buku::class, 'kategoriBuku_id');
    }
}
