<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Productos<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Encabezado de la página -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-boxes-stacked text-primary"></i> Catálogo de Productos
        </h1>
        <p class="page-subtitle">Gestiona el inventario, códigos de barra, stock de seguridad y precios de venta.</p>
    </div>
    <div>
        <a href="<?= base_url('productos/crear') ?>" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md)">
            <i class="fa-solid fa-circle-plus"></i> Nuevo Producto
        </a>
    </div>
</div>

<!-- Tarjeta Principal de Listado -->
<div class="card card-custom border-0 shadow-sm">
    <div class="card-custom-header">
        <h5 class="card-custom-title">
            <i class="fa-solid fa-warehouse text-primary me-2"></i> Inventario de Productos
        </h5>
        <span class="badge bg-primary-light text-primary fw-bold px-3 py-2 border border-primary-subtle" style="font-size: 0.85rem">
            <?= count($productos) ?> Productos Registrados
        </span>
    </div>
    <div class="card-custom-body">
        
        <div class="table-responsive">
            <table id="tabla-productos" class="table table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 80px;">Imagen</th>
                        <th>Código / SKU</th>
                        <th>Nombre del Producto</th>
                        <th>Categoría</th>
                        <th class="text-end">P. Compra</th>
                        <th class="text-end">P. Venta</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Min.</th>
                        <th class="text-center" style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $prod): ?>
                        <?php 
                            // Determinar si hay alerta de stock crítico
                            $esCritico = $prod['stock'] <= $prod['stock_minimo'];
                        ?>
                        <tr>
                            <!-- Imagen Miniatura -->
                            <td class="ps-3">
                                <?php if (!empty($prod['imagen']) && file_exists(FCPATH . 'uploads/productos/' . $prod['imagen'])): ?>
                                    <img src="<?= base_url('uploads/productos/' . $prod['imagen']) ?>" 
                                         alt="<?= esc($prod['nombre']) ?>" 
                                         class="img-thumbnail" 
                                         style="width: 44px; height: 44px; object-fit: cover; border-radius: var(--radius-md)">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center bg-light border text-muted" 
                                         style="width: 44px; height: 44px; border-radius: var(--radius-md)" 
                                         title="Sin imagen">
                                        <i class="fa-solid fa-image fs-6 text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Código -->
                            <td>
                                <code class="text-dark fw-bold" style="font-size: 0.9rem;"><?= esc($prod['codigo']) ?></code>
                            </td>

                            <!-- Nombre -->
                            <td>
                                <div class="fw-bold text-dark mb-0"><?= esc($prod['nombre']) ?></div>
                                <?php if (!empty($prod['descripcion'])): ?>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;"><?= esc($prod['descripcion']) ?></small>
                                <?php endif; ?>
                            </td>

                            <!-- Categoría -->
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1fw-semibold">
                                    <?= esc($prod['categoria_nombre']) ?>
                                </span>
                            </td>

                            <!-- Precios -->
                            <td class="text-end fw-semibold text-secondary">$<?= number_format($prod['precio_compra'], 2) ?></td>
                            <td class="text-end fw-bold text-dark">$<?= number_format($prod['precio_venta'], 2) ?></td>

                            <!-- Stock actual -->
                            <td class="text-center">
                                <?php if ($esCritico): ?>
                                    <span class="badge bg-danger-light text-danger fw-bold border border-danger-subtle px-3 py-2 tooltip-action" 
                                          title="¡Stock Crítico! Por debajo del mínimo recomendado." 
                                          style="font-size: 0.9rem;">
                                        <i class="fa-solid fa-triangle-exclamation me-1 animate-pulse"></i>
                                        <?= esc($prod['stock']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success-light text-success fw-bold border border-success-subtle px-3 py-2" 
                                          style="font-size: 0.9rem;">
                                        <?= esc($prod['stock']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Stock Mínimo -->
                            <td class="text-center text-muted fw-semibold"><?= esc($prod['stock_minimo']) ?></td>

                            <!-- Acciones -->
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="<?= base_url('productos/editar/' . $prod['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary tooltip-action" 
                                       title="Editar Producto"
                                       style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-md)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <a href="<?= base_url('productos/eliminar/' . $prod['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger tooltip-action" 
                                       title="Deshabilitar Producto"
                                       onclick="return confirm('¿Estás seguro de que deseas deshabilitar el producto &quot;<?= esc($prod['nombre']) ?>&quot;? El registro se mantendrá oculto pero almacenado en el inventario.');"
                                       style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-md)">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .bg-danger-light {
        background-color: rgba(239, 68, 68, 0.08);
    }
    .bg-success-light {
        background-color: rgba(16, 185, 129, 0.08);
    }
    .animate-pulse {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        $('#tabla-productos').DataTable({
            responsive: true,
            order: [[2, 'asc']], // Ordenar alfabéticamente por nombre
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json'
            },
            drawCallback: function() {
                $('.pagination').addClass('pagination-sm');
            }
        });
    });
</script>
<?= $this->endSection() ?>
