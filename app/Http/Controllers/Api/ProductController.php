<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Import\ProductsImport;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    
    public function index()
    {
        return Product::paginate(10);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria' => 'required|string'
        ]);

        return Product::create($data);
    }

    public function show(Product $product)
    {
        return $product;
    }

   
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria' => 'required|string|max:100',
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'data' => $product
        ]);
    }


    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ]);
    }

}
