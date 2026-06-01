<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Usuarios<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-users text-primary"></i> Usuarios del Sistema</h1>
        <p class="page-subtitle">Administra los operadores y sus roles de acceso al POS.</p>
    </div>
    <a href="<?= base_url('usuarios/crear') ?>" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius:var(--radius-md)">
        <i class="fa-solid fa-circle-plus"></i> Nuevo Usuario
    </a>
</div>

<div class="card card-custom border-0 shadow-sm">
    <div class="card-custom-header">
        <h5 class="card-custom-title"><i class="fa-solid fa-list-ul text-primary me-2"></i> Listado de Usuarios</h5>
        <span class="badge bg-primary-light text-primary fw-bold px-3 py-2 border border-primary-subtle"><?= count($usuarios) ?> usuarios</span>
    </div>
    <div class="card-custom-body">
        <div class="table-responsive">
            <table id="tabla-usuarios" class="table table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width:60px;">ID</th>
                        <th>Usuario</th>
                        <th>Nombre Completo</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center" style="width:180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td class="ps-3 fw-bold text-muted">#<?= $user['id'] ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center fw-bold text-white rounded-circle" style="width:36px;height:36px;background:var(--primary);font-size:0.8rem;flex-shrink:0;">
                                    <?= strtoupper(mb_substr($user['nombres'], 0, 1) . mb_substr($user['apellidos'], 0, 1)) ?>
                                </div>
                                <span class="fw-semibold"><?= esc($user['usuario']) ?></span>
                            </div>
                        </td>
                        <td><?= esc($user['nombres'] . ' ' . $user['apellidos']) ?></td>
                        <td><span class="text-muted small"><?= esc($user['correo']) ?></span></td>
                        <td><span class="badge bg-light text-dark border px-2 py-1"><?= esc($user['rol_nombre']) ?></span></td>
                        <td class="text-center">
                            <?php if ($user['estado'] == 1): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <a href="<?= base_url('usuarios/ver/' . $user['id']) ?>" class="btn btn-sm btn-outline-secondary tooltip-action" title="Ver Detalle" style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px"><i class="fa-solid fa-eye fs-7"></i></a>
                                <a href="<?= base_url('usuarios/editar/' . $user['id']) ?>" class="btn btn-sm btn-outline-primary tooltip-action" title="Editar" style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px"><i class="fa-solid fa-pen-to-square fs-7"></i></a>
                                <?php if ($user['estado'] == 1 && $user['id'] !== 1): ?>
                                    <a href="<?= base_url('usuarios/cambiarEstado/' . $user['id'] . '/0') ?>" class="btn btn-sm btn-outline-warning tooltip-action" title="Desactivar" onclick="return confirm('¿Desactivar usuario?');" style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px"><i class="fa-solid fa-ban fs-7"></i></a>
                                <?php elseif ($user['estado'] == 0): ?>
                                    <a href="<?= base_url('usuarios/cambiarEstado/' . $user['id'] . '/1') ?>" class="btn btn-sm btn-outline-success tooltip-action" title="Activar" style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px"><i class="fa-solid fa-circle-check fs-7"></i></a>
                                <?php endif; ?>
                                <?php if ($user['id'] !== 1): ?>
                                    <a href="<?= base_url('usuarios/eliminar/' . $user['id']) ?>" class="btn btn-sm btn-outline-danger tooltip-action" title="Eliminar" onclick="return confirm('¿Eliminar permanentemente al usuario &quot;<?= esc($user['usuario']) ?>&quot;?');" style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:6px"><i class="fa-solid fa-trash-can fs-7"></i></a>
                                <?php endif; ?>
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
    $(document).ready(function() {
        $('#tabla-usuarios').DataTable({ responsive: true, language: { url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json' } });
    });
</script>
<?= $this->endSection() ?>
