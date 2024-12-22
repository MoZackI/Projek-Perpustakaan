<x-app-layout>
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <main class="px-6 py-8 bg-gray-900 min-h-screen">
        <!-- Search Box -->
        <form id="buku-form" action="{{ route('dashboard') }}" method="GET">
            <div class="mb-6">
                <input type="text" id="cari" name="search" placeholder="Masukkan kata kunci"
                    class="w-full px-4 py-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
                    value="{{ request('search') }}" />
            </div>
        </form>

        <!-- List Buku -->
        <div class="bg-gray-100 min-h-screen p-6">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">Daftar Buku</h1>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    @foreach ($buku as $item)
                        <div
                            class="block bg-white shadow-md rounded-lg overflow-hidden border border-black transform transition-transform duration-300 hover:scale-105 hover:shadow-lg">

                            <!-- Gambar Buku (Klik untuk Show Page) -->
                            <a href="{{ route('buku.showWithReviews', $item->id) }}">
                                <div class="relative aspect-w-1 aspect-h-1">
                                    <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->judul }}"
                                        class="absolute inset-0 w-full h-full object-cover">
                                </div>
                            </a>

                            <!-- Content Section -->
                            <div class="p-4 text-center">
                                <h2 class="font-bold text-gray-800 text-base truncate">{{ $item->judul }}</h2>
                                <p class="text-gray-600 text-sm">Tahun: {{ $item->tahunTerbit }}</p>

                                @if (auth()->check() && auth()->user()->role === 'peminjam')
                                    @php
                                        // Logika peminjaman buku
                                        $existingPeminjaman =
                                            $item->peminjaman->firstWhere('user_id', auth()->user()->id) &&
                                            $item->peminjaman->firstWhere('statusPeminjaman', 'Dipinjam');

                                        $isBookAlreadyBorrowedByOthers = $item->peminjaman
                                            ->where('statusPeminjaman', 'Dipinjam')
                                            ->where('user_id', '!=', auth()->user()->id)
                                            ->isNotEmpty();
                                    @endphp

                                    <div class="mt-4">
                                        @if (!$existingPeminjaman && !$isBookAlreadyBorrowedByOthers)
                                            <!-- Tombol Pinjam Buku dengan Form -->
                                            <form action="{{ route('dashboard.pinjamBuku', $item->id) }}" method="POST"
                                                id="pinjam-form-{{ $item->id }}">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full bg-blue-500 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300">
                                                    Pinjam Buku!
                                                </button>
                                            </form>
                                        @elseif ($isBookAlreadyBorrowedByOthers)
                                            <!-- Buku dipinjam user lain -->
                                            <p class="w-full text-red-500 font-medium text-sm">
                                                Buku ini sudah dipinjam oleh user lain.
                                            </p>
                                        @else
                                            <!-- User sudah meminjam buku ini -->
                                            <p class="w-full text-red-500 font-medium text-sm">
                                                Anda sudah meminjam buku ini dan belum mengembalikannya.
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $buku->links() }}
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </main>

    <!-- JavaScript -->
    <script>
        // Fungsi untuk mengatur tanggal peminjaman dengan tanggal hari ini
        function setTanggalPeminjaman(bukuId) {
            var today = new Date();
            var dateString = today.toISOString().split('T')[0]; // Format YYYY-MM-DD
            document.getElementById('tanggalPeminjaman-' + bukuId).value = dateString;

            // Menentukan tenggat waktu (4 hari setelah tanggal peminjaman)
            var tenggatWaktu = new Date(today);
            tenggatWaktu.setDate(today.getDate() + 4); // Menambahkan 4 hari

            // Format tenggat waktu menjadi YYYY-MM-DD
            var tenggatWaktuString = tenggatWaktu.toISOString().split('T')[0];

            // Menampilkan tenggat waktu
            document.getElementById('tenggatWaktu-' + bukuId).value = tenggatWaktuString;
            document.getElementById('tenggat-text-' + bukuId).innerText = "Tenggat Waktu: " + tenggatWaktu
                .toLocaleDateString();
        }
    </script>
</x-app-layout>
