<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\ProductoModel;

class Home extends BaseController
{
    public function index(): string
    {
        $metricas = [
            'total_categorias' => 0,
            'total_productos'  => 0,
            'total_stock'      => 0,
            'stock_critico'    => 0,
        ];
        
        $productos_criticos = [];

        try {
            // Instanciar modelos si la base de datos y tablas existen
            $categoriaModel = new CategoriaModel();
            $productoModel = new ProductoModel();

            // Total de categorías activas (eliminación lógica estado = 1)
            $metricas['total_categorias'] = $categoriaModel->where('estado', 1)->countAllResults();

            // Total de productos activos
            $metricas['total_productos'] = $productoModel->where('estado', 1)->countAllResults();

            // Total de stock acumulado
            $stockResult = $productoModel->selectSum('stock')->where('estado', 1)->first();
            $metricas['total_stock'] = (int)($stockResult['stock'] ?? 0);

            // Total de productos con stock crítico (stock <= stock_minimo)
            $metricas['stock_critico'] = $productoModel
                ->where('estado', 1)
                ->where('stock <= stock_minimo')
                ->countAllResults();

            // Obtener los productos críticos para mostrarlos en el resumen
            $productos_criticos = $productoModel
                ->where('estado', 1)
                ->where('stock <= stock_minimo')
                ->orderBy('stock', 'ASC')
                ->findAll(5); // Limitar a los 5 más urgentes

        } catch (\Throwable $e) {
            // Atrapamos la excepción en caso de que la tabla aún no exista
            // evitando que el sistema falle antes de ejecutar la importación SQL manual.
        }

        return view('dashboard', [
            'metricas'           => $metricas,
            'productos_criticos' => $productos_criticos,
        ]);
    }
}
