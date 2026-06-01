<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\HTTP\RedirectResponse;

class Categorias extends BaseController
{
    protected CategoriaModel $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    /**
     * Muestra el listado de categorías activas.
     */
    public function index(): string
    {
        // Recuperar solo las categorías activas (eliminación lógica)
        $categorias = $this->categoriaModel->where('estado', 1)->orderBy('id', 'DESC')->findAll();

        return view('categorias/index', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function crear(): string
    {
        return view('categorias/crear');
    }

    /**
     * Guarda el registro de una nueva categoría.
     */
    public function guardar(): RedirectResponse
    {
        $data = [
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'estado'      => 1 // Activo por defecto al crearse
        ];

        // Intentar insertar
        if ($this->categoriaModel->insert($data)) {
            return redirect()->to(base_url('categorias'))->with('success', 'La categoría se ha creado exitosamente.');
        }

        // Si falla la validación del modelo
        return redirect()->back()
            ->withInput()
            ->with('errors', $this->categoriaModel->errors());
    }

    /**
     * Muestra el formulario de edición de una categoría.
     */
    public function editar(int $id)
    {
        $categoria = $this->categoriaModel->find($id);

        if (!$categoria || $categoria['estado'] == 0) {
            return redirect()->to(base_url('categorias'))->with('error', 'La categoría solicitada no existe o ha sido eliminada.');
        }

        return view('categorias/editar', [
            'categoria' => $categoria
        ]);
    }

    /**
     * Actualiza el registro de la categoría.
     */
    public function actualizar(int $id): RedirectResponse
    {
        $categoria = $this->categoriaModel->find($id);

        if (!$categoria || $categoria['estado'] == 0) {
            return redirect()->to(base_url('categorias'))->with('error', 'La categoría no existe o ha sido eliminada.');
        }

        $data = [
            'id'          => $id,
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion')
        ];

        // Intentar actualizar usando la validación del modelo
        if ($this->categoriaModel->save($data)) {
            return redirect()->to(base_url('categorias'))->with('success', 'La categoría se ha actualizado exitosamente.');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->categoriaModel->errors());
    }

    /**
     * Ejecuta una eliminación lógica del registro (estado = 0).
     */
    public function eliminar(int $id): RedirectResponse
    {
        $categoria = $this->categoriaModel->find($id);

        if (!$categoria) {
            return redirect()->to(base_url('categorias'))->with('error', 'La categoría seleccionada no existe.');
        }

        // Realizamos la eliminación lógica
        $this->categoriaModel->update($id, ['estado' => 0]);

        return redirect()->to(base_url('categorias'))->with('success', 'La categoría "' . esc($categoria['nombre']) . '" se ha eliminado lógicamente del sistema.');
    }
}
