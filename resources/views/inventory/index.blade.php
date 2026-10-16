@extends('layouts.app')

@section('title', 'Manajemen Inventory')
@section('page_title', 'Inventory & Kontrol Stok')

@section('content')
<div class="space-y-6">

    <!-- Flash Message -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-xl text-emerald-600"></i>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation text-xl text-rose-600"></i>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Form Restock / Penyesuaian Stok Cepat -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-boxes-packing text-blue-600"></i> Penyesuaian / Restock Stok
        </h3>
        
        <form action="{{ route('inventory.adjust') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @csrf
            
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Produk</label>
                <select name="product_id" required class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}">
                            {{ $prod->code }} - {{ $prod->name }} (Sisa Stok: {{ $prod->stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Aksi</label>
                <select name="type" required class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="in">Barang Masuk / Restock (+)</option>
                    <option value="out">Barang Keluar / Rusak / Hilang (-)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jumlah (Qty)</label>
                <input type="number" name="quantity" min="1" placeholder="10" required 
                       class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Catatan</label>
                <div class="flex gap-2">
                    <input type="text" name="notes" placeholder="Contoh: Kulakan suplier" 
                           class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl transition text-sm whitespace-nowrap shadow-md shadow-blue-500/20">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Riwayat Log Mutasi Stok -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Riwayat Mutasi Stok Terakhir</h3>
                <p class="text-xs text-slate-400">Log keluar masuk barang secara real-time</p>
            </div>
            @if($lowStockCount > 0)
                <span class="bg-rose-50 text-rose-600 text-xs font-bold px-3 py-1 rounded-full border border-rose-100">
                    {{ $lowStockCount }} Produk Menipis
                </span>
            @endif
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                    <th class="p-4 pl-6">Waktu</th>
                    <th class="p-4">Produk</th>
                    <th class="p-4 text-center">Tipe</th>
                    <th class="p-4 text-center">Perubahan</th>
                    <th class="p-4 text-center">Stok Awal -> Akhir</th>
                    <th class="p-4">Keterangan</th>
                    <th class="p-4 pr-6">Petugas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($movements as $m)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 pl-6 text-xs text-slate-500 whitespace-nowrap">
                            {{ $m->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="p-4 font-bold text-slate-800">
                            {{ $m->product->name ?? 'Produk Dihapus' }}
                            <div class="text-[11px] font-mono font-normal text-slate-400">{{ $m->product->code ?? '-' }}</div>
                        </td>
                        <td class="p-4 text-center">
                            @if($m->type === 'in')
                                <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-md border border-emerald-100">
                                    Masuk
                                </span>
                            @else
                                <span class="bg-rose-50 text-rose-700 text-xs font-bold px-2.5 py-1 rounded-md border border-rose-100">
                                    Keluar
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-center font-extrabold {{ $m->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $m->type === 'in' ? '+' : '-' }}{{ $m->quantity }}
                        </td>
                        <td class="p-4 text-center font-medium text-slate-600">
                            {{ $m->stock_before }} <span class="text-slate-400">→</span> <span class="font-bold text-slate-800">{{ $m->stock_after }}</span>
                        </td>
                        <td class="p-4 text-slate-600 text-xs">
                            {{ $m->notes ?? '-' }}
                        </td>
                        <td class="p-4 pr-6 text-xs text-slate-500">
                            {{ $m->user->name ?? 'Sistem' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">Belum ada riwayat mutasi stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection