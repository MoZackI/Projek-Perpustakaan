<x-app-layout>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Form Kategori</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-900 flex items-center justify-center min-h-screen">
        <!-- Box Utama -->
        <div class="bg-gray-800 text-white p-10 rounded-lg shadow-lg w-full max-w-lg">
            <h1 class="text-3xl font-bold mb-8 text-center">Form Kategori</h1>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-8" action="{{ route('kategori.post') }}" method="POST">
                @csrf
                <div class="p-6 bg-gray-700 rounded-lg hover:bg-gray-600 transition duration-200">
                    <input type="text" id="kategori-input" name="namaKategori" placeholder="Masukkan kategori baru"
                        class="w-full px-4 py-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
                        value="{{ old('kategori') }}" required>
                    <button
                        class="w-full mt-5 bg-blue-600 hover:bg-blue-700 focus:ring focus:ring-blue-500 text-white py-3 rounded-lg font-medium transition duration-200"
                        type="submit">
                        Simpan
                    </button>
                </div>
            </form>

            <form id="kategori-form" action="{{ route('kategori') }}" method="GET">
                <!-- Box Cari Kategori -->
                <div class="p-6 bg-gray-700 rounded-lg hover:bg-gray-600 transition duration-200">
                    <label for="cari" class="block text-base font-medium text-gray-300 mb-3">
                        Cari Kategori
                    </label>
                    <input type="text" id="cari" name="search" placeholder="Masukkan kata kunci"
                        class="w-full px-4 py-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
                        value="{{ request('search') }}" />

                    <button type="submit"
                        class="w-full mt-5 bg-green-600 hover:bg-green-700 focus:ring focus:ring-green-500 text-white py-3 rounded-lg font-medium transition duration-200">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-gray-800 text-white p-10 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-5">Daftar Kategori</h2>
            <ul class="space-y-4">
                @foreach ($kategori as $item)
                    <!-- Item 1 -->
                    <li
                        class="p-3 px-6 bg-gray-700 rounded-lg flex justify-between items-center hover:bg-gray-600 transition duration-200">
                        <span>{{ $item->namaKategori }}</span>
                        <div class="space-x-1">
                            <!-- 05. Update Data -->
                            <form action="{{ route('kategori.update', ['id' => $item->id]) }}" method="POST">
                                @csrf
                                @method('put')
                                <div>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control text-black" name="namaKategori"
                                            value="{{ $item->namaKategori }}">
                                        <button
                                            class="bg-yellow-500 hover:bg-yellow-600 py-2 px-4 rounded-lg font-medium transition duration-200">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <form action="{{ route('kategori.delete', ['id' => $item->id]) }}" method="POST"
                                onsubmit="return confirm('Yakin task ini dihapus?')">
                                @csrf
                                @method('delete')
                                <button
                                    class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </body>
</x-app-layout>
