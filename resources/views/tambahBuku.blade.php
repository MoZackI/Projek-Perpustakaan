<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tambah dan Edit Buku</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-100 flex items-center justify-items-center min-h-screen pt-3">
        <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md mx-auto">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Form Tambah Buku</h2>

            <!-- Tombol untuk menampilkan form Tambah Buku -->
            <div class="mb-4">
                <button id="btn-tambah-buku"
                    class="flex items-center justify-items-center  bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-600 transition duration-300">
                    Tambah Buku
                </button>
            </div>

            <!-- Form Tambah Buku -->
            <form id="form-tambah-buku" action="{{ route('buku.post') }}" method="POST" enctype="multipart/form-data"
                class="hidden">
                @csrf

                <!-- Nama Buku -->
                <div class="mb-4">
                    <label for="nama-buku" class="block text-black font-medium mb-2">Judul Buku</label>
                    <input type="text" id="nama-buku" name="judul"
                        class="w-full px-3 py-2 text-black border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        placeholder="Masukkan nama buku" value="{{ old('judul') }}" required>
                </div>

                <!-- Pengarang -->
                <div class="mb-4">
                    <label for="penulis" class="block text-black font-medium mb-2">Penulis</label>
                    <input type="text" id="penulis" name="penulis"
                        class="w-full px-3 py-2 text-black border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        placeholder="Masukkan nama penulis" value="{{ old('penulis') }}" required>
                </div>

                <!-- Penerbit -->
                <div class="mb-4">
                    <label for="penerbit" class="block text-black font-medium mb-2">Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit"
                        class="w-full px-3 py-2 text-black border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        placeholder="Masukkan nama penerbit" value="{{ old('penerbit') }}" required>
                </div>

                <!-- Tahun Terbit -->
                <div class="mb-4">
                    <label for="tahun-terbit" class="block text-black font-medium mb-2">Tahun Terbit</label>
                    <input type="number" id="tahun-terbit" name="tahunTerbit"
                        class="w-full px-3 py-2 text-black border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        placeholder="Masukkan tahun terbit" value="{{ old('tahunTerbit') }}" required>
                </div>

                <!-- Kategori Buku -->
                <div class="mb-4">
                    <label for="kategoriBuku_id" class="block text-black font-medium mb-2">Kategori Buku</label>
                    <select id="kategoriBuku_id" name="kategoriBuku_id"
                        class="w-full px-3 py-2 text-black border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}">{{ $item->namaKategori }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Gambar Buku -->
                <div class="mb-4">
                    <label for="gambar-buku" class="block text-black font-medium mb-2">Gambar Buku</label>
                    <input type="file" id="gambar-buku" name="image"
                        class="w-full px-3 py-2 text-black border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        accept="image/*">
                    @if (isset($item->image))
                        <div class="mt-2">
                            <img src="{{ asset('storage/images/' . $item->image) }}" alt="Gambar Buku"
                                class="w-32 h-32 object-cover rounded-lg">
                        </div>
                    @endif
                    <small class="text-gray-600">Kosongkan jika tidak ingin mengganti gambar</small>
                </div>

                <!-- Tombol Submit Tambah Buku -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition duration-300">
                        Tambah Buku
                    </button>
                </div>
            </form>
        </div>

        <!-- List Buku -->
        <div class="bg-gray-800 p-10 rounded-lg shadow-lg mt-10">
            <h2 class="text-2xl font-bold mb-4 text-white">Daftar Buku</h2>
            <div id="list-buku" class="space-y-4">
                @foreach ($buku as $item)
                    <div class="flex items-start gap-4 bg-red-50 p-4 rounded-lg shadow border border-red-400">
                        <!-- Gambar Buku -->
                        <div class="mb-4">
                            <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->judul }}"
                                class="w-32 h-32 object-cover rounded-lg">
                        </div>

                        <!-- Informasi Buku -->
                        <div class="flex-1">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800">Judul Buku: <span
                                        class="font-normal">{{ $item->judul }}</span></h3>
                                <p class="text-sm text-gray-600">Pengarang: {{ $item->penulis }}</p>
                                <p class="text-sm text-gray-600">Tahun Terbit: {{ $item->tahunTerbit }}</p>
                                <p class="text-sm text-gray-600">Kategori: {{ $item->kategori->namaKategori }}</p>
                            </div>

                            <!-- Tombol Edit Buku -->
                            <button
                                class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-yellow-600 transition duration-300 mb-4"
                                onclick="toggleForm('form-edit-{{ $item->id }}')">
                                Edit Buku
                            </button>

                            <!-- Form Update Buku -->
                            <form id="form-edit-{{ $item->id }}" action="{{ route('buku.update', $item->id) }}"
                                method="POST" enctype="multipart/form-data" class="hidden text-black">
                                @csrf
                                @method('PUT')

                                <!-- Inputan Form -->
                                <div>
                                    <label for="nama-buku" class="block text-black font-medium mb-2">Judul Buku</label>
                                    <input type="text" id="nama-buku" name="judul"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-yellow-300"
                                        value="{{ old('judul', $item->judul) }}" required>
                                </div>

                                <div>
                                    <label for="penulis" class="block text-black font-medium mb-2">Penulis</label>
                                    <input type="text" id="penulis" name="penulis"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-yellow-300"
                                        value="{{ old('penulis', $item->penulis) }}" required>
                                </div>

                                <div>
                                    <label for="penerbit" class="block text-black font-medium mb-2">Penerbit</label>
                                    <input type="text" id="penerbit" name="penerbit"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-yellow-300"
                                        value="{{ old('penerbit', $item->penerbit) }}" required>
                                </div>

                                <div>
                                    <label for="tahun-terbit" class="block text-black font-medium mb-2">Tahun
                                        Terbit</label>
                                    <input type="number" id="tahun-terbit" name="tahunTerbit"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-yellow-300"
                                        value="{{ old('tahunTerbit', $item->tahunTerbit) }}" required>
                                </div>

                                <div>
                                    <label for="kategoriBuku_id" class="block text-black font-medium mb-2">Kategori
                                        Buku</label>
                                    <select id="kategoriBuku_id" name="kategoriBuku_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-yellow-300"
                                        required>
                                        <option value="" disabled>Pilih Kategori</option>
                                        @foreach ($kategori as $itemKategori)
                                            <option value="{{ $itemKategori->id }}"
                                                {{ old('kategoriBuku_id', $item->kategoriBuku_id) == $itemKategori->id ? 'selected' : '' }}>
                                                {{ $itemKategori->namaKategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Gambar Buku -->
                                <div class="mb-4">
                                    <label for="gambar-buku" class="block text-gray-700 font-medium mb-2">Gambar
                                        Buku</label>
                                    <input type="file" id="gambar-buku" name="image"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                                        accept="image/*">
                                    <small class="text-gray-600">Kosongkan jika tidak ingin mengganti gambar</small>
                                </div>

                                <!-- Tombol Update -->
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-yellow-600 transition duration-300">
                                        Update Buku
                                    </button>
                                </div>
                            </form>

                            <form action="{{ route('buku.delete', $item->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-3 py-2 rounded-lg shadow-md hover:bg-red-600 transition duration-300">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Pagination Links -->
            <div class="mt-6">
                {{ $buku->links() }} <!-- This will display the pagination links -->
            </div>
        </div>

        <!-- Script untuk mengontrol visibilitas form -->
        <script>
            document.getElementById('btn-tambah-buku').addEventListener('click', function() {
                const form = document.getElementById('form-tambah-buku');
                form.classList.toggle('hidden');
            });

            function toggleForm(formId) {
                const form = document.getElementById(formId);
                form.classList.toggle('hidden');
            }
        </script>
    </body>

    </html>
</x-app-layout>
