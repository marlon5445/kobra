<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Encabezado del Dashboard -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-chart-line text-primary"></i> Panel de Control
        </h1>
        <p class="page-subtitle">Visualización global y analíticas del inventario Kobra POS.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('productos/crear') ?>" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md)">
            <i class="fa-solid fa-plus-circle"></i> Nuevo Producto
        </a>
    </div>
</div>

<!-- Tarjetas Métricas de Negocio -->
<div class="row g-4 mb-4">
    
    <!-- Categorías -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-custom p-4 h-100 d-flex flex-row align-items-center justify-content-between border-0 shadow-sm" style="background: linear-gradient(135deg, #FFF 0%, #F8FAFC 100%)">
            <div>
                <h3 class="text-uppercase text-muted fs-7 fw-bold tracking-wider mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Categorías</h3>
                <div class="fs-2 fw-extrabold text-dark" style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800;"><?= $metricas['total_categorias'] ?></div>
                <a href="<?= base_url('categorias') ?>" class="text-primary text-decoration-none fw-semibold fs-8" style="font-size: 0.85rem;">
                    Gestionar <i class="fa-solid fa-arrow-right-long ms-1 fs-9"></i>
                </a>
            </div>
            <div class="d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: var(--radius-md); background-color: rgba(79, 70, 229, 0.1); color: var(--primary);">
                <i class="fa-solid fa-tags fs-4"></i>
            </div>
        </div>
    </div>
    
    <!-- Productos -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-custom p-4 h-100 d-flex flex-row align-items-center justify-content-between border-0 shadow-sm" style="background: linear-gradient(135deg, #FFF 0%, #F8FAFC 100%)">
            <div>
                <h3 class="text-uppercase text-muted fs-7 fw-bold tracking-wider mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Productos</h3>
                <div class="fs-2 fw-extrabold text-dark" style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800;"><?= $metricas['total_productos'] ?></div>
                <a href="<?= base_url('productos') ?>" class="text-primary text-decoration-none fw-semibold fs-8" style="font-size: 0.85rem;">
                    Ver Inventario <i class="fa-solid fa-arrow-right-long ms-1 fs-9"></i>
                </a>
            </div>
            <div class="d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: var(--radius-md); background-color: rgba(6, 182, 212, 0.1); color: var(--info);">
                <i class="fa-solid fa-boxes-stacked fs-4"></i>
            </div>
        </div>
    </div>
    
    <!-- Stock Total -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-custom p-4 h-100 d-flex flex-row align-items-center justify-content-between border-0 shadow-sm" style="background: linear-gradient(135deg, #FFF 0%, #F8FAFC 100%)">
            <div>
                <h3 class="text-uppercase text-muted fs-7 fw-bold tracking-wider mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Stock Total</h3>
                <div class="fs-2 fw-extrabold text-dark" style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800;"><?= $metricas['total_stock'] ?></div>
                <span class="text-muted fs-8" style="font-size: 0.85rem;">Unidades en tienda</span>
            </div>
            <div class="d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: var(--radius-md); background-color: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fa-solid fa-warehouse fs-4"></i>
            </div>
        </div>
    </div>
    
    <!-- Stock Crítico -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-custom p-4 h-100 d-flex flex-row align-items-center justify-content-between border-0 shadow-sm" style="background: linear-gradient(135deg, #FFF 0%, #F8FAFC 100%)">
            <div>
                <h3 class="text-uppercase text-muted fs-7 fw-bold tracking-wider mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Stock Crítico</h3>
                <div class="fs-2 fw-extrabold <?= $metricas['stock_critico'] > 0 ? 'text-danger' : 'text-dark' ?>" style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800;">
                    <?= $metricas['stock_critico'] ?>
                </div>
                <?php if ($metricas['stock_critico'] > 0): ?>
                    <span class="text-danger fw-semibold fs-8" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-triangle-exclamation animate-pulse"></i> ¡Requiere reabastecer!
                    </span>
                <?php else: ?>
                    <span class="text-success fw-semibold fs-8" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-circle-check"></i> Inventario óptimo
                    </span>
                <?php endif; ?>
            </div>
            <div class="d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: var(--radius-md); background-color: <?= $metricas['stock_critico'] > 0 ? 'rgba(239, 68, 68, 0.1)' : 'rgba(100, 116, 139, 0.1)' ?>; color: <?= $metricas['stock_critico'] > 0 ? 'var(--danger)' : 'var(--secondary)' ?>;">
                <i class="fa-solid fa-circle-exclamation fs-4"></i>
            </div>
        </div>
    </div>

</div>

<!-- Accesos Rápidos y Resumen -->
<div class="row g-4">
    
    <!-- Enlaces directos de navegación y guía -->
    <div class="col-12 col-lg-6">
        <div class="card card-custom h-100 border-0 shadow-sm">
            <div class="card-custom-header">
                <h5 class="card-custom-title">
                    <i class="fa-solid fa-bolt text-warning me-2"></i> Accesos y Guía Rápida ERP
                </h5>
            </div>
            <div class="card-custom-body">
                <p class="text-muted mb-4">Bienvenido a la administración de **Kobra POS**. Aquí tienes un listado de acciones rápidas para dar de alta información de inventario y configurar el catálogo inicial.</p>
                
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center p-3 border rounded" style="background-color: #F8FAFC">
                        <i class="fa-solid fa-tag text-primary fs-3 me-3"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Gestionar Categorías</h6>
                            <p class="text-muted mb-0 small">Agrupa tus productos para búsquedas optimizadas y reportes estructurados.</p>
                        </div>
                        <a href="<?= base_url('categorias') ?>" class="btn btn-sm btn-outline-primary">Ver Módulo</a>
                    </div>

                    <div class="d-flex align-items-center p-3 border rounded" style="background-color: #F8FAFC">
                        <i class="fa-solid fa-box text-success fs-3 me-3"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Catalogo de Productos</h6>
                            <p class="text-muted mb-0 small">Controla compras, precios de venta, stock mínimo y carga fotos de productos.</p>
                        </div>
                        <a href="<?= base_url('productos') ?>" class="btn btn-sm btn-outline-success">Ver Módulo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Productos de Stock Crítico -->
    <div class="col-12 col-lg-6">
        <div class="card card-custom h-100 border-0 shadow-sm">
            <div class="card-custom-header">
                <h5 class="card-custom-title">
                    <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i> Productos con Stock Bajo / Mínimo
                </h5>
                <span class="badge bg-danger"><?= count($productos_criticos) ?> Alertas</span>
            </div>
            <div class="card-custom-body">
                <?php if (empty($productos_criticos)): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-shield-heart text-success fs-1 mb-3"></i>
                        <h6 class="fw-bold">¡Inventario Seguro!</h6>
                        <p class="text-muted small mb-0">Ningún producto está por debajo de su stock mínimo de seguridad.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th class="text-center">Stock Act.</th>
                                    <th class="text-center">Stock Mín.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos_criticos as $prod): ?>
                                    <tr>
                                        <td><a href="<?= base_url('productos/editar/' . $prod['id']) ?>" class="text-decoration-none"><code class="text-dark fw-bold"><?= esc($prod['codigo']) ?></code></a></td>
                                        <td class="fw-semibold"><?= esc($prod['nombre']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-light-danger text-danger fw-bold border border-danger-subtle px-2 py-1">
                                                <?= esc($prod['stock']) ?> unid.
                                            </span>
                                        </td>
                                        <td class="text-center text-muted"><?= esc($prod['stock_minimo']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .bg-light-danger {
        background-color: rgba(239, 68, 68, 0.08);
    }
    .animate-pulse {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.15); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
<?= $this->endSection() ?>
