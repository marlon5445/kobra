<?php

namespace App\Models;

use CodeIgniter\Model;

class ProveedorModel extends Model
{
    protected $table      = 'proveedores';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Usamos eliminación lógica personalizada (estado = 0)

    protected $allowedFields = [
        'ruc',
        'razon_social',
        'nombre_comercial',
        'direccion',
        'telefono',
        'correo',
        'contacto',
        'observaciones',
        'estado'
    ];

    // Marcas de tiempo automáticas
    protected $useTimestamps = true;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';

    // Reglas de validación en español
    protected $validationRules = [
        'id'               => 'permit_empty|is_natural_no_zero',
        'ruc'              => 'required|min_length[8]|max_length[50]|is_unique[proveedores.ruc,id,{id}]',
        'razon_social'     => 'required|min_length[3]|max_length[255]',
        'nombre_comercial' => 'permit_empty|max_length[255]',
        'direccion'        => 'permit_empty|max_length[255]',
        'telefono'         => 'permit_empty|max_length[50]',
        'correo'           => 'permit_empty|valid_email|max_length[150]|is_unique[proveedores.correo,id,{id}]',
        'contacto'         => 'permit_empty|max_length[150]',
        'observaciones'    => 'permit_empty',
        'estado'           => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'ruc' => [
            'required'   => 'El RUC del proveedor es obligatorio.',
            'min_length' => 'El RUC debe tener al menos 8 caracteres.',
            'max_length' => 'El RUC no puede superar los 50 caracteres.',
            'is_unique'  => 'Este número de RUC ya se encuentra registrado.'
        ],
        'razon_social' => [
            'required'   => 'La razón social es obligatoria.',
            'min_length' => 'La razón social debe tener al menos 3 caracteres.',
            'max_length' => 'La razón social no puede superar los 255 caracteres.'
        ],
        'nombre_comercial' => [
            'max_length' => 'El nombre comercial no puede superar los 255 caracteres.'
        ],
        'direccion' => [
            'max_length' => 'La dirección no puede superar los 255 caracteres.'
        ],
        'telefono' => [
            'max_length' => 'El teléfono no puede superar los 50 caracteres.'
        ],
        'correo' => [
            'valid_email' => 'Debes ingresar un correo electrónico válido.',
            'max_length'  => 'El correo electrónico no puede superar los 150 caracteres.',
            'is_unique'   => 'Este correo electrónico ya se encuentra registrado.'
        ],
        'contacto' => [
            'max_length' => 'El nombre de contacto no puede superar los 150 caracteres.'
        ],
        'estado' => [
            'in_list' => 'El estado seleccionado no es válido.'
        ]
    ];

    protected $skipValidation = false;
}
