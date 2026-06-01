<?php

namespace App\Models;

use CodeIgniter\Model;

class CompraModel extends Model
{
    protected $table      = 'compras';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'numero_compra', 'proveedor_id', 'usuario_id',
        'fecha', 'subtotal', 'impuesto', 'total',
        'observaciones', 'estado'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';

    protected $skipValidation = true; // Validación manual en el controlador

    // ─────────────────────────────────────────────────────────────
    // Genera el siguiente número de compra secuencial (C-0001, C-0002...)
    // ─────────────────────────────────────────────────────────────
    public function generarNumeroCompra(): string
    {
        $ultimo = $this->db->table('compras')
            ->select('numero_compra')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        if (!$ultimo || empty($ultimo['numero_compra'])) {
            return 'C-0001';
        }

        $partes  = explode('-', $ultimo['numero_compra']);
        $numero  = (int)($partes[1] ?? 0) + 1;
        return 'C-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    // ─────────────────────────────────────────────────────────────
    // Registra una compra completa en una transacción atómica
    // ─────────────────────────────────────────────────────────────
    public function registrarCompra(array $compraData, array $items, int $userId): bool
    {
        $this->db->transStart();

        // 1. Calcular totales
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float)$item['subtotal'];
        }
        $impuesto = round($subtotal * 0.18, 2);
        $total    = round($subtotal + $impuesto, 2);

        // 2. Armar cabecera
        $cabecera = [
            'numero_compra' => $this->generarNumeroCompra(),
            'proveedor_id'  => (int)$compraData['proveedor_id'],
            'usuario_id'    => $userId,
            'fecha'         => date('Y-m-d H:i:s'),
            'subtotal'      => $subtotal,
            'impuesto'      => $impuesto,
            'total'         => $total,
            'observaciones' => $compraData['observaciones'] ?? null,
            'estado'        => 1,
        ];

        // 3. Insertar compra
        $this->insert($cabecera);
        $compraId     = $this->insertID();
        $numeroCompra = $cabecera['numero_compra'];

        // 4. Procesar ítems
        $detalleModel  = new DetalleCompraModel();
        $productoModel = new ProductoModel();
        $movModel      = new MovimientoInventarioModel();

        foreach ($items as $item) {
            // 4a. Insertar detalle
            $detalleModel->insert([
                'compra_id'      => $compraId,
                'producto_id'    => (int)$item['producto_id'],
                'cantidad'       => (int)$item['cantidad'],
                'costo_unitario' => (float)$item['costo_unitario'],
                'subtotal'       => (float)$item['subtotal'],
            ]);

            // 4b. Leer stock actual
            $producto      = $productoModel->find((int)$item['producto_id']);
            $stockAnterior = (int)$producto['stock'];
            $stockNuevo    = $stockAnterior + (int)$item['cantidad'];

            // 4c. Actualizar stock
            $productoModel->update((int)$item['producto_id'], ['stock' => $stockNuevo]);

            // 4d. Registrar movimiento
            $movModel->insert([
                'producto_id'    => (int)$item['producto_id'],
                'tipo_movimiento'=> 'entrada',
                'documento'      => $numeroCompra,
                'cantidad'       => (int)$item['cantidad'],
                'stock_anterior' => $stockAnterior,
                'stock_nuevo'    => $stockNuevo,
                'usuario_id'     => $userId,
                'fecha'          => date('Y-m-d H:i:s'),
            ]);
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    // ─────────────────────────────────────────────────────────────
    // Anula una compra y revierte el stock
    // ─────────────────────────────────────────────────────────────
    public function anularCompra(int $id, int $userId): bool
    {
        $compra = $this->find($id);
        if (!$compra || $compra['estado'] == 0) {
            return false;
        }

        $this->db->transStart();

        // 1. Marcar como anulada
        $this->update($id, ['estado' => 0]);

        // 2. Revertir stock de cada ítem
        $detalleModel  = new DetalleCompraModel();
        $productoModel = new ProductoModel();
        $movModel      = new MovimientoInventarioModel();

        $items = $detalleModel->where('compra_id', $id)->findAll();

        foreach ($items as $item) {
            $producto      = $productoModel->find($item['producto_id']);
            $stockAnterior = (int)$producto['stock'];
            $stockNuevo    = max(0, $stockAnterior - (int)$item['cantidad']);

            $productoModel->update($item['producto_id'], ['stock' => $stockNuevo]);

            $movModel->insert([
                'producto_id'    => $item['producto_id'],
                'tipo_movimiento'=> 'ajuste',
                'documento'      => 'ANU-' . $compra['numero_compra'],
                'cantidad'       => $item['cantidad'],
                'stock_anterior' => $stockAnterior,
                'stock_nuevo'    => $stockNuevo,
                'usuario_id'     => $userId,
                'fecha'          => date('Y-m-d H:i:s'),
            ]);
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    // ─────────────────────────────────────────────────────────────
    // Listado con JOIN a proveedores y usuarios
    // ─────────────────────────────────────────────────────────────
    public function getComprasConProveedor(int $id = null)
    {
        $builder = $this->db->table('compras')
            ->select('compras.*, proveedores.razon_social as proveedor_nombre, CONCAT(usuarios.nombres, " ", usuarios.apellidos) as usuario_nombre')
            ->join('proveedores', 'proveedores.id = compras.proveedor_id', 'LEFT')
            ->join('usuarios',    'usuarios.id = compras.usuario_id', 'LEFT');

        if ($id !== null) {
            $builder->where('compras.id', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->orderBy('compras.id', 'DESC')->get()->getResultArray();
    }
}
