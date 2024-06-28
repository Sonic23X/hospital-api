<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

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

        //Validar si el producto es farmacéutico
        if ($validatedData['is_pharmaceutical']) {
            $validatedData['invoice_type'] = 'H87';
        } else {
            $validatedData['invoice_type'] = 'C62';
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

    function importProducts(Request $request)
    {
        // Validar y procesar un archivo de Excel para importar productos
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $path = $validatedData['file']->store('public/imported_files'); // Almacenar el archivo en el sistema de archivos

        $spreadsheet = IOFactory::load(storage_path('app/' . $path)); // Cargar el archivo de Excel
        $sheet = $spreadsheet->getActiveSheet(); // Obtener la hoja activa

        $products = [];
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);

            $product = [];
            foreach ($cellIterator as $cell) {
                $product[] = $cell->getValue();
            }

            $products[] = $product;
        }

        $header = array_shift($products);
        $productsData = [];
        foreach ($products as $product) {
            $productData = [];
            foreach ($product as $key => $value) {
                $productData[$header[$key]] = $value;
            }

            $productsData[] = $productData;
        }

        foreach ($productsData as $productData) {
            $product = Product::create($productData);
        }

        return response()->json($productsData, 201);
    }
}
