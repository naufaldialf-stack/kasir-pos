<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $transaction->invoice_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f1f5f9;
            color: #000;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .receipt-card {
            width: 320px;
            background: #ffffff;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            font-size: 13px;
            line-height: 1.4;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .line {
            border-bottom: 1px dashed #666;
            margin: 8px 0;
        }

        .double-line {
            border-bottom: 2px dashed #000;
            margin: 8px 0;
        }

        .flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-row {
            margin-bottom: 6px;
        }

        /* Tombol Aksi */
        .no-print {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
        }

        .btn {
            flex: 1;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            border: none;
        }

        .btn-back {
            background-color: #e2e8f0;
            color: #334155;
        }

        .btn-print {
            background-color: #2563eb;
            color: #ffffff;
        }

        /* Format Saat Masuk Printer */
        @media print {
            body {
                background: none;
                padding: 0;
                margin: 0;
            }
            .receipt-card {
                width: 100%;
                box-shadow: none;
                border-radius: 0;
                padding: 4px;
            }
            .no-print {
                display: none !important;
            }
            @page {
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="receipt-card">
        
        <!-- Navigasi Kasir -->
        <div class="no-print">
            <a href="{{ route('pos.index') }}" class="btn btn-back">← Kasir Baru</a>
            <button onclick="window.print()" class="btn btn-print">Cetak Struk</button>
        </div>

        <!-- Header Struk -->
        <div class="text-center">
            <h2 style="font-size: 16px; margin-bottom: 2px;">KASIR POS STORE</h2>
            <p style="font-size: 11px;">Jl. Raya Utama No. 123</p>
            <p style="font-size: 11px;">Telp: 0812-3456-7890</p>
        </div>

        <div class="line"></div>

        <!-- Info Invoice -->
        <div class="flex" style="font-size: 11px;">
            <span>{{ $transaction->invoice_number }}</span>
            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="flex" style="font-size: 11px;">
            <span>Kasir: {{ $transaction->user->name ?? 'Kasir' }}</span>
            <span>Tunai</span>
        </div>

        <div class="line"></div>

        <!-- Daftar Belanja -->
        @foreach($transaction->details as $item)
            <div class="item-row">
                <div class="font-bold">{{ $item->product->name ?? 'Produk' }}</div>
                <div class="flex" style="font-size: 12px;">
                    <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach

        <div class="double-line"></div>

        <!-- Ringkasan Bayar -->
        <div class="flex" style="font-size: 13px;">
            <span class="font-bold">Total:</span>
            <span class="font-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex" style="font-size: 12px;">
            <span>Bayar:</span>
            <span>Rp {{ number_format($transaction->pay_amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex" style="font-size: 12px;">
            <span>Kembali:</span>
            <span class="font-bold">Rp {{ number_format($transaction->change_amount ?? 0, 0, ',', '.') }}</span>
        </div>

        <div class="line"></div>

        <!-- Footer -->
        <div class="text-center" style="font-size: 11px; margin-top: 8px;">
            <p>Total Item: {{ $transaction->details->sum('quantity') }} pcs</p>
            <p style="margin-top: 4px;">-- TERIMA KASIH --</p>
        </div>

    </div>

</body>
</html>