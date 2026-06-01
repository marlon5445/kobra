<?php

namespace App\Controllers;

use App\Models\CompraModel;
use App\Models\DetalleCompraModel;
use App\Models\ProveedorModel;
use App\Models\ProductoModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Compras extends BaseController
{
    protected CompraModel   $compraModel;
    protected ProveedorModel $proveedorModel;
    protected ProductoModel  $productoModel;

    public function __construct()
    {
        $this->compraModel    = new CompraModel();
        $this->proveedorModel = new ProveedorModel();
        $this->productoModel  = new ProductoModel();
    }

    // ─────────────────────────────────────────────────────────────
    // GET /compras — Historial de compras
    // ─────────────────────────────────────────────────────────────
    public function index(): string
    {
        $compras = $this->compraModel->getComprasConProveedor();
        return view('compras/index', ['compras' => $compras]);
    }

    // ─────────────────────────────────────────────────────────────
    // GET /compras/nueva — Formulario de nueva compra
    // ─────────────────────────────────────────────────────────────
    public function nueva(): string
    {
        $proveedores = $this->proveedorModel
            ->where('estado', 1)
            ->orderBy('razon_social', 'ASC')
            ->findAll();

        return view('compras/nueva', ['proveedores' => $proveedores]);
    }

    // ─────────────────────────────────────────────────────────────
    // POST /compras/registrar — Procesa y guarda la compra
    // ─────────────────────────────────────────────────────────────
    public function registrar(): RedirectResponse
    {
        $itemsJson = $this->request->getPost('items_json');
        $items     = json_decode($itemsJson, true);

        if (empty($items) || !is_array($items)) {
            return redirect()->back()->withInput()
                ->with('error', 'Debes agregar al menos un producto al carrito.');
        }

        $proveedorId = (int)$this->request->getPost('proveedor_id');
        if (!$proveedorId) {
            return redirect()->back()->withInput()
                ->with('error', 'Debes seleccionar un proveedor.');
        }

        $compraData = [
            'proveedor_id'  => $proveedorId,
            'observaciones' => $this->request->getPost('observaciones') ?: null,
        ];

        $result = $this->compraModel->registrarCompra($compraData, $items, (int)session()->get('userId'));

        if ($result === true) {
            return redirect()->to(base_url('compras'))
                ->with('success', '¡Compra registrada exitosamente! El stock fue actualizado automáticamente.');
        }

        return redirect()->back()->withInput()
            ->with('error', 'Error interno al registrar la compra. Por favor intenta nuevamente.');
    }

    // ─────────────────────────────────────────────────────────────
    // GET /compras/ver/:id — Detalle de una compra
    // ─────────────────────────────────────────────────────────────
    public function ver(int $id): string|RedirectResponse
    {
        $compra = $this->compraModel->getComprasConProveedor($id);
        if (!$compra) {
            return redirect()->to(base_url('compras'))
                ->with('error', 'La compra solicitada no existe.');
        }

        $detalleModel = new DetalleCompraModel();
        $items        = $detalleModel->getDetalleConProducto($id);

        return view('compras/ver', [
            'compra' => $compra,
            'items'  => $items,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // GET /compras/anular/:id — Anula la compra y revierte stock
    // ─────────────────────────────────────────────────────────────
    public function anular(int $id): RedirectResponse
    {
        $result = $this->compraModel->anularCompra($id, (int)session()->get('userId'));

        if ($result) {
            return redirect()->to(base_url('compras'))
                ->with('success', 'La compra ha sido anulada y el stock fue revertido correctamente.');
        }

        return redirect()->to(base_url('compras'))
            ->with('error', 'No se pudo anular la compra. Puede que ya esté anulada.');
    }

    // ─────────────────────────────────────────────────────────────
    // GET /compras/buscarProducto?q= — API JSON de búsqueda
    // ─────────────────────────────────────────────────────────────
    public function buscarProducto(): ResponseInterface
    {
        $q = trim($this->request->getGet('q') ?? '');

        if (strlen($q) < 2) {
            return $this->response->setJSON([]);
        }

        $productos = $this->productoModel
            ->select('id, codigo, nombre, precio_compra, precio_venta, stock')
            ->where('estado', 1)
            ->groupStart()
                ->like('nombre', $q)
                ->orLike('codigo', $q)
            ->groupEnd()
            ->orderBy('nombre', 'ASC')
            ->limit(8)
            ->findAll();

        return $this->response->setJSON($productos);
    }
}
