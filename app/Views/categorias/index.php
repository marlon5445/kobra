<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Categorías<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Encabezado de la página -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-tags text-primary"></i> Categorías de Productos
        </h1>
        <p class="page-subtitle">Administra los grupos y familias de productos de tu tienda.</p>
    </div>
    <div>
        <a href="<?= base_url('categorias/crear') ?>" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md)">
            <i class="fa-solid fa-circle-plus"></i> Nueva Categoría
        </a>
    </div>
</div>

<!-- Tarjeta Principal de Listado -->
<div class="card card-custom border-0 shadow-sm">
    <div class="card-custom-header">
        <h5 class="card-custom-title">
            <i class="fa-solid fa-list-ul text-primary me-2"></i> Listado General
        </h5>
        <span class="badge bg-primary-light text-primary fw-bold px-3 py-2 border border-primary-subtle" style="font-size: 0.85rem">
            <?= count($categorias) ?> Categorías Activas
        </span>
    </div>
    <div class="card-custom-body">
        
        <!-- Tabla Dinámica DataTables -->
        <div class="table-responsive">
            <table id="tabla-categorias" class="table table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 80px;">ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha Creación</th>
                        <th class="text-center" style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td class="ps-3 fw-bold text-muted">#<?= esc($cat['id']) ?></td>
                            <td>
                                <div class="fw-semibold text-dark"><?= esc($cat['nombre']) ?></div>
                            </td>
                            <td>
                                <span class="text-secondary small">
                                    <?= !empty($cat['descripcion']) ? esc($cat['descripcion']) : '<em class="text-muted">Sin descripción asociada.</em>' ?>
                                </span>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    <i class="fa-regular fa-calendar me-1"></i>
                                    <?= date('d/m/Y H:i', strtotime($cat['fecha_creacion'])) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="<?= base_url('categorias/editar/' . $cat['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary tooltip-action" 
                                       title="Editar Categoría"
                                       style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-md)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <a href="<?= base_url('categorias/eliminar/' . $cat['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger tooltip-action" 
                                       title="Eliminar Categoría"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar la categoría &quot;<?= esc($cat['nombre']) ?>&quot;? Esta acción no afectará físicamente la base de datos (eliminación lógica), pero ya no estará disponible en el catálogo.');"
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

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        // Inicializar DataTable con idioma Español y diseño responsivo
        $('#tabla-categorias').DataTable({
            responsive: true,
            order: [[0, 'desc']], // Ordenar por ID descendente por defecto
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json'
            },
            drawCallback: function() {
                // Aplicar estilos a la paginación de Bootstrap 5 generada
                $('.pagination').addClass('pagination-sm');
            }
        });
    });
</script>
<?= $this->endSection() ?>
