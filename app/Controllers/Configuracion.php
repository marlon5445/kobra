<?php

namespace App\Controllers;

use App\Models\ConfiguracionModel;
use CodeIgniter\HTTP\RedirectResponse;

class Configuracion extends BaseController
{
    protected ConfiguracionModel $configuracionModel;

    public function __construct()
    {
        $this->configuracionModel = new ConfiguracionModel();
    }

    /**
     * Muestra el formulario de configuración de la empresa.
     */
    public function index(): string
    {
        // Recuperar el único registro de configuración (ID = 1)
        $configuracion = $this->configuracionModel->find(1);

        // Si por alguna razón no existiese, crear un registro vacío predeterminado
        if (!$configuracion) {
            $default = [
                'id'               => 1,
                'razon_social'     => 'Empresa de Prueba S.A.C.',
                'nombre_comercial' => 'Mi Negocio POS',
                'ruc'              => '20123456789',
                'direccion'        => 'Calle Principal 123',
                'telefono'         => '999999999',
                'correo'           => 'contacto@minegocio.com',
                'logo'             => null,
                'moneda'           => 'Soles',
                'simbolo_moneda'   => 'S/',
                'mensaje_ticket'   => '¡Gracias por su compra!'
            ];
            $this->configuracionModel->insert($default);
            $configuracion = $default;
        }

        return view('configuracion/index', [
            'configuracion' => $configuracion
        ]);
    }

    /**
     * Procesa la actualización de la configuración e incluye la carga del logo.
     */
    public function actualizar(): RedirectResponse
    {
        // 1. Obtener la configuración actual
        $configuracion = $this->configuracionModel->find(1);

        if (!$configuracion) {
            return redirect()->to(base_url('configuracion'))->with('error', 'No se encontró el registro de configuración.');
        }

        // 2. Recoger datos de texto del POST
        $data = [
            'id'               => 1,
            'razon_social'     => trim($this->request->getPost('razon_social') ?? ''),
            'nombre_comercial' => trim($this->request->getPost('nombre_comercial') ?? ''),
            'ruc'              => trim($this->request->getPost('ruc') ?? ''),
            'direccion'        => trim($this->request->getPost('direccion') ?? ''),
            'telefono'         => trim($this->request->getPost('telefono') ?? ''),
            'correo'           => trim($this->request->getPost('correo') ?? ''),
            'moneda'           => trim($this->request->getPost('moneda') ?? 'Soles'),
            'simbolo_moneda'   => trim($this->request->getPost('simbolo_moneda') ?? 'S/'),
            'mensaje_ticket'   => trim($this->request->getPost('mensaje_ticket') ?? '')
        ];

        // 3. Procesar carga de archivo (logo)
        $logoFile = $this->request->getFile('logo');

        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            // Validar la carga de la imagen
            $rules = [
                'logo' => [
                    'label' => 'Logo de Empresa',
                    'rules' => 'is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png,image/webp]|max_size[logo,2048]',
                    'errors' => [
                        'is_image' => 'El logo seleccionado debe ser una imagen válida.',
                        'mime_in'  => 'El formato del logo debe ser JPG, JPEG, PNG o WEBP.',
                        'max_size' => 'El tamaño máximo permitido para el logo es de 2 MB.'
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $this->validator->getErrors());
            }

            // Crear directorio si no existe
            $uploadPath = ROOTPATH . 'public/uploads/logo';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Generar un nombre aleatorio seguro
            $newName = $logoFile->getRandomName();
            
            if ($logoFile->move($uploadPath, $newName)) {
                // Eliminar el logo anterior si existe en el disco
                if (!empty($configuracion['logo'])) {
                    $oldLogoPath = $uploadPath . '/' . $configuracion['logo'];
                    if (file_exists($oldLogoPath)) {
                        @unlink($oldLogoPath);
                    }
                }

                // Asignar el nuevo nombre de logo
                $data['logo'] = $newName;
            }
        } else {
            // Mantener el logo existente si no se sube uno nuevo
            $data['logo'] = $configuracion['logo'];
        }

        // 4. Intentar guardar en base de datos
        if ($this->configuracionModel->save($data)) {
            return redirect()->to(base_url('configuracion'))
                ->with('success', 'La configuración de la empresa se ha actualizado correctamente.');
        }

        // Si fallan las validaciones del modelo
        return redirect()->back()
            ->withInput()
            ->with('errors', $this->configuracionModel->errors());
    }
}
