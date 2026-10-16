<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $movements = StockMovement::with(['product', 'user'])->latest()->take(30)->get();
        $lowStockCount = Product::where('stock', '<=', 5)->count();

        return view('inventory.index', compact('products', 'movements', 'lowStockCount'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:in,out',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $stockBefore = $product->stock;
        $qty = $request->quantity;

        if ($request->type === 'in') {
            $stockAfter = $stockBefore + $qty;
        } else {
            if ($qty > $stockBefore) {
                return back()->with('error', 'Jumlah keluar tidak boleh melebihi stok yang ada!');
            }
            $stockAfter = $stockBefore - $qty;
        }

        // Simpan log riwayat stok
        StockMovement::create([
            'product_id'   => $product->id,
            'user_id'      => Auth::id(),
            'type'         => $request->type,
            'quantity'     => $qty,
            'stock_before' => $stockBefore,
            'stock_after'  => $stockAfter,
            'notes'        => $request->notes ?? ($request->type === 'in' ? 'Restock barang masuk' : 'Penyesuaian stok keluar'),
        ]);

        // Update stok fisik produk
        $product->update(['stock' => $stockAfter]);

        return back()->with('success', 'Stok produk berhasil diperbarui!');
    }
}