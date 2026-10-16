<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    // 1. Dashboard Analytics
    public function dashboard()
    {
        $todayEarnings = Transaction::whereDate('created_at', today())->sum('total_amount');
        $todayTransactions = Transaction::whereDate('created_at', today())->count();
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 5)->count();

        $salesData = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $chartLabels = [];
        $chartTotals = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $formattedDate = now()->subDays($i)->format('d M');
            
            $found = $salesData->firstWhere('date', $date);
            
            $chartLabels[] = $formattedDate;
            $chartTotals[] = $found ? (float)$found->total : 0;
        }

        $topProducts = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'todayEarnings', 
            'todayTransactions', 
            'totalProducts', 
            'lowStockProducts', 
            'chartLabels', 
            'chartTotals',
            'topProducts'
        ));
    }

    // 2. Halaman Kasir POS
    public function index()
    {
        $products = Product::with('category')->where('stock', '>', 0)->latest()->get();
        return view('pos.index', compact('products'));
    }

    // 3. Proses Checkout & Pengurangan Stok Otomatis
    public function store(Request $request)
    {
        $request->validate([
            'cart'       => 'required|string',
            'pay_amount' => 'required|numeric|min:0',
        ]);

        $cart = json_decode($request->cart, true);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja masih kosong!');
        }

        return DB::transaction(function () use ($cart, $request) {
            $totalAmount = 0;

            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->find($item['id']);
                
                if (!$product) {
                    return back()->with('error', "Produk '{$item['name']}' tidak ditemukan!");
                }

                if ($product->stock < $item['qty']) {
                    return back()->with('error', "Stok '{$product->name}' tidak cukup! Sisa: {$product->stock}");
                }

                $totalAmount += $product->price * $item['qty'];
            }

            if ($request->pay_amount < $totalAmount) {
                return back()->with('error', 'Uang pembayaran kurang dari total belanja!');
            }

            $change = $request->pay_amount - $totalAmount;

            $transaction = Transaction::create([
            'invoice_number' => 'INV-' . date('YmdHis') . '-' . rand(100, 999),
            'user_id'        => Auth::id(),
            'total_amount'   => $totalAmount,
            'pay_amount'     => $request->pay_amount,
            'change_amount'  => $change,
]);

            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                $stockBefore = $product->stock;
                $stockAfter = $stockBefore - $item['qty'];

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'price'          => $product->price,
                    'quantity'       => $item['qty'],
                    'subtotal'       => $product->price * $item['qty'],
                ]);

                StockMovement::create([
                    'product_id'   => $product->id,
                    'user_id'      => Auth::id(),
                    'type'         => 'out',
                    'quantity'     => $item['qty'],
                    'stock_before' => $stockBefore,
                    'stock_after'  => $stockAfter,
                    'notes'        => 'Penjualan Kasir (' . $transaction->invoice_number . ')',
                ]);

                $product->update(['stock' => $stockAfter]);
            }

            return redirect()->route('pos.print', $transaction->id)
                             ->with('success', 'Transaksi berhasil disimpan!');
        });
    }

    // 4. Riwayat Transaksi
    public function history()
    {
        $transactions = Transaction::with(['user', 'details.product'])->latest()->paginate(10);
        return view('pos.history', compact('transactions'));
    }

    // 5. Cetak Struk
    public function printInvoice($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);
        return view('pos.print', compact('transaction'));
    }
}