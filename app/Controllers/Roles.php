<?php

namespace App\Controllers;

use App\Models\RolModel;
use App\Models\PermisoModel;
use CodeIgniter\HTTP\RedirectResponse;

class Roles extends BaseController
{
    protected RolModel $rolModel;
    protected PermisoModel $permisoModel;

    public function __construct()
    {
        $this->rolModel     = new RolModel();
        $this->permisoModel = new PermisoModel();
    }

    public function index(): string
    {
        $roles = $this->rolModel->orderBy('id', 'DESC')->findAll();
        return view('roles/index', ['roles' => $roles]);
    }

    public function crear(): string
    {
        return view('roles/crear');
    }

    public function guardar(): RedirectResponse
    {
        $data = [
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'estado'      => 1
        ];

        if ($this->rolModel->insert($data)) {
            return redirect()->to(base_url('roles'))->with('success', 'El rol se ha creado exitosamente.');
        }

        return redirect()->back()->withInput()->with('errors', $this->rolModel->errors());
    }

    public function editar(int $id)
    {
        $rol = $this->rolModel->find($id);
        if (!$rol) {
            return redirect()->to(base_url('roles'))->with('error', 'El rol no existe.');
        }

        return view('roles/editar', ['rol' => $rol]);
    }

    public function actualizar(int $id): RedirectResponse
    {
        $rol = $this->rolModel->find($id);
        if (!$rol) {
            return redirect()->to(base_url('roles'))->with('error', 'El rol no existe.');
        }

        $data = [
            'id'          => $id,
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
        ];

        if ($this->rolModel->save($data)) {
            return redirect()->to(base_url('roles'))->with('success', 'El rol se ha actualizado correctamente.');
        }

        return redirect()->back()->withInput()->with('errors', $this->rolModel->errors());
    }

    public function eliminar(int $id): RedirectResponse
    {
        $rol = $this->rolModel->find($id);
        if (!$rol) {
            return redirect()->to(base_url('roles'))->with('error', 'El rol no existe.');
        }

        // Proteger roles críticos del sistema
        if ($id === 1) {
            return redirect()->to(base_url('roles'))->with('error', 'El rol Administrador no puede ser eliminado para proteger la integridad del sistema.');
        }

        $this->rolModel->update($id, ['estado' => 0]);
        return redirect()->to(base_url('roles'))->with('success', 'El rol "' . esc($rol['nombre']) . '" ha sido desactivado.');
    }

    /**
     * Muestra la matriz visual de permisos para asignar a un rol.
     */
    public function permisos(int $id): string
    {
        $rol = $this->rolModel->find($id);
        if (!$rol) {
            return redirect()->to(base_url('roles'))->with('error', 'El rol no existe.');
        }

        // Obtener todos los permisos agrupados por módulo
        $todosPermisos = $this->permisoModel->orderBy('modulo', 'ASC')->orderBy('accion', 'ASC')->findAll();

        // Agrupar por módulo para construir la matriz visual
        $permisosPorModulo = [];
        foreach ($todosPermisos as $permiso) {
            $permisosPorModulo[$permiso['modulo']][] = $permiso;
        }

        // Obtener los IDs de permisos actuales del rol
        $permisosActivos = $this->rolModel->getPermisosRol($id);
        $permisosActivosIds = array_column($permisosActivos, 'permiso_id');

        return view('roles/permisos', [
            'rol'                => $rol,
            'permisosPorModulo'  => $permisosPorModulo,
            'permisosActivosIds' => $permisosActivosIds
        ]);
    }

    /**
     * Guarda los cambios de la matriz de permisos del rol.
     */
    public function guardarPermisos(int $id): RedirectResponse
    {
        $rol = $this->rolModel->find($id);
        if (!$rol) {
            return redirect()->to(base_url('roles'))->with('error', 'El rol no existe.');
        }

        $permisoIds = $this->request->getPost('permisos') ?? [];
        $permisoIds = array_map('intval', $permisoIds);

        if ($this->rolModel->guardarPermisos($id, $permisoIds)) {
            return redirect()->to(base_url('roles'))->with('success', 'Los permisos del rol "' . esc($rol['nombre']) . '" se han actualizado correctamente.');
        }

        return redirect()->back()->with('error', 'No se pudieron guardar los permisos. Por favor intenta nuevamente.');
    }
}
