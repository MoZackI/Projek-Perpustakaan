<x-app-layout>
    <main class="px-6 py-8 bg-gray-900 min-h-screen">
        <!-- Navbar -->
        <nav class="bg-blue-600 p-4 text-white">
            <div class="container mx-auto flex justify-between items-center">
                <a href="#" class="text-2xl font-bold">Library</a>
                <div class="space-x-4">
                    <a href="#borrowed" class="hover:underline">Sedang Terpinjam</a>
                    <a href="#returned" class="hover:underline">Sudah Dikembalikan</a>
                </div>
            </div>
        </nav>

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'petugas'))
            <div class="container mx-auto mt-6">
                <section>
                    <h2 class="text-2xl font-semibold mb-4 text-white">Generate Laporan</h2>
                    <div class="space-x-4">
                        <a href="{{ route('laporan.sedangDipinjam') }}"
                            class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                            Unduh Laporan Sedang Dipinjam
                        </a>
                        <a href="{{ route('laporan.sudahDikembalikan') }}"
                            class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                            Unduh Laporan Sudah Dikembalikan
                        </a>
                    </div>
                </section>
            </div>
        @endif

        <!-- List Buku -->
        <div class="container mx-auto mt-6">
            <!-- Sedang Terpinjam -->
            <section id="borrowed">
                <h2 class="text-2xl font-semibold mb-4 text-white">Sedang Terpinjam</h2>
                <div class="bg-white p-4 rounded-md shadow-md overflow-x-auto text-black">
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2">Buku</th>
                                <th class="px-4 py-2">Peminjam</th>
                                <th class="px-4 py-2">Tanggal Pinjam</th>
                                <th class="px-4 py-2">Tenggat Waktu</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($borrowedBooks as $peminjaman)
                                <tr class="bg-gray-100 border-b">
                                    <td class="px-4 py-2">{{ $peminjaman->buku->judul }}</td>
                                    <td class="px-4 py-2">{{ $peminjaman->user->name }}</td>
                                    <td class="px-4 py-2">{{ $peminjaman->formattedTanggalPeminjaman }}</td>
                                    <td class="px-4 py-2">{{ $peminjaman->formattedTenggatWaktu }}</td>
                                    <td class="px-4 py-2">
                                        @if (auth()->user() && auth()->user()->role === 'peminjam')
                                            <form action="{{ route('peminjaman.return', $peminjaman->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="text-sm text-blue-600 hover:underline">
                                                    Kembalikan
                                                </button>
                                            </form>
                                        @endif
                                        @if (auth()->user() && (auth()->user()->role === 'admin' || auth()->user()->role === 'petugas'))
                                            <form action="{{ route('peminjaman.destroy', $peminjaman->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:underline">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada buku yang sedang
                                        dipinjam.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Sudah Dikembalikan -->
            <section id="returned" class="mt-8">
                <h2 class="text-2xl font-semibold mb-4 text-white">Sudah Dikembalikan</h2>
                <div class="bg-white p-4 rounded-md shadow-md overflow-x-auto text-black">
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2">Buku</th>
                                <th class="px-4 py-2">Peminjam</th>
                                <th class="px-4 py-2">Tanggal Kembali</th>
                                <th class="px-4 py-2">Denda</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($returnedBooks as $peminjaman)
                                <tr class="bg-gray-100 border-b">
                                    <td class="px-4 py-2">{{ $peminjaman->buku->judul }}</td>
                                    <td class="px-4 py-2">{{ $peminjaman->user->name }}</td>
                                    <td class="px-4 py-2">
                                        {{ optional($peminjaman->tanggalPengembalian)->format('d M Y') }}</td>
                                    <td class="px-4 py-2">Rp. {{ number_format($peminjaman->denda, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">
                                        @if (auth()->user() && (auth()->user()->role === 'admin' || auth()->user()->role === 'petugas'))
                                            <form action="{{ route('peminjaman.destroy', $peminjaman->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:underline">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada buku yang telah
                                        dikembalikan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        @include('layouts.footer')
    </main>
</x-app-layout>
