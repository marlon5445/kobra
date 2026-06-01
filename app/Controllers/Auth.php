<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Auth extends BaseController
{
    /**
     * Muestra la pantalla de inicio de sesión.
     */
    public function login(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        // Si ya hay sesión activa, redirigir al dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/login');
    }

    /**
     * Procesa las credenciales del formulario de inicio de sesión.
     */
    public function procesar()
    {
        $usuarioInput    = $this->request->getPost('usuario');
        $contrasenaInput = $this->request->getPost('contrasena');
        $recordar        = $this->request->getPost('recordar');

        // 1. Validación básica de campos vacíos
        if (empty($usuarioInput) || empty($contrasenaInput)) {
            return redirect()->back()
                ->with('error', 'Por favor, completa todos los campos para iniciar sesión.');
        }

        // 2. Buscar usuario activo en la base de datos
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel
            ->where('usuario', $usuarioInput)
            ->orWhere('correo', $usuarioInput)
            ->where('estado', 1)
            ->first();

        // 3. Verificar que el usuario existe y la contraseña es correcta (BCRYPT)
        if (!$usuario || !password_verify($contrasenaInput, $usuario['contrasena'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Las credenciales ingresadas son incorrectas. Por favor verifica tu usuario y contraseña.');
        }

        // 4. Obtener el rol del usuario y sus permisos desde la base de datos
        $rolData = $usuarioModel->getUsuarioConRol($usuario['id']);
        $permisosUsuario = $usuarioModel->getPermisosUsuario($usuario['id']);

        $rolId     = $rolData['rol_id'] ?? null;
        $rolNombre = $rolData['rol_nombre'] ?? 'Sin Rol';

        // 5. Construir los datos de la sesión
        $sessionData = [
            'isLoggedIn'   => true,
            'userId'       => $usuario['id'],
            'nombres'      => $usuario['nombres'],
            'apellidos'    => $usuario['apellidos'],
            'usuario'      => $usuario['usuario'],
            'correo'       => $usuario['correo'],
            'rolId'        => (int)$rolId,
            'rol_nombre'   => $rolNombre,
            'permisos'     => $permisosUsuario,
            // Iniciales para el avatar del Header
            'iniciales'    => strtoupper(mb_substr($usuario['nombres'], 0, 1) . mb_substr($usuario['apellidos'], 0, 1))
        ];

        session()->set($sessionData);

        // 6. Redirigir al panel de control
        return redirect()->to(base_url('dashboard'))
            ->with('success', '¡Bienvenido, ' . esc($usuario['nombres']) . '! Has iniciado sesión correctamente.');
    }

    /**
     * Destruye la sesión y redirige al Login.
     */
    public function logout()
    {
        $nombre = session()->get('nombres');
        session()->destroy();

        return redirect()->to(base_url('login'))
            ->with('success', 'Has cerrado sesión correctamente. ¡Hasta pronto, ' . esc($nombre) . '!');
    }
}
