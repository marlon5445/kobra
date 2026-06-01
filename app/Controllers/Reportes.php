<?php

namespace App\Controllers;

use App\Models\VentaModel;
use App\Models\CompraModel;
use App\Models\ClienteModel;
use App\Models\ProveedorModel;
use App\Models\UsuarioModel;

class Reportes extends BaseController
{
    protected VentaModel     $ventaModel;
    protected CompraModel    $compraModel;
    protected ClienteModel   $clienteModel;
    protected ProveedorModel $proveedorModel;
    protected UsuarioModel   $usuarioModel;

    public function __construct()
    {
        $this->ventaModel     = new VentaModel();
        $this->compraModel    = new CompraModel();
        $this->clienteModel   = new ClienteModel();
        $this->proveedorModel = new ProveedorModel();
        $this->usuarioModel   = new UsuarioModel();
    }

    /**
     * GET /reportes/ventas
     * Reporte de Ventas con filtros avanzados
     */
    public function ventas()
    {
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin    = $this->request->getGet('fecha_fin') ?? date('Y-m-d');
        $clienteId   = $this->request->getGet('cliente_id');
        $usuarioId   = $this->request->getGet('usuario_id');
        $estado      = $this->request->getGet('estado');

        $builder = $this->ventaModel->db->table('ventas')
            ->select('ventas.*, clientes.nombres as cliente_nombre, CONCAT(usuarios.nombres, " ", usuarios.apellidos) as usuario_nombre')
            ->join('clientes', 'clientes.id = ventas.cliente_id', 'left')
            ->join('usuarios', 'usuarios.id = ventas.usuario_id', 'left');

        if (!empty($fechaInicio)) {
            $builder->where('ventas.fecha >=', $fechaInicio . ' 00:00:00');
        }
        if (!empty($fechaFin)) {
            $builder->where('ventas.fecha <=', $fechaFin . ' 23:59:59');
        }
        if (!empty($clienteId)) {
            $builder->where('ventas.cliente_id', $clienteId);
        }
        if (!empty($usuarioId)) {
            $builder->where('ventas.usuario_id', $usuarioId);
        }
        if ($estado !== null && $estado !== '') {
            $builder->where('ventas.estado', $estado);
        }

        $ventas = $builder->orderBy('ventas.fecha', 'DESC')->get()->getResultArray();

        // Calcular Totales
        $cantidadVentas = count($ventas);
        $totalVendido   = array_sum(array_column(array_filter($ventas, fn($v) => $v['estado'] == 1), 'total'));

        // Listas para filtros desplegables
        $clientes = $this->clienteModel->where('estado', 1)->orderBy('nombres', 'ASC')->findAll();
        $vendedores = $this->usuarioModel->where('estado', 1)->orderBy('nombres', 'ASC')->findAll();

        return view('reportes/ventas', [
            'ventas'         => $ventas,
            'cantidadVentas' => $cantidadVentas,
            'totalVendido'   => $totalVendido,
            'clientes'       => $clientes,
            'vendedores'     => $vendedores,
            'fecha_inicio'   => $fechaInicio,
            'fecha_fin'      => $fechaFin,
            'cliente_id'     => $clienteId,
            'usuario_id'     => $usuarioId,
            'estado'         => $estado,
        ]);
    }

    /**
     * GET /reportes/compras
     * Reporte de Compras con filtros avanzados
     */
    public function compras()
    {
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin    = $this->request->getGet('fecha_fin') ?? date('Y-m-d');
        $proveedorId = $this->request->getGet('proveedor_id');
        $estado      = $this->request->getGet('estado');

        $builder = $this->compraModel->db->table('compras')
            ->select('compras.*, proveedores.razon_social as proveedor_nombre, CONCAT(usuarios.nombres, " ", usuarios.apellidos) as usuario_nombre')
            ->join('proveedores', 'proveedores.id = compras.proveedor_id', 'left')
            ->join('usuarios', 'usuarios.id = compras.usuario_id', 'left');

        if (!empty($fechaInicio)) {
            $builder->where('compras.fecha >=', $fechaInicio . ' 00:00:00');
        }
        if (!empty($fechaFin)) {
            $builder->where('compras.fecha <=', $fechaFin . ' 23:59:59');
        }
        if (!empty($proveedorId)) {
            $builder->where('compras.proveedor_id', $proveedorId);
        }
        if ($estado !== null && $estado !== '') {
            $builder->where('compras.estado', $estado);
        }

        $compras = $builder->orderBy('compras.fecha', 'DESC')->get()->getResultArray();

        // Calcular Totales
        $cantidadCompras = count($compras);
        $totalComprado   = array_sum(array_column(array_filter($compras, fn($c) => $c['estado'] == 1), 'total'));

        // Listas para filtros desplegables
        $proveedores = $this->proveedorModel->where('estado', 1)->orderBy('razon_social', 'ASC')->findAll();

        return view('reportes/compras', [
            'compras'         => $compras,
            'cantidadCompras' => $cantidadCompras,
            'totalComprado'   => $totalComprado,
            'proveedores'     => $proveedores,
            'fecha_inicio'    => $fechaInicio,
            'fecha_fin'       => $fechaFin,
            'proveedor_id'    => $proveedorId,
            'estado'          => $estado,
        ]);
    }
}
