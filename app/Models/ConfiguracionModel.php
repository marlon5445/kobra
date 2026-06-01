<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfiguracionModel extends Model
{
    protected $table      = 'configuracion_empresa';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'razon_social', 
        'nombre_comercial', 
        'ruc', 
        'direccion', 
        'telefono', 
        'correo', 
        'logo', 
        'moneda', 
        'simbolo_moneda', 
        'mensaje_ticket'
    ];

    // Marcas de tiempo automáticas
    protected $useTimestamps = true;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';

    // Reglas de validación en español
    protected $validationRules = [
        'id'             => 'permit_empty|is_natural_no_zero',
        'razon_social'   => 'required|min_length[3]|max_length[255]',
        'nombre_comercial'=> 'permit_empty|max_length[255]',
        'ruc'            => 'required|min_length[8]|max_length[20]',
        'direccion'      => 'permit_empty|max_length[255]',
        'telefono'       => 'permit_empty|max_length[50]',
        'correo'         => 'permit_empty|valid_email|max_length[150]',
        'logo'           => 'permit_empty|max_length[255]',
        'moneda'         => 'required|min_length[3]|max_length[50]',
        'simbolo_moneda' => 'required|max_length[10]',
        'mensaje_ticket' => 'permit_empty'
    ];

    protected $validationMessages = [
        'razon_social' => [
            'required'   => 'La razón social es obligatoria.',
            'min_length' => 'La razón social debe tener al menos 3 caracteres.',
            'max_length' => 'La razón social no puede superar los 255 caracteres.'
        ],
        'ruc' => [
            'required'   => 'El RUC es obligatorio.',
            'min_length' => 'El RUC debe tener al menos 8 caracteres.',
            'max_length' => 'El RUC no puede superar los 20 caracteres.'
        ],
        'correo' => [
            'valid_email' => 'Debes ingresar un correo electrónico válido.',
            'max_length'  => 'El correo electrónico no puede superar los 150 caracteres.'
        ],
        'moneda' => [
            'required'   => 'El nombre de la moneda es obligatorio.',
            'min_length' => 'El nombre de la moneda debe tener al menos 3 caracteres.'
        ],
        'simbolo_moneda' => [
            'required'   => 'El símbolo de la moneda es obligatorio.'
        ]
    ];

    protected $skipValidation = false;
}
