<x-app-layout>
    <div class="container py-5">
        <h1 class="text-4xl font-bold mb-4">Koleksi Buku Saya</h1>

        @if ($koleksi->isEmpty())
            <p class="text-gray-600">Anda belum menambahkan buku ke koleksi Anda.</p>
        @else
            <div class="space-y-4">
                @foreach ($koleksi as $koleksiItem)
                    <div class="p-4 bg-gray-100 rounded-lg shadow">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-semibold mb-2 text-black">{{ $koleksiItem->buku->judul }}</h3>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('koleksi.remove', $koleksiItem->id) }}" method="POST" class="ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700">Hapus</button>
                            </form>
                        </div>

                        <p class="text-lg mb-2 text-black"><strong>Penulis:</strong> {{ $koleksiItem->buku->penulis }}</p>
                        <p class="text-lg mb-2 text-black"><strong>Penerbit:</strong> {{ $koleksiItem->buku->penerbit }}</p>
                        <p class="text-lg mb-2 text-black"><strong>Tahun Terbit:</strong> {{ $koleksiItem->buku->tahunTerbit }}</p>

                        <!-- Link ke Detail Buku -->
                        <a href="{{ route('buku.showWithReviews', $koleksiItem->buku->id) }}"
                            class="text-blue-600 hover:underline">Lihat Detail Buku</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
