<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Roles<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-user-shield text-primary"></i> Roles del Sistema</h1>
        <p class="page-subtitle">Administra los grupos de acceso y sus permisos asociados.</p>
    </div>
    <a href="<?= base_url('roles/crear') ?>" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius:var(--radius-md)">
        <i class="fa-solid fa-circle-plus"></i> Nuevo Rol
    </a>
</div>

<div class="card card-custom border-0 shadow-sm">
    <div class="card-custom-header">
        <h5 class="card-custom-title"><i class="fa-solid fa-list-ul text-primary me-2"></i> Listado de Roles</h5>
        <span class="badge bg-primary-light text-primary fw-bold px-3 py-2 border border-primary-subtle"><?= count($roles) ?> roles</span>
    </div>
    <div class="card-custom-body">
        <div class="table-responsive">
            <table id="tabla-roles" class="table table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width:60px;">ID</th>
                        <th>Nombre del Rol</th>
                        <th>Descripción</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center" style="width:180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $rol): ?>
                    <tr>
                        <td class="ps-3 fw-bold text-muted">#<?= $rol['id'] ?></td>
                        <td>
                            <div class="fw-semibold text-dark"><?= esc($rol['nombre']) ?></div>
                        </td>
                        <td><span class="text-muted small"><?= esc($rol['descripcion']) ?: '<em class="text-muted">Sin descripción.</em>' ?></span></td>
                        <td class="text-center">
                            <?php if ($rol['estado'] == 1): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <a href="<?= base_url('roles/permisos/' . $rol['id']) ?>" class="btn btn-sm btn-outline-success tooltip-action" title="Gestionar Permisos" style="width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:var(--radius-md)">
                                    <i class="fa-solid fa-key"></i>
                                </a>
                                <a href="<?= base_url('roles/editar/' . $rol['id']) ?>" class="btn btn-sm btn-outline-primary tooltip-action" title="Editar Rol" style="width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:var(--radius-md)">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <?php if ($rol['id'] !== 1): ?>
                                <a href="<?= base_url('roles/eliminar/' . $rol['id']) ?>" class="btn btn-sm btn-outline-danger tooltip-action" title="Desactivar Rol" onclick="return confirm('¿Desactivar el rol &quot;<?= esc($rol['nombre']) ?>&quot;?');" style="width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:var(--radius-md)">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
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
        $('#tabla-roles').DataTable({ responsive: true, language: { url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json' } });
    });
</script>
<?= $this->endSection() ?>
