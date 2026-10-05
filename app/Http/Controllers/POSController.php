<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('pos.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'pay_amount' => 'required|numeric',
        ]);

        $cart = json_decode($request->cart, true);
        $totalAmount = 0;

        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['qty'];
        }

        if ($request->pay_amount < $totalAmount) {
            return back()->with('error', 'Uang pembayaran kurang!');
        }

        $transaction = Transaction::create([
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'total_amount' => $totalAmount,
            'pay_amount' => $request->pay_amount,
            'change_amount' => $request->pay_amount - $totalAmount,
        ]);

        foreach ($cart as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['qty'],
            ]);

            // Kurangi stok produk
            $product = Product::find($item['id']);
            $product->decrement('stock', $item['qty']);
        }

        return redirect()->route('pos.index')->with('success', 'Transaksi berhasil! Kembalian: Rp ' . number_format($transaction->change_amount, 0, ',', '.'));
    }
}