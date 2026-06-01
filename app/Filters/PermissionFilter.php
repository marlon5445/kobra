<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    /**
     * Intercepta la petición y valida si el rol del usuario logueado posee 
     * el permiso requerido para entrar al controlador.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Validar que la sesión esté iniciada
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        // 2. Si el rol es Administrador (ID: 1), tiene acceso total inmediato
        if ($session->get('rolId') === 1 || $session->get('rol_nombre') === 'Administrador') {
            return;
        }

        // 3. Resolver el permiso requerido
        $permisoRequerido = '';

        if (!empty($arguments) && isset($arguments[0])) {
            // Si el permiso se definió explícitamente en Routes.php (ej. 'permission:productos.crear')
            $permisoRequerido = $arguments[0];
        } else {
            // Deducción automática por segmentos de URI
            $uri = $request->getUri();
            $segments = $uri->getSegments();
            
            // Segmento 1 es el módulo (ej. 'productos')
            $modulo = $segments[0] ?? 'dashboard';
            // Segmento 2 es la acción o id. Si es número, es editar/eliminar.
            $seg2 = $segments[1] ?? 'ver';
            
            $accion = 'ver';
            if (in_array($seg2, ['crear', 'guardar', 'nueva', 'registrar'])) {
                $accion = 'crear';
            } elseif (in_array($seg2, ['editar', 'actualizar'])) {
                $accion = 'editar';
            } elseif ($seg2 === 'eliminar') {
                $accion = 'eliminar';
            } elseif ($seg2 === 'anular') {
                $accion = 'anular';
            }
            
            $permisoRequerido = "{$modulo}.{$accion}";
        }

        // 4. Obtener los permisos del usuario almacenados en la sesión al loguearse
        $permisosUsuario = $session->get('permisos') ?? [];

        // 5. Verificar si el permiso requerido se encuentra en los autorizados para el usuario
        if (!in_array($permisoRequerido, $permisosUsuario)) {
            // Responder con un estado HTTP 403 Forbidden y cargar la vista premium de acceso restringido
            $response = service('response');
            $response->setStatusCode(403);
            $response->setBody(view('errors/html/error_403'));
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere procesamiento posterior
    }
}
