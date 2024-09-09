<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CFDIUse;
use App\Models\CustomerBillingData;
use App\Models\FiscalRegime;
use App\Models\Invoice;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
use SimpleXMLElement;

class InvoiceController extends Controller
{
    public function storeClient(Request $request)
    {
        $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'sale_id' => 'nullable|exists:patients,id',
            'rfc' => 'required|regex:/^([A-ZÑ&]{3,4})?(?:[0-9]{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12][0-9]|3[01]))?([A-Z\d]{2})([A\d])$/',
            'business_name' => 'required|string|max:255',
            'fiscal_regime' => 'required|exists:fiscal_regimes,code',
            'cfdi_use' => 'required|exists:cfdi_uses,code',
            'zipcode' => 'required|string|min:5|max:5',
            'street' => 'required|string',
            'exterior_number' => 'required|string',
            'interior_number' => 'nullable|string',
            'neighborhood' => 'required|string',
            'locality' => 'required|string',
            'municipality' => 'required|string',
            'state' => 'required|string',
            'email' => 'required|email',
        ]);

        $sale = Sale::findOrFail($request->sale_id);

        CustomerBillingData::create([
            'patient_id' => $request->patient_id,
            'rfc' => $request->rfc,
            'business_name' => $request->business_name, 
            'fiscal_regime' => $request->fiscal_regime, 
            'cfdi_use' => $request->cfdi_use, 
            'zipcode' => $request->zipcode, 
            'street' => $request->street, 
            'exterior_number' => $request->exterior_number, 
            'interior_number' => $request->interior_number, 
            'neighborhood' => $request->neighborhood, 
            'locality' => $request->locality, 
            'municipality' => $request->municipality, 
            'state' => $request->state, 
            'email' => $request->email, 
        ]);
        // Servicio de facturama

        $invoice = Invoice::create([
            'patient_id' => $request->sale_id,
            'sale_id' => $request->sale_id,
            'rfc' => $request->rfc,
            'business_name' => $request->business_name, 
            'fiscal_regime' => $request->fiscal_regime, 
            'cfdi_use' => $request->cfdi_use, 
            'zipcode' => $request->zipcode, 
            'street' => $request->street, 
            'exterior_number' => $request->exterior_number, 
            'interior_number' => $request->interior_number, 
            'neighborhood' => $request->neighborhood, 
            'locality' => $request->locality, 
            'municipality' => $request->municipality, 
            'state' => $request->state, 
            'email' => $request->email, 
            'subtotal' => $sale->subtotal, 
            'iva' => $sale->iva, 
            'total_amount' => $sale->total_amount, 
            'payment_method' => $sale->payment_method, 
            'last_digits_card' => $request->last_digits_card, 
            'facturama_id' => null, 
            'facturama_url' => null, 
        ]);

        return response()->json($invoice, 201);
    }


    public function storeSupplier(Request $request)
    {
        $request->validate([
            'xml' => 'required|mimes:xml',
        ]);
        try {
            if ($request->file('xml')) {
                $xmlFile = $request->file('xml');
                $xmlContent = file_get_contents($xmlFile);
        
                $xml = new SimpleXMLElement($xmlContent);
                $namespaces = $xml->getNamespaces(true);
                $cfdi = $xml->children($namespaces['cfdi']);
                
                $emisor = $cfdi->Emisor->attributes();
                $receptor = $cfdi->Receptor->attributes();
                $comprobante = $xml->attributes();

                $folio = (string) $comprobante['Folio'];

                $invoiceExiste = Invoice::where('folio', $folio)->first();
                if($invoiceExiste) {
                    return response()->json(["message" => "La factura ya fue registrada"], 400);
                }

                $supplierData = [
                    'name' => (string) $emisor['Nombre'],
                ];
        
                $supplier = Supplier::firstOrCreate(
                    ['name' => $emisor['Nombre']],
                    $supplierData
                );
                $cfdiUse = CFDIUse::where('code', $receptor['UsoCFDI'])->first();
                $regimeFiscal = FiscalRegime::where('code', $emisor['RegimenFiscal'])->first();
                $invoiceData = [
                    'supplier_id' => $supplier->id,
                    'folio' => $folio,
                    'rfc' => (string) $emisor['Rfc'],
                    'business_name' => (string) $emisor['Nombre'], 
                    'cfdi_use' => $cfdiUse->concat_description, 
                    'fiscal_regime' => $regimeFiscal->concat_description,
                    'subtotal' => (float) $comprobante['SubTotal'],
                    'iva' => isset($cfdi->Impuestos->Traslados->Traslado) ? (float) $cfdi->Impuestos->Traslados->Traslado['Importe'] : 0.00,
                    'total_amount' => (float) $comprobante['Total'],
                    'payment_method' => (string) $comprobante['MetodoPago'],
                    'zipcode' => (string) $comprobante['LugarExpedicion'], 
                    'street' => null,
                    'exterior_number' => null,
                    'interior_number' => null,
                    'neighborhood' => null,
                    'locality' => null,
                    'municipality' => null,
                    'state' => null,
                    'email' => null,
                    'last_digits_card' => null,
                    'status' => "done",
                ];
    
                $invoice = Invoice::create($invoiceData);
        
                return response()->json($invoice, 201);
            }
            return abort(400, "No se pudo procesar el archivo XML (El archivo esta dañado o no cuenta con el formato correcto)");
        } catch (\Throwable $th) {
            // return response()->json($th);
            return abort(400, "No se pudo procesar el archivo XML (El archivo esta dañado o no cuenta con el formato correcto)");
        }
    }


    public function indexClient() {
        $invoices = Invoice::whereNotNull('sale_id')->get();
        return response()->json($invoices);
    }

    public function indexSupplier() {
        $invoices = Invoice::with('supplier')->whereNotNull('supplier_id')->get();
        return response()->json($invoices);
    }

    public function showClient(string $id)
    {
        $invoice = Invoice::with('sale', 'patient')->whereNotNull('sale_id')->where('id', $id)->firstOrFail();
        return response()->json($invoice);
    }

    public function showSupplier(string $id)
    {
        $invoice = Invoice::with('supplier')->whereNotNull('supplier_id')->where('id', $id)->firstOrFail();
        return response()->json($invoice);
    }

    public function indexCfdiuses() {
        $cfdiUses = CFDIUse::get();
        return response()->json($cfdiUses);
    }

    public function indexRegimes() {
        $regimes = FiscalRegime::get();
        return response()->json($regimes);
    }

}
