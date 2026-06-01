<?php

namespace App\Models;

use CodeIgniter\Model;

class PermisoModel extends Model
{
    protected $table      = 'permisos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nombre', 
        'modulo', 
        'accion', 
        'descripcion'
    ];

    protected $useTimestamps = false; // Esta tabla es de metadatos del sistema, no requiere timestamps automáticos.

    protected $validationRules = [
        'id'     => 'permit_empty|is_natural_no_zero',
        'nombre' => 'required|min_length[3]|max_length[150]|is_unique[permisos.nombre,id,{id}]',
        'modulo' => 'required|min_length[2]|max_length[100]',
        'accion' => 'required|min_length[2]|max_length[50]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El identificador único del permiso es obligatorio.',
            'is_unique'  => 'Ya existe un permiso registrado con este identificador único.'
        ],
        'modulo' => [
            'required'   => 'El nombre del módulo es obligatorio.'
        ],
        'accion' => [
            'required'   => 'La acción del permiso es obligatoria.'
        ]
    ];

    protected $skipValidation = false;
}
