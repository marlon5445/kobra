<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Manejado lógicamente mediante el campo 'estado' (1: Activo, 0: Inactivo)

    protected $allowedFields = [
        'nombres', 
        'apellidos', 
        'usuario', 
        'correo', 
        'contrasena', 
        'estado'
    ];

    // Marcas de tiempo automáticas
    protected $useTimestamps = true;
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';

    // Reglas de validación
    protected $validationRules = [
        'id'        => 'permit_empty|is_natural_no_zero',
        'nombres'   => 'required|min_length[2]|max_length[100]',
        'apellidos' => 'required|min_length[2]|max_length[100]',
        'usuario'   => 'required|min_length[3]|max_length[50]|is_unique[usuarios.usuario,id,{id}]',
        'correo'    => 'required|valid_email|is_unique[usuarios.correo,id,{id}]',
        'contrasena'=> 'permit_empty|min_length[6]',
        'estado'    => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nombres' => [
            'required'   => 'Los nombres son obligatorios.',
            'min_length' => 'Los nombres deben tener al menos 2 caracteres.'
        ],
        'apellidos' => [
            'required'   => 'Los apellidos son obligatorios.',
            'min_length' => 'Los apellidos deben tener al menos 2 caracteres.'
        ],
        'usuario' => [
            'required'   => 'El nombre de usuario es obligatorio.',
            'min_length' => 'El usuario debe tener al menos 3 caracteres.',
            'is_unique'  => 'Este nombre de usuario ya está registrado en el sistema.'
        ],
        'correo' => [
            'required'    => 'El correo electrónico es obligatorio.',
            'valid_email' => 'Debes ingresar un correo electrónico válido.',
            'is_unique'   => 'Este correo electrónico ya está registrado en el sistema.'
        ],
        'contrasena' => [
            'min_length' => 'La contraseña debe tener al menos 6 caracteres.'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Recupera un usuario o todos, trayendo consigo la información del Rol asociado (INNER JOIN).
     */
    public function getUsuarioConRol(int $id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('usuarios.*, roles.nombre as rol_nombre, roles.id as rol_id');
        $builder->join('usuario_roles', 'usuario_roles.usuario_id = usuarios.id', 'INNER');
        $builder->join('roles', 'roles.id = usuario_roles.rol_id', 'INNER');
        $builder->where('usuarios.estado', 1); // Solo activos

        if ($id !== null) {
            $builder->where('usuarios.id', $id);
            return $builder->get()->getRowArray();
        }

        $builder->orderBy('usuarios.id', 'DESC');
        return $builder->get()->getResultArray();
    }

    /**
     * Guarda o actualiza un usuario y asocia su rol en una transacción de base de datos.
     */
    public function guardarUsuarioConRol(array $userData, int $rolId, int $userId = null): bool
    {
        $this->db->transStart();

        if ($userId === null) {
            // 1. Insertar el nuevo usuario
            $this->insert($userData);
            $newUserId = $this->insertID();

            // 2. Asociar el rol en usuario_roles
            $this->db->table('usuario_roles')->insert([
                'usuario_id' => $newUserId,
                'rol_id'     => $rolId
            ]);
        } else {
            // 1. Actualizar usuario existente
            $this->update($userId, $userData);

            // 2. Actualizar el rol en la tabla pivote (borrar y re-insertar para asegurar unicidad)
            $this->db->table('usuario_roles')->where('usuario_id', $userId)->delete();
            $this->db->table('usuario_roles')->insert([
                'usuario_id' => $userId,
                'rol_id'     => $rolId
            ]);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Devuelve todos los nombres de los permisos asignados a un usuario
     * analizando la tabla de rol_permisos de su rol activo.
     */
    public function getPermisosUsuario(int $userId): array
    {
        $result = $this->db->table('usuario_roles')
            ->select('permisos.nombre')
            ->join('rol_permisos', 'rol_permisos.rol_id = usuario_roles.rol_id')
            ->join('permisos', 'permisos.id = rol_permisos.permiso_id')
            ->where('usuario_roles.usuario_id', $userId)
            ->get()
            ->getResultArray();

        // Aplanar el array a una sola lista de strings (ej. ['dashboard.ver', 'productos.ver'])
        return array_column($result, 'nombre');
    }
}
