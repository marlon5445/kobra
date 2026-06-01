<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table      = 'clientes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Usamos eliminación lógica personalizada (estado = 0)

    protected $allowedFields = [
        'tipo_documento',
        'numero_documento',
        'nombres',
        'direccion',
        'telefono',
        'correo',
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
        'tipo_documento'   => 'required|min_length[2]|max_length[50]',
        'numero_documento' => 'required|min_length[3]|max_length[50]|is_unique[clientes.numero_documento,id,{id}]',
        'nombres'          => 'required|min_length[3]|max_length[255]',
        'direccion'        => 'permit_empty|max_length[255]',
        'telefono'         => 'permit_empty|max_length[50]',
        'correo'           => 'permit_empty|valid_email|max_length[150]|is_unique[clientes.correo,id,{id}]',
        'observaciones'    => 'permit_empty',
        'estado'           => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'tipo_documento' => [
            'required'   => 'El tipo de documento es obligatorio.',
            'min_length' => 'El tipo de documento debe tener al menos 2 caracteres.',
            'max_length' => 'El tipo de documento no puede superar los 50 caracteres.'
        ],
        'numero_documento' => [
            'required'   => 'El número de documento es obligatorio.',
            'min_length' => 'El número de documento debe tener al menos 3 caracteres.',
            'max_length' => 'El número de documento no puede superar los 50 caracteres.',
            'is_unique'  => 'Este número de documento ya se encuentra registrado.'
        ],
        'nombres' => [
            'required'   => 'Los nombres o la razón social son obligatorios.',
            'min_length' => 'El nombre debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre no puede superar los 255 caracteres.'
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
        'estado' => [
            'in_list' => 'El estado seleccionado no es válido.'
        ]
    ];

    protected $skipValidation = false;
}
