<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        // Obtener todos los proveedores
        $suppliers = Supplier::all();
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        // Validar y crear un nuevo proveedor
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'nullable|string'
        ]);

        $supplier = Supplier::create($validatedData);

        return response()->json($supplier, 201);
    }

    public function show($id)
    {
        // Obtener un proveedor por su ID
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier);
    }

    public function update(Request $request, $id)
    {
        // Validar y actualizar un proveedor existente
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'contact_info' => 'nullable|string'
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($validatedData);

        return response()->json($supplier);
    }

    public function destroy($id)
    {
        // Eliminar un proveedor por su ID
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return response()->json(null, 204);
    }
}
