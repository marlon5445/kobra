<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleVentaModel extends Model
{
    protected $table      = 'detalle_ventas';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'venta_id', 'producto_id', 'cantidad',
        'precio_unitario', 'descuento', 'subtotal'
    ];

    protected $useTimestamps  = false;
    protected $skipValidation = true;

    /**
     * Retorna los ítems de una venta con nombre y código del producto.
     */
    public function getDetalleConProducto(int $ventaId): array
    {
        return $this->db->table('detalle_ventas')
            ->select('detalle_ventas.*, productos.nombre as producto_nombre, productos.codigo as producto_codigo')
            ->join('productos', 'productos.id = detalle_ventas.producto_id', 'LEFT')
            ->where('detalle_ventas.venta_id', $ventaId)
            ->get()
            ->getResultArray();
    }
}
