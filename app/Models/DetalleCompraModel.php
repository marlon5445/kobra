<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleCompraModel extends Model
{
    protected $table      = 'detalle_compras';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'compra_id', 'producto_id', 'cantidad', 'costo_unitario', 'subtotal'
    ];

    protected $useTimestamps  = false;
    protected $skipValidation = true;

    /**
     * Retorna los ítems de una compra con nombre del producto.
     */
    public function getDetalleConProducto(int $compraId): array
    {
        return $this->db->table('detalle_compras')
            ->select('detalle_compras.*, productos.nombre as producto_nombre, productos.codigo as producto_codigo')
            ->join('productos', 'productos.id = detalle_compras.producto_id', 'LEFT')
            ->where('detalle_compras.compra_id', $compraId)
            ->get()
            ->getResultArray();
    }
}
