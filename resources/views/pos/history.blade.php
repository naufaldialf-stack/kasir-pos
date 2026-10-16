@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi Penjualan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-800">Daftar Transaksi Kasir</h3>
        <span class="text-xs font-semibold text-slate-400">Total data: {{ $transactions->total() }}</span>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                <th class="p-4 pl-6">Invoice</th>
                <th class="p-4">Tanggal & Waktu</th>
                <th class="p-4">Kasir</th>
                <th class="p-4">Item Dibeli</th>
                <th class="p-4">Total</th>
                <th class="p-4 pr-6 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
            @forelse($transactions as $trx)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 pl-6 font-mono font-bold text-blue-600">{{ $trx->invoice_number }}</td>
                    <td class="p-4 text-xs text-slate-500">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                    <td class="p-4 text-xs font-semibold text-slate-700">{{ $trx->user->name ?? 'Kasir' }}</td>
                    <td class="p-4 text-xs text-slate-600">
                        {{ $trx->details->sum('quantity') }} Pcs
                        <span class="text-slate-400">({{ $trx->details->count() }} jenis)</span>
                    </td>
                    <td class="p-4 font-bold text-slate-900">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                    <td class="p-4 pr-6 text-right">
                        <a href="{{ route('pos.print', $trx->id) }}" target="_blank"
                           class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-print"></i> Cetak Struk
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-400">Belum ada riwayat transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="p-4 border-t border-slate-100">
        {{ $transactions->links() }}
    </div>
</div>
@endsection