<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table      = 'categorias';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Usamos eliminación lógica personalizada (estado = 0)

    protected $allowedFields = [
        'nombre', 
        'descripcion', 
        'estado'
    ];

    // Marcas de tiempo automáticas
    protected $useTimestamps = true;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';

    // Reglas de validación en español
    protected $validationRules = [
        'id'     => 'permit_empty|is_natural_no_zero',
        'nombre' => 'required|min_length[3]|max_length[150]|is_unique[categorias.nombre,id,{id}]',
        'estado' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre de la categoría es obligatorio.',
            'min_length' => 'El nombre debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre no puede superar los 150 caracteres.',
            'is_unique'  => 'Ya existe una categoría registrada con este nombre.'
        ],
        'estado' => [
            'in_list' => 'El estado de la categoría seleccionado no es válido.'
        ]
    ];

    protected $skipValidation = false;
}
