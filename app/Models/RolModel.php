<?php

namespace App\Models;

use CodeIgniter\Model;

class RolModel extends Model
{
    protected $table      = 'roles';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Manejado mediante el campo estado (1: Activo, 0: Inactivo)

    protected $allowedFields = [
        'nombre', 
        'descripcion', 
        'estado'
    ];

    // Gestión automática de marcas de tiempo
    protected $useTimestamps = true;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';

    // Reglas de validación en español
    protected $validationRules = [
        'id'     => 'permit_empty|is_natural_no_zero',
        'nombre' => 'required|min_length[3]|max_length[100]|is_unique[roles.nombre,id,{id}]',
        'estado' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre del rol es obligatorio.',
            'min_length' => 'El nombre debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre no puede superar los 100 caracteres.',
            'is_unique'  => 'Ya existe un rol registrado con este nombre.'
        ],
        'estado' => [
            'in_list' => 'El estado seleccionado no es válido.'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Recupera todos los permisos actualmente asociados a un rol específico.
     */
    public function getPermisosRol(int $rolId): array
    {
        return $this->db->table('rol_permisos')
            ->select('permiso_id')
            ->where('rol_id', $rolId)
            ->get()
            ->getResultArray();
    }

    /**
     * Sincroniza las asignaciones de permisos del rol en la tabla pivote.
     * Elimina las asignaciones previas e inserta las nuevas en una sola transacción.
     */
    public function guardarPermisos(int $rolId, array $permisoIds): bool
    {
        $this->db->transStart();

        // 1. Eliminar todos los permisos actuales del rol
        $this->db->table('rol_permisos')->where('rol_id', $rolId)->delete();

        // 2. Insertar los nuevos permisos si se seleccionaron
        if (!empty($permisoIds)) {
            $data = [];
            foreach ($permisoIds as $permisoId) {
                $data[] = [
                    'rol_id'     => $rolId,
                    'permiso_id' => $permisoId
                ];
            }
            $this->db->table('rol_permisos')->insertBatch($data);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
