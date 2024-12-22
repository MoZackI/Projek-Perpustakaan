<x-app-layout>
    <!-- Tombol Kembali -->
    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="inline-block text-gray-600 hover:text-gray-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M15.54 3.54a1 1 0 010 1.42L9.41 12l6.13 7.04a1 1 0 11-1.54 1.28l-7-8a1 1 0 010-1.28l7-8a1 1 0 011.42 0z" />
            </svg>
        </a>
    </div>

    <!-- Kontainer Utama -->
    <div class="container py-5">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <!-- Menampilkan pesan sukses jika ada -->
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Detail Buku -->
                <div class="flex flex-col md:flex-row items-center md:items-start">
                    <div class="w-full md:w-1/3 mb-4 md:mb-0">
                        <img src="{{ asset('images/' . $buku->image) }}" alt="{{ $buku->judul }} "
                            class="w-full h-auto rounded-lg shadow-md">
                    </div>
                    <div class="w-full md:w-2/3 md:pl-6">
                        <h1 class="text-4xl font-bold mb-4 text-center md:text-left">{{ $buku->judul }}</h1>
                        <p class="text-lg mb-2"><strong>Penulis:</strong> {{ $buku->penulis }}</p>
                        <p class="text-lg mb-2"><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
                        <p class="text-lg mb-2"><strong>Tahun Terbit:</strong> {{ $buku->tahunTerbit }}</p>
                        <p class="text-lg mb-2"><strong>Kategori:</strong> {{ $buku->kategori->namaKategori }}</p>
                    </div>
                </div>

                @if (auth()->check() && auth()->user()->role === 'peminjam')
                    @auth
                        <!-- Cek apakah buku sudah ada di koleksi pengguna -->
                        @if (auth()->user()->koleksi->contains('buku_id', $buku->id))
                            <p class="text-green-500 mt-4">Buku ini sudah ada di koleksi Anda.</p>
                        @else
                            <div class="mt-4">
                                <form action="{{ route('koleksi.add', $buku->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                        Tambahkan ke Koleksi
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                @endif



                <hr class="my-6">

                <!-- Bagian Ulasan -->
                <h2 class="text-2xl font-semibold mb-4">Ulasan Buku</h2>
                @if ($buku->ulasan->isEmpty())
                    <p class="text-gray-500">Belum ada ulasan untuk buku ini.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($buku->ulasan as $ulas)
                            <div class="p-4 bg-gray-100 rounded-lg shadow">
                                <p class="font-semibold text-gray-700">{{ $ulas->user->name }}</p>
                                <p class="text-yellow-400 text-lg">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $ulas->rating)
                                            &#9733; <!-- Bintang Terisi -->
                                        @else
                                            &#9734; <!-- Bintang Kosong -->
                                        @endif
                                    @endfor
                                </p>
                                <p class="text-gray-600 mt-2">{{ $ulas->ulasan }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Formulir Tambah Ulasan -->
                @if (auth()->check() && auth()->user()->role === 'peminjam')
                    @auth
                        <div class="mt-8">
                            <h3 class="text-2xl font-semibold mb-3">Berikan Ulasan Anda</h3>
                            <form action="{{ route('buku.addUlasan', $buku->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <h4 class="text-lg font-semibold mb-2">Berikan Rating:</h4>
                                    <div class="flex space-x-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label>
                                                <input type="radio" name="rating" value="{{ $i }}"
                                                    class="hidden" required>
                                                <span class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400"
                                                    onmouseover="highlightStars({{ $i }})"
                                                    onmouseout="resetStars()"
                                                    onclick="setRating({{ $i }})">&#9733;</span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <textarea name="ulasan" placeholder="Tulis ulasan Anda di sini..." required
                                        class="w-full text-black h-40 p-4 border border-gray-300 rounded-lg 
                                        focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit"
                                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                        Kirim Ulasan
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <p class="mt-6 text-center">
                            Anda harus <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> untuk
                            memberikan ulasan.
                        </p>
                    @endauth
                @endif
            </div>
        </div>
    </div>

    <!-- Script Highlight Rating -->
    <script>
        function highlightStars(rating) {
            document.querySelectorAll('span').forEach((star, index) => {
                star.classList.toggle('text-yellow-400', index < rating);
                star.classList.toggle('text-gray-300', index >= rating);
            });
        }

        function resetStars() {
            document.querySelectorAll('input[name=\"rating\"]:checked').forEach(input => {
                highlightStars(input.value);
            });
        }

        function setRating(rating) {
            document.querySelectorAll('input[name=\"rating\"]').forEach(input => {
                input.checked = input.value == rating;
            });
        }
    </script>
</x-app-layout>
