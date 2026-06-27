<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categoryrqt;
use App\Http\Resources\Categoryresource;
use App\Models\Category;
use App\Services\Categoryservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    protected $category;
    public function __construct(Categoryservice $category)
    {
        $this->category = $category;
    }
    public function index()
    {
        return response()->json([
            'categories' => Categoryresource::collection($this->category->index())
        ]);
    }


    public function store(Categoryrqt $request)
    {
        $this->category->store($request);
        return response()->json([
            'ok' => true,
            'message' => 'Category creada',
        ]);
    }


    public function show($id)
    {
        return Categoryresource::make($this->category->show($id));
    }

    public function update(Categoryrqt $request, $id)
    {
        $this->category->update($request, $id);
        return response()->json(['updated' => true, 'message' => 'Category actualizada']);
    }

    public function destroy($id)
    {
        $this->category->destroy($id);
        return response()->json(['ok' => true, 'message' => 'Category borrada']);
    }
}
