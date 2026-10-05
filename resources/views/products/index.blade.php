@extends('layouts.app')

@section('title', 'Kelola Produk')
@section('page_title', 'Kelola Inventaris Produk')

@section('content')
<div class="space-y-6">

    <!-- Alert Flash Message -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-xl text-emerald-600"></i>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Form Tambah Produk -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-box-open text-blue-600"></i> Tambah Produk Baru
        </h3>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kode Barang</label>
                <input type="text" name="code" placeholder="PRD001" required 
                       class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Produk</label>
                <input type="text" name="name" placeholder="Nama Produk" required 
                       class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                <select name="category_id" required class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                    <option value="">-- Pilih --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Harga (Rp)</label>
                <input type="number" name="price" placeholder="10000" required 
                       class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Stok</label>
                <input type="number" name="stock" placeholder="50" required 
                       class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Foto Produk</label>
                <div class="flex gap-2">
                    <input type="file" name="image" accept="image/*" 
                           class="bg-slate-50 border border-slate-300 rounded-xl px-2 py-1.5 w-full text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl transition shadow-lg shadow-blue-500/20 text-sm">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Produk -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Daftar Produk Ready</h3>
            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">Total: {{ count($products) }} Produk</span>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                    <th class="p-4 pl-6">Foto</th>
                    <th class="p-4">Kode</th>
                    <th class="p-4">Nama Produk</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Status Stok</th>
                    <th class="p-4 pr-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 pl-6">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-image text-lg"></i>
                                </div>
                            @endif
                        </td>
                        <td class="p-4 font-mono font-semibold text-blue-600">{{ $product->code }}</td>
                        <td class="p-4 font-bold text-slate-800">{{ $product->name }}</td>
                        <td class="p-4">
                            <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1 rounded-md font-medium border border-slate-200">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="p-4 font-semibold text-slate-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="p-4">
                            @if($product->stock > 10)
                                <span class="bg-emerald-50 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-bold flex items-center gap-1 w-max">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $product->stock }} Pcs
                                </span>
                            @elseif($product->stock > 0)
                                <span class="bg-amber-50 text-amber-700 text-xs px-2.5 py-1 rounded-full font-bold flex items-center gap-1 w-max">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Tersisa {{ $product->stock }} Pcs
                                </span>
                            @else
                                <span class="bg-rose-50 text-rose-700 text-xs px-2.5 py-1 rounded-full font-bold flex items-center gap-1 w-max">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Stok Habis
                                </span>
                            @endif
                        </td>
                        <td class="p-4 pr-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('products.edit', $product->id) }}" 
                                   class="bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>

                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">Belum ada produk. Tambahkan produk di atas!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection