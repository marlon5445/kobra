<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table      = 'productos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Manejado lógicamente si se desea, o borrado tradicional.

    protected $allowedFields = [
        'categoria_id', 
        'codigo', 
        'nombre', 
        'descripcion', 
        'precio_compra', 
        'precio_venta', 
        'stock', 
        'stock_minimo', 
        'imagen', 
        'estado'
    ];

    // Marcas de tiempo automáticas
    protected $useTimestamps = true;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';

    // Reglas de validación en español para asegurar la consistencia del inventario
    protected $validationRules = [
        'id'            => 'permit_empty|is_natural_no_zero',
        'categoria_id'  => 'required|is_natural_no_zero',
        'codigo'        => 'required|min_length[3]|max_length[50]|is_unique[productos.codigo,id,{id}]',
        'nombre'        => 'required|min_length[3]|max_length[150]',
        'precio_compra' => 'required|decimal|greater_than_equal_to[0]',
        'precio_venta'  => 'required|decimal|greater_than_equal_to[0]',
        'stock'         => 'required|integer|greater_than_equal_to[0]',
        'stock_minimo'  => 'required|integer|greater_than_equal_to[0]',
        'estado'        => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'categoria_id' => [
            'required'            => 'Debes seleccionar una categoría válida.',
            'is_natural_no_zero'  => 'Debes seleccionar una categoría válida.'
        ],
        'codigo' => [
            'required'   => 'El código de barras o SKU es obligatorio.',
            'min_length' => 'El código debe tener al menos 3 caracteres.',
            'max_length' => 'El código no puede superar los 50 caracteres.',
            'is_unique'  => 'Ya existe un producto registrado con este código de barras.'
        ],
        'nombre' => [
            'required'   => 'El nombre del producto es obligatorio.',
            'min_length' => 'El nombre debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre no puede superar los 150 caracteres.'
        ],
        'precio_compra' => [
            'required'               => 'El precio de compra es obligatorio.',
            'decimal'                => 'El precio de compra debe ser un número decimal válido.',
            'greater_than_equal_to' => 'El precio de compra no puede ser menor a 0.'
        ],
        'precio_venta' => [
            'required'               => 'El precio de venta es obligatorio.',
            'decimal'                => 'El precio de venta debe ser un número decimal válido.',
            'greater_than_equal_to' => 'El precio de venta no puede ser menor a 0.'
        ],
        'stock' => [
            'required'               => 'El stock inicial es obligatorio.',
            'integer'                => 'El stock debe ser un número entero.',
            'greater_than_equal_to' => 'El stock no puede ser menor a 0.'
        ],
        'stock_minimo' => [
            'required'               => 'El stock mínimo es obligatorio.',
            'integer'                => 'El stock mínimo debe ser un número entero.',
            'greater_than_equal_to' => 'El stock mínimo no puede ser menor a 0.'
        ],
        'estado' => [
            'in_list' => 'El estado del producto no es válido.'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Recupera los productos realizando un JOIN con categorías
     * para obtener el nombre descriptivo de la categoría.
     */
    public function getProductosConCategoria(int $id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('productos.*, categorias.nombre as categoria_nombre');
        $builder->join('categorias', 'categorias.id = productos.categoria_id', 'INNER');
        $builder->where('productos.estado', 1); // Solo activos

        if ($id !== null) {
            $builder->where('productos.id', $id);
            return $builder->get()->getRowArray();
        }

        $builder->orderBy('productos.id', 'DESC');
        return $builder->get()->getResultArray();
    }
}
