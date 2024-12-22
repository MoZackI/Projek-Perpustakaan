<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReturnedBooksExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Mengambil data peminjaman buku yang sudah dikembalikan
        return Peminjaman::with('buku', 'user')
            ->where('statusPeminjaman', 'dikembalikan')
            ->get()
            ->map(function ($peminjaman) {
                return [
                    'Judul Buku' => $peminjaman->buku->judul,
                    'Nama Peminjam' => $peminjaman->user->name,
                    'Tanggal Kembali' => optional($peminjaman->tanggalPengembalian)->format('d M Y'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Judul Buku',
            'Nama Peminjam',
            'Tanggal Kembali',
        ];
    }
}
