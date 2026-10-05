@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Penjualan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-800">Daftar Laporan Penjualan</h3>
        <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">Total: {{ count($transactions) }} Transaksi</span>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                <th class="p-4 pl-6">No. Invoice</th>
                <th class="p-4">Tanggal Transaksi</th>
                <th class="p-4">Total Belanja</th>
                <th class="p-4">Dibayar</th>
                <th class="p-4">Kembalian</th>
                <th class="p-4 pr-6 text-right">Cetak</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
            @forelse($transactions as $trx)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4 pl-6 font-mono font-bold text-blue-600">{{ $trx->invoice_number }}</td>
                    <td class="p-4 text-slate-500 font-medium text-xs">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                    <td class="p-4 font-extrabold text-slate-800">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                    <td class="p-4 text-slate-600 font-medium">Rp {{ number_format($trx->pay_amount, 0, ',', '.') }}</td>
                    <td class="p-4 text-slate-600 font-medium">Rp {{ number_format($trx->change_amount, 0, ',', '.') }}</td>
                    <td class="p-4 pr-6 text-right">
                        <a href="{{ route('pos.print', $trx->id) }}" target="_blank" 
                           class="bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-print"></i> Struk
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-400">Belum ada transaksi penjualan yang tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection