<?php

namespace App\Services;

use GuzzleHttp\Client;
use Facturama\Client as FacturamaClient;

class FacturamaService
{
    protected $client;

    public function __construct()
    {
        $this->client = new FacturamaClient(env('FACTURAMA_API_USER'), env('FACTURAMA_API_PASS'));
    }

    public function createInvoice($data)
    {
        try {
            return $this->client->post('3/cfdis', $data);
        } catch (\Exception $e) {
            throw new \Exception("Error al crear la factura: " . $e->getMessage());
        }
    }

    public function getInvoice($id)
    {
        return $this->client->get("3/cfdis/$id");
    }

    public function downloadInvoice($id)
    {
        return $this->client->get("cfdi/pdf/received/$id");
    }

    public function cancelInvoice($id)
    {
        return $this->client->post("3/cfdis/$id/cancel");
    }
}
