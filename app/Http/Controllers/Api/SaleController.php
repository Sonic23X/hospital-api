<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::where('client_id', Auth::user()->client_id)->with('items', 'user')->get();
        return response()->json($sales);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'status' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        $data = $request->only(['total_amount', 'payment_method', 'status', 'notes']);
        $data['user_id'] = Auth::user()->id;
        $data['client_id'] = Auth::user()->client_id;
        $data['purchase_date'] = Carbon::now();

        $sale = Sale::create($data);

        foreach ($request->items as $item) {
            $saleItem = new SaleItem([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['quantity'] * $item['price'],
            ]);
            $sale->items()->save($saleItem);

            // Actualizar el stock del producto
            $product = Product::find($item['product_id']);
            $product->stock -= $item['quantity'];
            $product->save();
        }

        return response()->json($sale->load('items.product'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sales = Sale::where('client_id', Auth::user()->client_id)->with('items', 'user')->where('id', $id)->firstOrFail();
        return response()->json($sales);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sale = Sale::findOrFail($id);

        // Revertir el stock de los productos antes de eliminar los items
        foreach ($sale->items as $item) {
            $product = Product::find($item->product_id);
            $product->stock += $item->quantity;
            $product->save();
        }

        $sale->items()->delete();
        $sale->delete();

        return response()->json(null, 204);
    }
}
