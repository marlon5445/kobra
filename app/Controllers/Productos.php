<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use CodeIgniter\HTTP\RedirectResponse;

class Productos extends BaseController
{
    protected ProductoModel $productoModel;
    protected CategoriaModel $categoriaModel;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
        $this->categoriaModel = new CategoriaModel();
    }

    /**
     * Muestra el listado de productos activos.
     */
    public function index(): string
    {
        // Obtener productos con JOIN a Categorías
        $productos = $this->productoModel->getProductosConCategoria();

        return view('productos/index', [
            'productos' => $productos
        ]);
    }

    /**
     * Formulario de creación de productos.
     */
    public function crear(): string
    {
        // Solo cargar categorías activas para el selector
        $categorias = $this->categoriaModel->where('estado', 1)->orderBy('nombre', 'ASC')->findAll();

        return view('productos/crear', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Guarda el producto en la base de datos con validaciones e imágenes.
     */
    public function guardar(): RedirectResponse
    {
        // 1. Obtener datos básicos
        $data = [
            'categoria_id'  => $this->request->getPost('categoria_id'),
            'codigo'        => $this->request->getPost('codigo'),
            'nombre'        => $this->request->getPost('nombre'),
            'descripcion'   => $this->request->getPost('descripcion'),
            'precio_compra' => $this->request->getPost('precio_compra'),
            'precio_venta'  => $this->request->getPost('precio_venta'),
            'stock'         => $this->request->getPost('stock'),
            'stock_minimo'  => $this->request->getPost('stock_minimo'),
            'estado'        => 1
        ];

        // 2. Gestionar la subida de imagen
        $imagenFile = $this->request->getFile('imagen');
        
        if ($imagenFile && $imagenFile->isValid() && !$imagenFile->hasMoved()) {
            // Validar formato y tamaño de la imagen
            $rules = [
                'imagen' => 'uploaded[imagen]|max_size[imagen,2048]|is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png,image/webp]'
            ];
            
            if ($this->validate($rules)) {
                // Generar nombre aleatorio seguro
                $newName = $imagenFile->getRandomName();
                // Mover a public/uploads/productos/
                if ($imagenFile->move(FCPATH . 'uploads/productos/', $newName)) {
                    $data['imagen'] = $newName;
                }
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', ['imagen' => 'El archivo de imagen no es válido. Asegúrate de subir un formato JPG, PNG, WEBP de máximo 2MB.']);
            }
        }

        // 3. Intentar guardar en el modelo (activa validaciones del Modelo)
        if ($this->productoModel->insert($data)) {
            return redirect()->to(base_url('productos'))->with('success', 'El producto se ha guardado correctamente.');
        }

        // Si falla la inserción por validaciones del modelo, eliminar la imagen subida si existe
        if (isset($data['imagen']) && file_exists(FCPATH . 'uploads/productos/' . $data['imagen'])) {
            unlink(FCPATH . 'uploads/productos/' . $data['imagen']);
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->productoModel->errors());
    }

    /**
     * Muestra el formulario de edición de productos.
     */
    public function editar(int $id)
    {
        $producto = $this->productoModel->find($id);

        if (!$producto || $producto['estado'] == 0) {
            return redirect()->to(base_url('productos'))->with('error', 'El producto solicitado no existe o ha sido desactivado.');
        }

        // Categorías para el selector
        $categorias = $this->categoriaModel->where('estado', 1)->orderBy('nombre', 'ASC')->findAll();

        return view('productos/editar', [
            'producto'   => $producto,
            'categorias' => $categorias
        ]);
    }

    /**
     * Actualiza el producto gestionando el reemplazo de imágenes.
     */
    public function actualizar(int $id): RedirectResponse
    {
        $producto = $this->productoModel->find($id);

        if (!$producto || $producto['estado'] == 0) {
            return redirect()->to(base_url('productos'))->with('error', 'El producto no existe.');
        }

        $data = [
            'id'            => $id,
            'categoria_id'  => $this->request->getPost('categoria_id'),
            'codigo'        => $this->request->getPost('codigo'),
            'nombre'        => $this->request->getPost('nombre'),
            'descripcion'   => $this->request->getPost('descripcion'),
            'precio_compra' => $this->request->getPost('precio_compra'),
            'precio_venta'  => $this->request->getPost('precio_venta'),
            'stock'         => $this->request->getPost('stock'),
            'stock_minimo'  => $this->request->getPost('stock_minimo')
        ];

        // Gestionar la subida de una nueva imagen
        $imagenFile = $this->request->getFile('imagen');
        $oldImagen = $producto['imagen'];

        if ($imagenFile && $imagenFile->isValid() && !$imagenFile->hasMoved()) {
            $rules = [
                'imagen' => 'uploaded[imagen]|max_size[imagen,2048]|is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png,image/webp]'
            ];
            
            if ($this->validate($rules)) {
                $newName = $imagenFile->getRandomName();
                if ($imagenFile->move(FCPATH . 'uploads/productos/', $newName)) {
                    $data['imagen'] = $newName;
                    
                    // Eliminar la imagen anterior del disco si existía para optimizar espacio
                    if (!empty($oldImagen) && file_exists(FCPATH . 'uploads/productos/' . $oldImagen)) {
                        unlink(FCPATH . 'uploads/productos/' . $oldImagen);
                    }
                }
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', ['imagen' => 'La nueva imagen no es válida. Debe ser formato JPG, PNG, WEBP menor a 2MB.']);
            }
        }

        // Intentar actualizar usando las validaciones del modelo
        if ($this->productoModel->save($data)) {
            return redirect()->to(base_url('productos'))->with('success', 'El producto se ha actualizado correctamente.');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->productoModel->errors());
    }

    /**
     * Eliminación lógica o física de productos (aquí realizamos lógica estado = 0).
     */
    public function eliminar(int $id): RedirectResponse
    {
        $producto = $this->productoModel->find($id);

        if (!$producto) {
            return redirect()->to(base_url('productos'))->with('error', 'El producto solicitado no existe.');
        }

        // Eliminación lógica
        $this->productoModel->update($id, ['estado' => 0]);

        return redirect()->to(base_url('productos'))->with('success', 'El producto "' . esc($producto['nombre']) . '" ha sido deshabilitado del inventario.');
    }
}
