<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use CodeIgniter\HTTP\RedirectResponse;

class Clientes extends BaseController
{
    protected ClienteModel $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
    }

    /**
     * Muestra el listado de clientes activos.
     */
     public function index(): string
     {
         // Recuperar solo los clientes activos (eliminación lógica)
         $clientes = $this->clienteModel->where('estado', 1)->orderBy('id', 'DESC')->findAll();
 
         return view('clientes/index', [
             'clientes' => $clientes
         ]);
     }

    /**
     * Muestra el formulario para registrar un nuevo cliente.
     */
    public function crear(): string
    {
        return view('clientes/crear');
    }

    /**
     * Guarda el registro de un nuevo cliente.
     */
    public function guardar(): RedirectResponse
    {
        $data = [
            'tipo_documento'   => $this->request->getPost('tipo_documento'),
            'numero_documento' => $this->request->getPost('numero_documento'),
            'nombres'          => $this->request->getPost('nombres'),
            'direccion'        => $this->request->getPost('direccion') ?: null,
            'telefono'         => $this->request->getPost('telefono') ?: null,
            'correo'           => $this->request->getPost('correo') ?: null,
            'observaciones'    => $this->request->getPost('observaciones') ?: null,
            'estado'           => 1 // Activo por defecto
        ];

        if ($this->clienteModel->insert($data)) {
            return redirect()->to(base_url('clientes'))->with('success', 'El cliente se ha registrado exitosamente.');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->clienteModel->errors());
    }

    /**
     * Muestra el formulario de edición de un cliente.
     */
    public function editar(int $id)
    {
        $cliente = $this->clienteModel->find($id);

        if (!$cliente || $cliente['estado'] == 0) {
            return redirect()->to(base_url('clientes'))->with('error', 'El cliente solicitado no existe o ha sido eliminado.');
        }

        return view('clientes/editar', [
            'cliente' => $cliente
        ]);
    }

    /**
     * Actualiza el registro de un cliente.
     */
    public function actualizar(int $id): RedirectResponse
    {
        $cliente = $this->clienteModel->find($id);

        if (!$cliente || $cliente['estado'] == 0) {
            return redirect()->to(base_url('clientes'))->with('error', 'El cliente no existe o ha sido eliminado.');
        }

        $data = [
            'id'               => $id,
            'tipo_documento'   => $this->request->getPost('tipo_documento'),
            'numero_documento' => $this->request->getPost('numero_documento'),
            'nombres'          => $this->request->getPost('nombres'),
            'direccion'        => $this->request->getPost('direccion') ?: null,
            'telefono'         => $this->request->getPost('telefono') ?: null,
            'correo'           => $this->request->getPost('correo') ?: null,
            'observaciones'    => $this->request->getPost('observaciones') ?: null,
        ];

        if ($this->clienteModel->save($data)) {
            return redirect()->to(base_url('clientes'))->with('success', 'La información del cliente se ha actualizado exitosamente.');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->clienteModel->errors());
    }

    /**
     * Ejecuta la eliminación lógica de un cliente (estado = 0).
     */
    public function eliminar(int $id): RedirectResponse
    {
        $cliente = $this->clienteModel->find($id);

        if (!$cliente || $cliente['estado'] == 0) {
            return redirect()->to(base_url('clientes'))->with('error', 'El cliente seleccionado no existe.');
        }

        // Realizamos la eliminación lógica
        $this->clienteModel->update($id, ['estado' => 0]);

        return redirect()->to(base_url('clientes'))->with('success', 'El cliente "' . esc($cliente['nombres']) . '" se ha eliminado lógicamente del sistema.');
    }
}
