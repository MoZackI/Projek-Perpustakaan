<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BorrowedBooksExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Mengambil data peminjaman buku yang sedang dipinjam
        return Peminjaman::with('buku', 'user')
            ->where('statusPeminjaman', 'Dipinjam')
            ->get()
            ->map(function ($peminjaman) {
                return [
                    'Judul Buku' => $peminjaman->buku->judul,
                    'Nama Peminjam' => $peminjaman->user->name,
                    'Tanggal Pinjam' => optional($peminjaman->tanggalPeminjaman)->format('d M Y'),
                    // Pastikan tenggatWaktu diubah menjadi objek Carbon jika ada
                    'Tenggat Waktu' => $peminjaman->tenggatWaktu
                        ? Carbon::parse($peminjaman->tenggatWaktu)->format('d M Y')
                        : 'Tidak Ditetapkan', // Menambahkan fallback jika tenggatWaktu null
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Judul Buku',
            'Nama Peminjam',
            'Tanggal Pinjam',
            'Tenggat Waktu',
        ];
    }
}
