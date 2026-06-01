<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Proveedores<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Encabezado de la página -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-handshake text-primary"></i> Directorio de Proveedores
        </h1>
        <p class="page-subtitle">Gestiona la base de datos de proveedores, información comercial y contactos.</p>
    </div>
    <div>
        <a href="<?= base_url('proveedores/crear') ?>" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md)">
            <i class="fa-solid fa-circle-plus"></i> Nuevo Proveedor
        </a>
    </div>
</div>

<!-- Tarjeta Principal de Listado -->
<div class="card card-custom border-0 shadow-sm">
    <div class="card-custom-header">
        <h5 class="card-custom-title">
            <i class="fa-solid fa-truck text-primary me-2"></i> Listado de Proveedores
        </h5>
        <span class="badge bg-primary-light text-primary fw-bold px-3 py-2 border border-primary-subtle" style="font-size: 0.85rem">
            <?= count($proveedores) ?> Proveedores Activos
        </span>
    </div>
    <div class="card-custom-body">
        
        <div class="table-responsive">
            <table id="tabla-proveedores" class="table table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 140px;">RUC</th>
                        <th>Razón Social</th>
                        <th>Nombre Comercial</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Correo Electrónico</th>
                        <th>Dirección</th>
                        <th class="text-center" style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proveedores as $prov): ?>
                        <tr>
                            <!-- RUC -->
                            <td class="ps-3">
                                <code class="text-dark fw-bold" style="font-size: 0.9rem;"><?= esc($prov['ruc']) ?></code>
                            </td>

                            <!-- Razón Social -->
                            <td>
                                <div class="fw-bold text-dark mb-0"><?= esc($prov['razon_social']) ?></div>
                                <?php if (!empty($prov['observaciones'])): ?>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;" title="<?= esc($prov['observaciones']) ?>"><?= esc($prov['observaciones']) ?></small>
                                <?php endif; ?>
                            </td>

                            <!-- Nombre Comercial -->
                            <td>
                                <span class="text-dark small fw-semibold"><?= esc($prov['nombre_comercial'] ?: '-') ?></span>
                            </td>

                            <!-- Contacto -->
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="fa-regular fa-user text-muted small"></i>
                                    <span class="text-muted small"><?= esc($prov['contacto'] ?: '-') ?></span>
                                </div>
                            </td>

                            <!-- Teléfono -->
                            <td>
                                <span class="fw-semibold text-secondary small"><?= esc($prov['telefono'] ?: '-') ?></span>
                            </td>

                            <!-- Correo -->
                            <td>
                                <?php if (!empty($prov['correo'])): ?>
                                    <a href="mailto:<?= esc($prov['correo']) ?>" class="text-primary text-decoration-none small">
                                        <i class="fa-regular fa-envelope me-1"></i><?= esc($prov['correo']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Dirección -->
                            <td>
                                <span class="text-muted small"><?= esc($prov['direccion'] ?: '-') ?></span>
                            </td>

                            <!-- Acciones -->
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="<?= base_url('proveedores/editar/' . $prov['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary tooltip-action" 
                                       title="Editar Proveedor"
                                       style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-md)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <a href="<?= base_url('proveedores/eliminar/' . $prov['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger tooltip-action" 
                                       title="Eliminar Proveedor"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar al proveedor &quot;<?= esc($prov['razon_social']) ?>&quot;? El registro se desactivará lógicamente.');"
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
        $('#tabla-proveedores').DataTable({
            responsive: true,
            order: [[1, 'asc']], // Ordenar alfabéticamente por razón social
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
