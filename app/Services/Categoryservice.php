<?php

namespace App\Services;

use App\Http\Requests\Categoryrqt;
use App\Http\Resources\Categoryresource;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class Categoryservice
{

    public function index()
    {
        // Legacy issue: returns all records, no cache, no pagination.
        return  Category::orderBy('created_at', 'desc')->get();
    }


    public function store(Categoryrqt $request)
    {
        Category::create($request->all());
        return response()->json([
            'ok' => true,
            'message' => 'Category creada',
        ]);
    }


    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function update(Categoryrqt $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->fill($request->all());
        return $category->save();
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        Log::info('Category deleted', ['category_id' => $id]);
        return $category->delete();
    }
}
