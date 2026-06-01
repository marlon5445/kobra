<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Clientes<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Encabezado de la página -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-address-book text-primary"></i> Directorio de Clientes
        </h1>
        <p class="page-subtitle">Gestiona la base de datos de clientes, sus datos de contacto e identificación fiscal.</p>
    </div>
    <div>
        <a href="<?= base_url('clientes/crear') ?>" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md)">
            <i class="fa-solid fa-circle-plus"></i> Nuevo Cliente
        </a>
    </div>
</div>

<!-- Tarjeta Principal de Listado -->
<div class="card card-custom border-0 shadow-sm">
    <div class="card-custom-header">
        <h5 class="card-custom-title">
            <i class="fa-solid fa-users text-primary me-2"></i> Listado de Clientes
        </h5>
        <span class="badge bg-primary-light text-primary fw-bold px-3 py-2 border border-primary-subtle" style="font-size: 0.85rem">
            <?= count($clientes) ?> Clientes Activos
        </span>
    </div>
    <div class="card-custom-body">
        
        <div class="table-responsive">
            <table id="tabla-clientes" class="table table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 120px;">Identificación</th>
                        <th>Documento</th>
                        <th>Nombre / Razón Social</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Correo Electrónico</th>
                        <th>Observaciones</th>
                        <th class="text-center" style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cli): ?>
                        <tr>
                            <!-- Tipo Doc -->
                            <td class="ps-3">
                                <span class="badge bg-light text-dark border px-2 py-1 fw-semibold text-uppercase">
                                    <?= esc($cli['tipo_documento']) ?>
                                </span>
                            </td>

                            <!-- Nro Doc -->
                            <td>
                                <code class="text-dark fw-bold" style="font-size: 0.9rem;"><?= esc($cli['numero_documento']) ?></code>
                            </td>

                            <!-- Nombres -->
                            <td>
                                <div class="fw-bold text-dark mb-0"><?= esc($cli['nombres']) ?></div>
                            </td>

                            <!-- Dirección -->
                            <td>
                                <span class="text-muted small"><?= esc($cli['direccion'] ?: '-') ?></span>
                            </td>

                            <!-- Teléfono -->
                            <td>
                                <span class="fw-semibold text-secondary small"><?= esc($cli['telefono'] ?: '-') ?></span>
                            </td>

                            <!-- Correo -->
                            <td>
                                <?php if (!empty($cli['correo'])): ?>
                                    <a href="mailto:<?= esc($cli['correo']) ?>" class="text-primary text-decoration-none small">
                                        <i class="fa-regular fa-envelope me-1"></i><?= esc($cli['correo']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Observaciones -->
                            <td>
                                <span class="text-muted small text-truncate d-inline-block" style="max-width: 180px;" title="<?= esc($cli['observaciones'] ?? '') ?>">
                                    <?= esc($cli['observaciones'] ?: '-') ?>
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="<?= base_url('clientes/editar/' . $cli['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary tooltip-action" 
                                       title="Editar Cliente"
                                       style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-md)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <a href="<?= base_url('clientes/eliminar/' . $cli['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger tooltip-action" 
                                       title="Eliminar Cliente"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar al cliente &quot;<?= esc($cli['nombres']) ?>&quot;? El registro se desactivará lógicamente.');"
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
        $('#tabla-clientes').DataTable({
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
