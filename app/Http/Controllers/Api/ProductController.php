<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Obtener todos los productos con sus proveedores
        $products = Product::with('supplier')->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        // Validar y crear un nuevo producto
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'batch' => 'nullable|string|max:255',
            'active_substance' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'qr_location' => 'nullable|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif',
            'is_pharmaceutical' => 'required|boolean',
            'supplier_id' => 'required|exists:suppliers,id'
        ]);

        $imageUrls = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/product_images'); // Almacenar la imagen en el sistema de archivos
                $imageUrls[] = Storage::url($path); // Obtener la URL de la imagen almacenada
            }
        }

        $validatedData['images'] = $imageUrls;

        $product = Product::create($validatedData);

        return response()->json($product, 201);
    }

    public function show($id)
    {
        // Obtener un producto por su ID
        $product = Product::with('supplier')->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        // Validar y actualizar un producto existente
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
            'category' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'batch' => 'nullable|string|max:255',
            'active_substance' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'qr_location' => 'nullable|string|max:255',
            'images' => 'nullable|json',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validatedData);

        return response()->json($product);
    }

    public function destroy($id)
    {
        // Eliminar un producto por su ID
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }

    function changeStock(Request $request, $id)
    {
        // Validar y actualizar el stock de un producto
        $validatedData = $request->validate([
            'stock' => 'required|integer'
        ]);

        $product = Product::findOrFail($id);
        $product->stock = $validatedData['stock'];
        $product->save();

        return response()->json($product, 200);
    }
}
