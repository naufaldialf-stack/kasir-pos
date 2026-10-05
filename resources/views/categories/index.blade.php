@extends('layouts.app')

@section('title', 'Kelola Kategori')
@section('page_title', 'Kelola Kategori Produk')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Card Input Kategori -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-plus-circle text-blue-600"></i> Tambah Kategori Baru
        </h3>
        <form action="{{ route('categories.store') }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="name" placeholder="Contoh: Makanan, Minuman, Snack" required 
                   class="bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition text-sm">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow-lg shadow-blue-500/20 whitespace-nowrap flex items-center gap-2 text-sm">
                <i class="fa-solid fa-save"></i> Simpan
            </button>
        </form>
    </div>

    <!-- Tabel Kategori -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Daftar Kategori Terdaftar</h3>
            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">Total: {{ count($categories) }} Kategori</span>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                    <th class="p-4 pl-6">No</th>
                    <th class="p-4">Nama Kategori</th>
                    <th class="p-4 pr-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($categories as $index => $category)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 pl-6 font-semibold text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-4 font-bold text-slate-800">{{ $category->name }}</td>
                        <td class="p-4 pr-6 text-right">
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 ml-auto">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-slate-400">Belum ada kategori terdaftar. Silakan tambahkan di atas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection