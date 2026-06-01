<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Editar Usuario<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-user-pen text-primary"></i> Editar Usuario</h1>
        <p class="page-subtitle">Modifica los datos y el rol del usuario seleccionado.</p>
    </div>
    <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius:var(--radius-md)">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row g-4"><div class="col-12 col-xl-9">
<div class="card card-custom border-0 shadow-sm">
    <div class="card-custom-header">
        <h5 class="card-custom-title"><i class="fa-solid fa-id-card text-primary me-2"></i> Editar Registro #<?= $usuario['id'] ?></h5>
    </div>
    <div class="card-custom-body">
        <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php $errors = session()->getFlashdata('errors'); ?>
            <?php if (is_array($errors)): ?>
                <ul class="mb-0 ps-3"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
            <?php else: ?><?= esc($errors) ?><?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('usuarios/actualizar/' . $usuario['id']) ?>" method="POST" autocomplete="off">
            <?= csrf_field() ?>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nombres <span class="text-danger">*</span></label>
                    <input type="text" name="nombres" class="form-control" value="<?= old('nombres', $usuario['nombres']) ?>" required style="border-radius:var(--radius-md)">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Apellidos <span class="text-danger">*</span></label>
                    <input type="text" name="apellidos" class="form-control" value="<?= old('apellidos', $usuario['apellidos']) ?>" required style="border-radius:var(--radius-md)">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nombre de Usuario <span class="text-danger">*</span></label>
                    <input type="text" name="usuario" class="form-control" value="<?= old('usuario', $usuario['usuario']) ?>" required style="border-radius:var(--radius-md)">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="correo" class="form-control" value="<?= old('correo', $usuario['correo']) ?>" required style="border-radius:var(--radius-md)">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Rol Asignado <span class="text-danger">*</span></label>
                    <select name="rol_id" class="form-select" required style="border-radius:var(--radius-md)">
                        <?php foreach ($roles as $rol): ?>
                        <option value="<?= $rol['id'] ?>" <?= $rolActual == $rol['id'] ? 'selected' : '' ?>><?= esc($rol['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Nueva Contraseña <span class="text-secondary fw-normal">(Opcional)</span></label>
                    <input type="password" name="contrasena" class="form-control" placeholder="Dejar vacío para no cambiar" style="border-radius:var(--radius-md)">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Confirmar Contraseña</label>
                    <input type="password" name="confirmar_contrasena" class="form-control" placeholder="Repite la nueva contraseña" style="border-radius:var(--radius-md)">
                </div>
            </div>

            <div class="d-flex gap-3 border-top pt-4 mt-4">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius:var(--radius-md);padding:0.75rem 1.75rem">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                </button>
                <a href="<?= base_url('usuarios') ?>" class="btn btn-light" style="border-radius:var(--radius-md);padding:0.75rem 1.75rem">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div></div>
<?= $this->endSection() ?>
