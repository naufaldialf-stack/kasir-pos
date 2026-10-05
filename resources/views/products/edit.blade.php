@extends('layouts.app')

@section('title', 'Edit Produk')
@section('page_title', 'Edit Data Produk')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h3 class="text-lg font-bold text-slate-800">Edit Produk: {{ $product->name }}</h3>
        <a href="{{ route('products.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-700">← Kembali</a>
    </div>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kode Barang</label>
                <input type="text" name="code" value="{{ old('code', $product->code) }}" required 
                       class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                       class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                <select name="category_id" required class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" required 
                       class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required 
                       class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Ganti Foto (Opsional)</label>
                <input type="file" name="image" accept="image/*" 
                       class="bg-slate-50 border border-slate-300 rounded-xl px-2 py-1.5 w-full text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        @if($product->image)
            <div class="mt-2">
                <p class="text-xs text-slate-500 mb-1">Foto Saat Ini:</p>
                <img src="{{ asset('storage/' . $product->image) }}" class="w-20 h-20 rounded-xl object-cover border">
            </div>
        @endif

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm">
                Update Produk
            </button>
        </div>
    </form>
</div>
@endsection