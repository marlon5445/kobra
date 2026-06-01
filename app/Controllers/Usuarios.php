<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\RolModel;
use CodeIgniter\HTTP\RedirectResponse;

class Usuarios extends BaseController
{
    protected UsuarioModel $usuarioModel;
    protected RolModel $rolModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolModel     = new RolModel();
    }

    public function index(): string
    {
        $usuarios = $this->usuarioModel->getUsuarioConRol();
        return view('usuarios/index', ['usuarios' => $usuarios]);
    }

    public function crear(): string
    {
        $roles = $this->rolModel->where('estado', 1)->orderBy('nombre', 'ASC')->findAll();
        return view('usuarios/crear', ['roles' => $roles]);
    }

    public function guardar(): RedirectResponse
    {
        $contrasena = $this->request->getPost('contrasena');
        $confirmar  = $this->request->getPost('confirmar_contrasena');
        $rolId      = (int)$this->request->getPost('rol_id');

        if ($contrasena !== $confirmar) {
            return redirect()->back()->withInput()
                ->with('errors', ['contrasena' => 'Las contraseñas no coinciden. Por favor verifica e intenta nuevamente.']);
        }

        $data = [
            'nombres'    => $this->request->getPost('nombres'),
            'apellidos'  => $this->request->getPost('apellidos'),
            'usuario'    => $this->request->getPost('usuario'),
            'correo'     => $this->request->getPost('correo'),
            'contrasena' => password_hash($contrasena, PASSWORD_BCRYPT),
            'estado'     => 1
        ];

        if ($this->usuarioModel->guardarUsuarioConRol($data, $rolId)) {
            return redirect()->to(base_url('usuarios'))->with('success', 'El usuario se ha creado correctamente en el sistema.');
        }

        return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
    }

    public function ver(int $id): string
    {
        $usuario = $this->usuarioModel->getUsuarioConRol($id);
        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))->with('error', 'El usuario no existe.');
        }

        return view('usuarios/ver', ['usuario' => $usuario]);
    }

    public function editar(int $id)
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario || $usuario['estado'] == 0) {
            return redirect()->to(base_url('usuarios'))->with('error', 'El usuario no existe o ha sido desactivado.');
        }

        $rolActual = $this->usuarioModel->getUsuarioConRol($id);
        $roles     = $this->rolModel->where('estado', 1)->orderBy('nombre', 'ASC')->findAll();

        return view('usuarios/editar', [
            'usuario'   => $usuario,
            'rolActual' => $rolActual['rol_id'] ?? null,
            'roles'     => $roles
        ]);
    }

    public function actualizar(int $id): RedirectResponse
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))->with('error', 'El usuario no existe.');
        }

        $rolId = (int)$this->request->getPost('rol_id');
        $data  = [
            'id'         => $id,
            'nombres'    => $this->request->getPost('nombres'),
            'apellidos'  => $this->request->getPost('apellidos'),
            'usuario'    => $this->request->getPost('usuario'),
            'correo'     => $this->request->getPost('correo'),
        ];

        // Actualizar contraseña solo si se proporcionó una nueva
        $nuevaContrasena = $this->request->getPost('contrasena');
        $confirmar       = $this->request->getPost('confirmar_contrasena');

        if (!empty($nuevaContrasena)) {
            if ($nuevaContrasena !== $confirmar) {
                return redirect()->back()->withInput()
                    ->with('errors', ['contrasena' => 'Las contraseñas no coinciden.']);
            }
            $data['contrasena'] = password_hash($nuevaContrasena, PASSWORD_BCRYPT);
        }

        if ($this->usuarioModel->guardarUsuarioConRol($data, $rolId, $id)) {
            return redirect()->to(base_url('usuarios'))->with('success', 'El usuario se ha actualizado correctamente.');
        }

        return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
    }

    public function eliminar(int $id): RedirectResponse
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))->with('error', 'El usuario no existe.');
        }

        if ($id === 1) {
            return redirect()->to(base_url('usuarios'))->with('error', 'El usuario Administrador principal no puede ser eliminado del sistema.');
        }

        $this->usuarioModel->update($id, ['estado' => 0]);
        return redirect()->to(base_url('usuarios'))->with('success', 'El usuario "' . esc($usuario['usuario']) . '" ha sido desactivado.');
    }

    public function cambiarEstado(int $id, int $estado): RedirectResponse
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))->with('error', 'El usuario no existe.');
        }

        if ($id === 1 && $estado === 0) {
            return redirect()->to(base_url('usuarios'))->with('error', 'El usuario Administrador principal no puede ser desactivado.');
        }

        $this->usuarioModel->update($id, ['estado' => $estado]);
        $mensaje = $estado === 1 ? 'activado' : 'desactivado';
        return redirect()->to(base_url('usuarios'))->with('success', 'El usuario "' . esc($usuario['usuario']) . '" ha sido ' . $mensaje . '.');
    }

    public function resetPassword(int $id): RedirectResponse
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))->with('error', 'El usuario no existe.');
        }

        $nuevaContrasena = $this->request->getPost('nueva_contrasena');
        $confirmar       = $this->request->getPost('confirmar_nueva_contrasena');

        if (empty($nuevaContrasena) || strlen($nuevaContrasena) < 6) {
            return redirect()->back()->with('error', 'La nueva contraseña debe tener al menos 6 caracteres.');
        }

        if ($nuevaContrasena !== $confirmar) {
            return redirect()->back()->with('error', 'Las contraseñas nuevas no coinciden.');
        }

        $this->usuarioModel->update($id, [
            'contrasena' => password_hash($nuevaContrasena, PASSWORD_BCRYPT)
        ]);

        return redirect()->to(base_url('usuarios'))->with('success', 'La contraseña del usuario "' . esc($usuario['usuario']) . '" se ha restablecido correctamente.');
    }
}
