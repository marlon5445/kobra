<?php

namespace App\Controllers;

use App\Models\ProveedorModel;
use CodeIgniter\HTTP\RedirectResponse;

class Proveedores extends BaseController
{
    protected ProveedorModel $proveedorModel;

    public function __construct()
    {
        $this->proveedorModel = new ProveedorModel();
    }

    /**
     * Muestra el listado de proveedores activos.
     */
     public function index(): string
     {
         // Recuperar solo los proveedores activos (eliminación lógica)
         $proveedores = $this->proveedorModel->where('estado', 1)->orderBy('id', 'DESC')->findAll();
 
         return view('proveedores/index', [
             'proveedores' => $proveedores
         ]);
     }

    /**
     * Muestra el formulario para registrar un nuevo proveedor.
     */
    public function crear(): string
    {
        return view('proveedores/crear');
    }

    /**
     * Guarda el registro de un nuevo proveedor.
     */
    public function guardar(): RedirectResponse
    {
        $data = [
            'ruc'              => $this->request->getPost('ruc'),
            'razon_social'     => $this->request->getPost('razon_social'),
            'nombre_comercial' => $this->request->getPost('nombre_comercial') ?: null,
            'direccion'        => $this->request->getPost('direccion') ?: null,
            'telefono'         => $this->request->getPost('telefono') ?: null,
            'correo'           => $this->request->getPost('correo') ?: null,
            'contacto'         => $this->request->getPost('contacto') ?: null,
            'observaciones'    => $this->request->getPost('observaciones') ?: null,
            'estado'           => 1 // Activo por defecto
        ];

        if ($this->proveedorModel->insert($data)) {
            return redirect()->to(base_url('proveedores'))->with('success', 'El proveedor se ha registrado exitosamente.');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->proveedorModel->errors());
    }

    /**
     * Muestra el formulario de edición de un proveedor.
     */
    public function editar(int $id)
    {
        $proveedor = $this->proveedorModel->find($id);

        if (!$proveedor || $proveedor['estado'] == 0) {
            return redirect()->to(base_url('proveedores'))->with('error', 'El proveedor solicitado no existe o ha sido eliminado.');
        }

        return view('proveedores/editar', [
            'proveedor' => $proveedor
        ]);
    }

    /**
     * Actualiza el registro de un proveedor.
     */
    public function actualizar(int $id): RedirectResponse
    {
        $proveedor = $this->proveedorModel->find($id);

        if (!$proveedor || $proveedor['estado'] == 0) {
            return redirect()->to(base_url('proveedores'))->with('error', 'El proveedor no existe o ha sido eliminado.');
        }

        $data = [
            'id'               => $id,
            'ruc'              => $this->request->getPost('ruc'),
            'razon_social'     => $this->request->getPost('razon_social'),
            'nombre_comercial' => $this->request->getPost('nombre_comercial') ?: null,
            'direccion'        => $this->request->getPost('direccion') ?: null,
            'telefono'         => $this->request->getPost('telefono') ?: null,
            'correo'           => $this->request->getPost('correo') ?: null,
            'contacto'         => $this->request->getPost('contacto') ?: null,
            'observaciones'    => $this->request->getPost('observaciones') ?: null,
        ];

        if ($this->proveedorModel->save($data)) {
            return redirect()->to(base_url('proveedores'))->with('success', 'La información del proveedor se ha actualizado exitosamente.');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->proveedorModel->errors());
    }

    /**
     * Ejecuta la eliminación lógica de un proveedor (estado = 0).
     */
    public function eliminar(int $id): RedirectResponse
    {
        $proveedor = $this->proveedorModel->find($id);

        if (!$proveedor || $proveedor['estado'] == 0) {
            return redirect()->to(base_url('proveedores'))->with('error', 'El proveedor seleccionado no existe.');
        }

        // Realizamos la eliminación lógica
        $this->proveedorModel->update($id, ['estado' => 0]);

        return redirect()->to(base_url('proveedores'))->with('success', 'El proveedor "' . esc($proveedor['razon_social']) . '" se ha eliminado lógicamente del sistema.');
    }
}
