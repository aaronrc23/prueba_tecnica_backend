<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRqt;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $productoservice;

    public function __construct(ProductService $productoservice)
    {
        $this->productoservice = $productoservice;
    }
    public function index(Request $request)
    {
        return $this->productoservice->index($request);
    }

    public function store(ProductoRqt $request)
    {
        return $this->productoservice->store($request);
    }

    public function show($id)
    {
        return $this->productoservice->show($id);
    }

    public function update(ProductoRqt $request, $id)
    {
        return $this->productoservice->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->productoservice->destroy($id);
    }

    public function stockMovements($id)
    {
        return $this->productoservice->stockMovements($id);
    }

    public function storeStockMovement(Request $request, $id)
    {
        return $this->productoservice->storeStockMovement($request, $id);
    }
}
