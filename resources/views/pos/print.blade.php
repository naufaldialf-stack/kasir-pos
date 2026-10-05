<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $transaction->invoice_number }}</title>
    <style>
        body { font-family: monospace; width: 300px; padding: 10px; margin: auto; }
        .text-center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
        .flex { display: flex; justify-content: space-between; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <h2>KASIR POS STORE</h2>
        <p>Jl. Raya Utama No. 123</p>
        <p>{{ $transaction->created_at->format('d/m/Y H:i') }} | {{ $transaction->invoice_number }}</p>
    </div>
    
    <div class="line"></div>
    
    @foreach($transaction->details as $item)
        <div>
            <div><strong>{{ $item->product->name ?? 'Produk' }}</strong></div>
            <div class="flex">
                <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
    @endforeach

    <div class="line"></div>

    <div class="flex"><strong>Total:</strong> <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></div>
    <div class="flex"><span>Bayar:</span> <span>Rp {{ number_format($transaction->pay_amount, 0, ',', '.') }}</span></div>
    <div class="flex"><span>Kembali:</span> <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span></div>

    <div class="line"></div>
    <p class="text-center">-- Terima Kasih --</p>
</body>
</html>