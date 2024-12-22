<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tambah User</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-100 flex items-center justify-items-center min-h-screen">
        <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md mx-auto">
            <h2 class="text-2xl font-bold mb-4 text-black">Form Tambah User</h2>

            <!-- Pesan Error -->
            @if ($errors->any())
                <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <!-- Nama -->
                <div class="mb-4">
                    <label for="name" class="block text-black font-medium mb-2">Nama</label>
                    <input type="text" id="name" name="name"
                        class="text-black w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        placeholder="Masukkan nama lengkap" required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-black font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email"
                        class="text-black w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        placeholder="Masukkan email" value="{{ old('email') }}" required>
                </div>

                <!-- Alamat -->
                <div class="mb-4">
                    <label for="alamat" class="block text-black font-medium mb-2">Alamat</label>
                    <textarea id="alamat" name="alamat"
                        class="text-black w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        placeholder="Masukkan alamat" required>{{ old('alamat') }}</textarea>
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="block text-black font-medium mb-2">Role</label>
                    <select id="role" name="role"
                        class="text-black w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-black font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password"
                        class="text-black w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        placeholder="Masukkan password" required>
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-black font-medium mb-2">Konfirmasi
                        Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="text-black w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                        placeholder="Masukkan ulang password" required>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition duration-300">
                        Tambah User
                    </button>
                </div>
            </form>
        </div>
    </body>

    </html>
</x-app-layout>
