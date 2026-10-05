<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Kasir POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Daftar Kategori</h1>

        <!-- Form Tambah Kategori -->
        <form action="{{ route('categories.store') }}" method="POST" class="flex gap-2 mb-6">
            @csrf
            <input type="text" name="name" placeholder="Nama Kategori Baru" required 
                   class="border border-gray-300 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan</button>
        </form>

        <!-- Tabel Daftar Kategori -->
        <table class="w-full text-left border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Nama Kategori</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $category)
                    <tr class="border-b">
                        <td class="p-3 border">{{ $index + 1 }}</td>
                        <td class="p-3 border">{{ $category->name }}</td>
                        <td class="p-3 border">
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-4 text-center text-gray-500">Belum ada kategori. Silakan tambahkan!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>