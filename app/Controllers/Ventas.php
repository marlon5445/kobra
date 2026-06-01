<?php

namespace App\Controllers;

use App\Models\VentaModel;
use App\Models\DetalleVentaModel;
use App\Models\ClienteModel;
use App\Models\ProductoModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Ventas extends BaseController
{
    protected VentaModel   $ventaModel;
    protected ClienteModel  $clienteModel;
    protected ProductoModel $productoModel;

    public function __construct()
    {
        $this->ventaModel    = new VentaModel();
        $this->clienteModel  = new ClienteModel();
        $this->productoModel = new ProductoModel();
    }

    // ─────────────────────────────────────────────────────────────
    // GET /ventas — Historial de ventas
    // ─────────────────────────────────────────────────────────────
    public function index(): string
    {
        $ventas = $this->ventaModel->getVentasConCliente();
        return view('ventas/index', ['ventas' => $ventas]);
    }

    // ─────────────────────────────────────────────────────────────
    // GET /ventas/nueva — Interfaz POS de nueva venta
    // ─────────────────────────────────────────────────────────────
    public function nueva(): string
    {
        return view('ventas/nueva');
    }

    // ─────────────────────────────────────────────────────────────
    // POST /ventas/registrar — Procesa y guarda la venta
    // ─────────────────────────────────────────────────────────────
    public function registrar(): RedirectResponse
    {
        $itemsJson = $this->request->getPost('items_json');
        $items     = json_decode($itemsJson, true);

        if (empty($items) || !is_array($items)) {
            return redirect()->back()->withInput()
                ->with('error', 'Debes agregar al menos un producto al carrito.');
        }

        $tipoComprobante = $this->request->getPost('tipo_comprobante');
        if (!in_array($tipoComprobante, ['Boleta', 'Factura', 'Ticket'])) {
            return redirect()->back()->withInput()
                ->with('error', 'Tipo de comprobante inválido.');
        }

        $ventaData = [
            'tipo_comprobante' => $tipoComprobante,
            'cliente_id'       => $this->request->getPost('cliente_id') ?: null,
            'descuento'        => (float)($this->request->getPost('descuento_global') ?? 0),
            'observaciones'    => $this->request->getPost('observaciones') ?: null,
        ];

        $result = $this->ventaModel->registrarVenta($ventaData, $items, (int)session()->get('userId'));

        if (is_numeric($result)) {
            return redirect()->to(base_url('ventas'))
                ->with('success', '¡Venta registrada exitosamente! El stock fue descontado automáticamente.')
                ->with('reciente_venta_id', $result);
        }

        $error = is_array($result) ? ($result['error'] ?? 'Error al procesar.') : 'Error interno.';
        return redirect()->back()->withInput()->with('error', $error);
    }

    // ─────────────────────────────────────────────────────────────
    // GET /ventas/ver/:id — Detalle de una venta
    // ─────────────────────────────────────────────────────────────
    public function ver(int $id): string|RedirectResponse
    {
        $venta = $this->ventaModel->getVentasConCliente($id);
        if (!$venta) {
            return redirect()->to(base_url('ventas'))
                ->with('error', 'La venta solicitada no existe.');
        }

        $detalleModel = new DetalleVentaModel();
        $items        = $detalleModel->getDetalleConProducto($id);

        return view('ventas/ver', [
            'venta' => $venta,
            'items' => $items,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // GET /ventas/anular/:id — Anula la venta y restaura stock
    // ─────────────────────────────────────────────────────────────
    public function anular(int $id): RedirectResponse
    {
        $result = $this->ventaModel->anularVenta($id, (int)session()->get('userId'));

        if ($result) {
            return redirect()->to(base_url('ventas'))
                ->with('success', 'La venta ha sido anulada y el stock fue restaurado correctamente.');
        }

        return redirect()->to(base_url('ventas'))
            ->with('error', 'No se pudo anular la venta. Puede que ya esté anulada.');
    }

    // ─────────────────────────────────────────────────────────────
    // GET /ventas/buscarProducto?q= — API JSON búsqueda con stock > 0
    // ─────────────────────────────────────────────────────────────
    public function buscarProducto(): ResponseInterface
    {
        $q = trim($this->request->getGet('q') ?? '');

        if (strlen($q) < 2) {
            return $this->response->setJSON([]);
        }

        $productos = $this->productoModel
            ->select('id, codigo, nombre, precio_venta, stock')
            ->where('estado', 1)
            ->where('stock >', 0)
            ->groupStart()
                ->like('nombre', $q)
                ->orLike('codigo', $q)
            ->groupEnd()
            ->orderBy('nombre', 'ASC')
            ->limit(8)
            ->findAll();

        return $this->response->setJSON($productos);
    }

    // ─────────────────────────────────────────────────────────────
    // GET /ventas/buscarCliente?q= — API JSON búsqueda de clientes
    // ─────────────────────────────────────────────────────────────
    public function buscarCliente(): ResponseInterface
    {
        $q = trim($this->request->getGet('q') ?? '');

        if (strlen($q) < 2) {
            return $this->response->setJSON([]);
        }

        $clientes = $this->clienteModel
            ->select('id, nombres, tipo_documento, numero_documento')
            ->where('estado', 1)
            ->groupStart()
                ->like('nombres', $q)
                ->orLike('numero_documento', $q)
            ->groupEnd()
            ->orderBy('nombres', 'ASC')
            ->limit(8)
            ->findAll();

        return $this->response->setJSON($clientes);
    }

    // ─────────────────────────────────────────────────────────────
    // GET /ventas/imprimir/:id — Vista de ticket optimizada para impresión
    // ─────────────────────────────────────────────────────────────
    public function imprimir(int $id)
    {
        $venta = $this->ventaModel->getVentasConCliente($id);
        if (!$venta) {
            return redirect()->to(base_url('ventas'))
                ->with('error', 'La venta solicitada no existe.');
        }

        $detalleModel = new \App\Models\DetalleVentaModel();
        $items        = $detalleModel->getDetalleConProducto($id);

        $configuracionModel = new \App\Models\ConfiguracionModel();
        $configuracion      = $configuracionModel->find(1);

        // Si no hay configuración en la BD, creamos valores por defecto
        if (!$configuracion) {
            $configuracion = [
                'razon_social'     => 'Kobra Soft S.A.C.',
                'nombre_comercial' => 'Kobra POS',
                'ruc'              => '20123456789',
                'direccion'        => 'Av. Principal 123',
                'telefono'         => '01-4445566',
                'correo'           => 'contacto@kobra.com',
                'logo'             => null,
                'moneda'           => 'Soles',
                'simbolo_moneda'   => 'S/',
                'mensaje_ticket'   => '¡Gracias por su compra!'
            ];
        }

        return view('ventas/imprimir', [
            'venta'         => $venta,
            'items'         => $items,
            'configuracion' => $configuracion
        ]);
    }
}
