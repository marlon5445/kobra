<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table      = 'ventas';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'numero_venta', 'tipo_comprobante', 'cliente_id', 'usuario_id',
        'fecha', 'subtotal', 'descuento', 'impuesto', 'total',
        'observaciones', 'estado'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';

    protected $skipValidation = true;

    // ─────────────────────────────────────────────────────────────
    // Genera el siguiente número de venta por tipo de comprobante
    // Boleta → B-0001, Factura → F-0001, Ticket → T-0001
    // ─────────────────────────────────────────────────────────────
    public function generarNumeroVenta(string $tipo): string
    {
        $prefijos = [
            'Boleta'  => 'B',
            'Factura' => 'F',
            'Ticket'  => 'T',
        ];
        $prefijo = $prefijos[$tipo] ?? 'B';

        $ultimo = $this->db->table('ventas')
            ->select('numero_venta')
            ->like('numero_venta', $prefijo . '-', 'after')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        if (!$ultimo || empty($ultimo['numero_venta'])) {
            return $prefijo . '-0001';
        }

        $partes = explode('-', $ultimo['numero_venta']);
        $numero = (int)($partes[1] ?? 0) + 1;
        return $prefijo . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    // ─────────────────────────────────────────────────────────────
    // Registra una venta completa en transacción atómica
    // Retorna true o array con clave 'error'
    // ─────────────────────────────────────────────────────────────
    public function registrarVenta(array $ventaData, array $items, int $userId)
    {
        $productoModel = new ProductoModel();

        // 1. Validar stock antes de iniciar la transacción
        foreach ($items as $item) {
            $producto = $productoModel->find((int)$item['producto_id']);
            if (!$producto || (int)$producto['stock'] < (int)$item['cantidad']) {
                $disponible = $producto ? $producto['stock'] : 0;
                $nombre     = $producto ? $producto['nombre'] : 'ID:' . $item['producto_id'];
                return ['error' => "Stock insuficiente para \"{$nombre}\". Disponible: {$disponible} unidad(es)."];
            }
        }

        $this->db->transStart();

        // 2. Calcular totales
        $subtotal  = 0;
        foreach ($items as $item) {
            $subtotal += (float)$item['subtotal'];
        }
        $descuento = (float)($ventaData['descuento'] ?? 0);
        $base      = $subtotal - $descuento;
        $impuesto  = ($ventaData['tipo_comprobante'] === 'Factura') ? round($base * 0.18, 2) : 0.00;
        $total     = round($base + $impuesto, 2);

        // 3. Armar cabecera
        $clienteId = !empty($ventaData['cliente_id']) ? (int)$ventaData['cliente_id'] : null;

        $cabecera = [
            'numero_venta'    => $this->generarNumeroVenta($ventaData['tipo_comprobante']),
            'tipo_comprobante'=> $ventaData['tipo_comprobante'],
            'cliente_id'      => $clienteId,
            'usuario_id'      => $userId,
            'fecha'           => date('Y-m-d H:i:s'),
            'subtotal'        => $subtotal,
            'descuento'       => $descuento,
            'impuesto'        => $impuesto,
            'total'           => $total,
            'observaciones'   => $ventaData['observaciones'] ?? null,
            'estado'          => 1,
        ];

        // 4. Insertar venta
        $this->insert($cabecera);
        $ventaId     = $this->insertID();
        $numeroVenta = $cabecera['numero_venta'];

        // 5. Procesar ítems
        $detalleModel = new DetalleVentaModel();
        $movModel     = new MovimientoInventarioModel();

        foreach ($items as $item) {
            // 5a. Insertar detalle
            $detalleModel->insert([
                'venta_id'       => $ventaId,
                'producto_id'    => (int)$item['producto_id'],
                'cantidad'       => (int)$item['cantidad'],
                'precio_unitario'=> (float)$item['precio_unitario'],
                'descuento'      => (float)($item['descuento'] ?? 0),
                'subtotal'       => (float)$item['subtotal'],
            ]);

            // 5b. Actualizar stock
            $producto      = $productoModel->find((int)$item['producto_id']);
            $stockAnterior = (int)$producto['stock'];
            $stockNuevo    = $stockAnterior - (int)$item['cantidad'];

            $productoModel->update((int)$item['producto_id'], ['stock' => $stockNuevo]);

            // 5c. Registrar movimiento
            $movModel->insert([
                'producto_id'    => (int)$item['producto_id'],
                'tipo_movimiento'=> 'salida',
                'documento'      => $numeroVenta,
                'cantidad'       => (int)$item['cantidad'],
                'stock_anterior' => $stockAnterior,
                'stock_nuevo'    => $stockNuevo,
                'usuario_id'     => $userId,
                'fecha'          => date('Y-m-d H:i:s'),
            ]);
        }

        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            return ['error' => 'Error interno al procesar la venta. Por favor intenta nuevamente.'];
        }

        return $ventaId;
    }

    // ─────────────────────────────────────────────────────────────
    // Anula una venta y restaura el stock
    // ─────────────────────────────────────────────────────────────
    public function anularVenta(int $id, int $userId): bool
    {
        $venta = $this->find($id);
        if (!$venta || $venta['estado'] == 0) {
            return false;
        }

        $this->db->transStart();

        $this->update($id, ['estado' => 0]);

        $detalleModel  = new DetalleVentaModel();
        $productoModel = new ProductoModel();
        $movModel      = new MovimientoInventarioModel();

        $items = $detalleModel->where('venta_id', $id)->findAll();

        foreach ($items as $item) {
            $producto      = $productoModel->find($item['producto_id']);
            $stockAnterior = (int)$producto['stock'];
            $stockNuevo    = $stockAnterior + (int)$item['cantidad'];

            $productoModel->update($item['producto_id'], ['stock' => $stockNuevo]);

            $movModel->insert([
                'producto_id'    => $item['producto_id'],
                'tipo_movimiento'=> 'ajuste',
                'documento'      => 'ANU-' . $venta['numero_venta'],
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
    // Listado con JOIN a clientes y usuarios
    // ─────────────────────────────────────────────────────────────
    public function getVentasConCliente(int $id = null)
    {
        $builder = $this->db->table('ventas')
            ->select('ventas.*, clientes.nombres as cliente_nombre, CONCAT(usuarios.nombres, " ", usuarios.apellidos) as usuario_nombre')
            ->join('clientes', 'clientes.id = ventas.cliente_id', 'LEFT')
            ->join('usuarios', 'usuarios.id = ventas.usuario_id', 'LEFT');

        if ($id !== null) {
            $builder->where('ventas.id', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->orderBy('ventas.id', 'DESC')->get()->getResultArray();
    }
}
