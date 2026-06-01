<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimientoInventarioModel extends Model
{
    protected $table      = 'movimientos_inventario';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'producto_id', 'tipo_movimiento', 'documento',
        'cantidad', 'stock_anterior', 'stock_nuevo',
        'usuario_id', 'fecha'
    ];

    protected $useTimestamps  = false;
    protected $skipValidation = true;

    /**
     * Retorna el historial de movimientos de un producto
     * con JOIN a usuarios para mostrar quién los generó.
     */
    public function getMovimientosPorProducto(int $productoId): array
    {
        return $this->db->table('movimientos_inventario')
            ->select('movimientos_inventario.*, CONCAT(usuarios.nombres, " ", usuarios.apellidos) as usuario_nombre')
            ->join('usuarios', 'usuarios.id = movimientos_inventario.usuario_id', 'LEFT')
            ->where('movimientos_inventario.producto_id', $productoId)
            ->orderBy('movimientos_inventario.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Retorna todos los movimientos con nombre del producto incluido.
     */
    public function getTodosConProducto(): array
    {
        return $this->db->table('movimientos_inventario')
            ->select('movimientos_inventario.*, productos.nombre as producto_nombre, productos.codigo as producto_codigo, CONCAT(usuarios.nombres, " ", usuarios.apellidos) as usuario_nombre')
            ->join('productos', 'productos.id = movimientos_inventario.producto_id', 'LEFT')
            ->join('usuarios',  'usuarios.id = movimientos_inventario.usuario_id', 'LEFT')
            ->orderBy('movimientos_inventario.id', 'DESC')
            ->get()
            ->getResultArray();
    }
}
