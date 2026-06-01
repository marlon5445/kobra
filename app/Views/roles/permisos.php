<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Permisos: <?= esc($rol['nombre']) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .modulo-card { background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:1.5rem; margin-bottom:1.25rem; transition:all 0.2s ease; }
    .modulo-card:hover { border-color: var(--primary); box-shadow: 0 4px 12px rgba(79,70,229,0.08); }
    .modulo-title { font-family:'Outfit',sans-serif; font-weight:700; font-size:1rem; text-transform:capitalize; color:var(--text-dark); display:flex; align-items:center; gap:0.6rem; margin-bottom:1rem; }
    .permisos-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:0.65rem; }
    .permiso-item { display:flex; align-items:center; gap:0.5rem; padding:0.6rem 0.85rem; border:1.5px solid #E2E8F0; border-radius:8px; cursor:pointer; transition:all 0.2s; background:#FAFBFC; }
    .permiso-item:has(input:checked) { border-color:var(--primary); background:rgba(79,70,229,0.06); }
    .permiso-item input[type="checkbox"] { accent-color:var(--primary); width:16px; height:16px; cursor:pointer; }
    .permiso-item label { font-size:0.875rem; font-weight:600; cursor:pointer; color:var(--text-dark); text-transform:capitalize; }
    .accion-ver     { color:#6366F1; }
    .accion-crear   { color:#10B981; }
    .accion-editar  { color:#F59E0B; }
    .accion-eliminar{ color:#EF4444; }
    .modulo-icon { width:28px; height:28px; border-radius:6px; background:rgba(79,70,229,0.08); color:var(--primary); display:flex; align-items:center; justify-content:center; font-size:0.8rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-key text-success"></i> Permisos del Rol: <span style="color:var(--primary)"><?= esc($rol['nombre']) ?></span></h1>
        <p class="page-subtitle">Asigna o revoca permisos por módulo mediante la siguiente matriz visual.</p>
    </div>
    <a href="<?= base_url('roles') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius:var(--radius-md)">
        <i class="fa-solid fa-arrow-left"></i> Volver a Roles
    </a>
</div>

<!-- Formulario de la Matriz de Permisos -->
<form action="<?= base_url('roles/guardarPermisos/' . $rol['id']) ?>" method="POST">
    <?= csrf_field() ?>

    <div class="row g-4">

        <!-- Columna de la Matriz -->
        <div class="col-12 col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold m-0 text-muted text-uppercase" style="letter-spacing:0.05em;font-size:0.8rem;">Módulos y Acciones</h6>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-success" id="seleccionarTodo" style="border-radius:6px;font-size:0.8rem;">
                        <i class="fa-solid fa-check-double me-1"></i> Seleccionar Todo
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="deseleccionarTodo" style="border-radius:6px;font-size:0.8rem;">
                        <i class="fa-solid fa-xmark me-1"></i> Deseleccionar Todo
                    </button>
                </div>
            </div>

            <?php
            $iconosModulo = [
                'dashboard'   => 'fa-chart-pie',
                'categorias'  => 'fa-tags',
                'productos'   => 'fa-boxes-stacked',
                'usuarios'    => 'fa-users',
                'roles'       => 'fa-user-shield',
                'ventas'      => 'fa-cash-register',
                'compras'     => 'fa-truck',
                'clientes'    => 'fa-address-book',
                'proveedores' => 'fa-handshake',
                'reportes'    => 'fa-chart-bar',
                'configuracion'=>'fa-sliders',
            ];
            ?>

            <?php foreach ($permisosPorModulo as $modulo => $permisos): ?>
            <div class="modulo-card">
                <div class="modulo-title">
                    <div class="modulo-icon">
                        <i class="fa-solid <?= $iconosModulo[$modulo] ?? 'fa-circle-dot' ?>"></i>
                    </div>
                    <?= ucfirst($modulo) ?>
                    <button type="button" class="btn btn-link p-0 ms-auto btn-check-modulo text-muted" data-modulo="<?= $modulo ?>" style="font-size:0.78rem;text-decoration:none;">
                        Sel. módulo
                    </button>
                </div>
                <div class="permisos-grid">
                    <?php foreach ($permisos as $permiso): ?>
                    <div class="permiso-item" data-modulo="<?= $modulo ?>">
                        <input type="checkbox"
                               name="permisos[]"
                               value="<?= $permiso['id'] ?>"
                               id="perm_<?= $permiso['id'] ?>"
                               <?= in_array($permiso['id'], $permisosActivosIds) ? 'checked' : '' ?>>
                        <label for="perm_<?= $permiso['id'] ?>" class="accion-<?= $permiso['accion'] ?>">
                            <i class="fa-solid <?= $permiso['accion'] === 'ver' ? 'fa-eye' : ($permiso['accion'] === 'crear' ? 'fa-plus' : ($permiso['accion'] === 'editar' ? 'fa-pen' : 'fa-trash')) ?> me-1"></i>
                            <?= ucfirst($permiso['accion']) ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Panel de Información -->
        <div class="col-12 col-lg-4">
            <div class="card card-custom border-0 shadow-sm sticky-top" style="top:90px;">
                <div class="card-custom-header">
                    <h5 class="card-custom-title"><i class="fa-solid fa-circle-info text-primary me-2"></i> Información del Rol</h5>
                </div>
                <div class="card-custom-body">
                    <div class="mb-3">
                        <p class="text-muted small mb-1 text-uppercase fw-bold" style="font-size:0.72rem;letter-spacing:0.05em;">Rol</p>
                        <p class="fw-bold m-0"><?= esc($rol['nombre']) ?></p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1 text-uppercase fw-bold" style="font-size:0.72rem;letter-spacing:0.05em;">Descripción</p>
                        <p class="text-muted small m-0"><?= esc($rol['descripcion']) ?: 'Sin descripción.' ?></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-muted small mb-1 text-uppercase fw-bold" style="font-size:0.72rem;letter-spacing:0.05em;">Estado</p>
                        <span class="badge bg-<?= $rol['estado'] == 1 ? 'success' : 'danger' ?>-subtle text-<?= $rol['estado'] == 1 ? 'success' : 'danger' ?> border border-<?= $rol['estado'] == 1 ? 'success' : 'danger' ?>-subtle">
                            <?= $rol['estado'] == 1 ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </div>

                    <div class="border-top pt-3">
                        <p class="text-muted small mb-2" style="font-size:0.8rem;"><i class="fa-solid fa-circle-info me-1"></i> Los cambios se aplicarán a todos los usuarios que tengan asignado este rol en la próxima sesión.</p>
                        <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" style="border-radius:var(--radius-md)">
                            <i class="fa-solid fa-shield-halved"></i> Guardar Permisos
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Seleccionar / Deseleccionar todos los permisos
    document.getElementById('seleccionarTodo').addEventListener('click', function() {
        document.querySelectorAll('input[name="permisos[]"]').forEach(cb => cb.checked = true);
    });
    document.getElementById('deseleccionarTodo').addEventListener('click', function() {
        document.querySelectorAll('input[name="permisos[]"]').forEach(cb => cb.checked = false);
    });

    // Seleccionar / Deseleccionar por módulo
    document.querySelectorAll('.btn-check-modulo').forEach(btn => {
        btn.addEventListener('click', function() {
            const modulo = this.dataset.modulo;
            const checks = document.querySelectorAll(`.permiso-item[data-modulo="${modulo}"] input[type="checkbox"]`);
            const allChecked = [...checks].every(c => c.checked);
            checks.forEach(c => c.checked = !allChecked);
        });
    });
</script>
<?= $this->endSection() ?>
