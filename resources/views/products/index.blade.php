<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Kasir POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Daftar Produk</h1>
            <a href="{{ route('categories.index') }}" class="text-blue-600 hover:underline">← Kelola Kategori</a>
        </div>

        <!-- Form Tambah Produk -->
        <form action="{{ route('products.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6 bg-gray-50 p-4 rounded border">
            @csrf
            <div>
                <label class="block text-xs font-semibold mb-1">Kode Barang</label>
                <input type="text" name="code" placeholder="PRD001" required 
                       class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Nama Produk</label>
                <input type="text" name="name" placeholder="Nama Barang" required 
                       class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Kategori</label>
                <select name="category_id" required class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Harga (Rp)</label>
                <input type="number" name="price" placeholder="10000" required 
                       class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Stok</label>
                <div class="flex gap-2">
                    <input type="number" name="stock" placeholder="50" required 
                           class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah</button>
                </div>
            </div>
        </form>

        <!-- Tabel Daftar Produk -->
        <table class="w-full text-left border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="p-3 border">Kode</th>
                    <th class="p-3 border">Nama Produk</th>
                    <th class="p-3 border">Kategori</th>
                    <th class="p-3 border">Harga</th>
                    <th class="p-3 border">Stok</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b">
                        <td class="p-3 border font-mono text-sm">{{ $product->code }}</td>
                        <td class="p-3 border font-semibold">{{ $product->name }}</td>
                        <td class="p-3 border">{{ $product->category->name ?? '-' }}</td>
                        <td class="p-3 border">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="p-3 border">{{ $product->stock }}</td>
                        <td class="p-3 border">
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">Belum ada produk. Tambahkan kategori terlebih dahulu sebelum mengisi produk!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>