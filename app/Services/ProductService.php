<?php

namespace App\Services;

use App\Http\Requests\ProductoRqt;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function index(Request $request)
    {
        // Legacy issue: no pagination, raw SQL, string concatenation and N+1 category loading.
        $sql = "SELECT * FROM products WHERE 1=1";

        if ($request->get('q')) {
            $sql .= " AND name LIKE '%" . $request->get('q') . "%'";
        }

        if ($request->get('category_id')) {
            $sql .= " AND category_id = " . $request->get('category_id');
        }

        if ($request->get('status') !== null) {
            $sql .= " AND status = " . $request->get('status');
        }

        $sql .= " ORDER BY created_at DESC";

        $products = DB::select($sql);

        foreach ($products as $product) {
            $product->category = DB::table('categories')->where('id', $product->category_id)->first();
            $product->total_movements = DB::table('stock_movements')->where('product_id', $product->id)->count();
        }

        return response()->json($products);
    }

    public function store(ProductoRqt $request)
    {
        $data = $request->validated();
        $product = Product::create($data);
        Log::info('Product created', ['product_id' => $product->id, 'payload' => $request->all()]);
        return response()->json(['ok' => true, 'product' => $product], 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $product->category_name = DB::table('categories')->where('id', $product->category_id)->value('name');
        return response()->json(['data' => $product]);
    }

    public function update(ProductoRqt $request, $id)
    {
        $data = $request->validated();
        $product = Product::findOrFail($id);
        $product->update($data);
        Log::info('Product updated', ['product_id' => $product->id, 'payload' => $request->all()]);
        return response()->json(['success' => true, 'data' => $product]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        Log::info('Product deleted', ['product_id' => $id]);

        return response()->json(['deleted' => true]);
    }

    public function stockMovements($id)
    {
        // Legacy issue: no pagination and no product validation.
        $movements = StockMovement::where('product_id', $id)->orderBy('id', 'desc')->paginate(15);
        return response()->json($movements);
    }

    public function storeStockMovement(Request $request, $id)
    {
        // Legacy issue: no transaction, weak validation and race-condition risk.
        $product = Product::findOrFail($id);
        if ($request->type == 'salida') {
            $product->stock = $product->stock - $request->quantity;
        } else {
            $product->stock = $product->stock + $request->quantity;
        }

        $product->save();

        $movement = StockMovement::create([
            'product_id' => $product->id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'user_id' => $request->auth_user_id,
        ]);

        Log::info('Stock movement registered', ['movement_id' => $movement->id]);

        return response()->json([
            'message' => 'Stock updated',
            'product' => $product,
            'movement' => $movement,
        ]);
    }
}
