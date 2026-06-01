<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Detalle de Usuario<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-circle-info text-primary"></i> Detalle de Usuario</h1>
        <p class="page-subtitle">Visualiza la información completa y permisos asignados de este operador.</p>
    </div>
    <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius:var(--radius-md)">
        <i class="fa-solid fa-arrow-left"></i> Volver a Usuarios
    </a>
</div>

<div class="row g-4">
    <!-- Columna Principal -->
    <div class="col-12 col-lg-8">
        <div class="card card-custom border-0 shadow-sm mb-4">
            <div class="card-custom-header">
                <h5 class="card-custom-title"><i class="fa-solid fa-id-card text-primary me-2"></i> Perfil de Usuario</h5>
                <div>
                    <?php if ($usuario['estado'] == 1): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">Inactivo</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-custom-body">
                <div class="d-flex flex-column flex-sm-row align-items-center gap-4 pb-4 border-bottom mb-4">
                    <div class="d-flex align-items-center justify-content-center fw-bold text-white rounded-circle shadow-sm" 
                         style="width:80px;height:80px;background:linear-gradient(135deg, var(--primary) 0%, #06b6d4 100%);font-size:2rem;flex-shrink:0;">
                        <?= strtoupper(mb_substr($usuario['nombres'], 0, 1) . mb_substr($usuario['apellidos'], 0, 1)) ?>
                    </div>
                    <div class="text-center text-sm-start">
                        <h3 class="fw-bold text-dark mb-1"><?= esc($usuario['nombres'] . ' ' . $usuario['apellidos']) ?></h3>
                        <p class="text-muted mb-2">@<?= esc($usuario['usuario']) ?> &bull; <?= esc($usuario['correo']) ?></p>
                        <span class="badge bg-primary-light text-primary fw-bold border border-primary-subtle px-3 py-1.5 fs-7">
                            <i class="fa-solid fa-user-shield me-1"></i> <?= esc($usuario['rol_nombre'] ?? 'Sin Rol') ?>
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3" style="border:1px solid #E2E8F0">
                            <span class="text-muted small d-block mb-1 text-uppercase fw-bold" style="font-size:0.7rem;letter-spacing:0.05em">Nombres</span>
                            <span class="fw-semibold text-dark fs-6"><?= esc($usuario['nombres']) ?></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3" style="border:1px solid #E2E8F0">
                            <span class="text-muted small d-block mb-1 text-uppercase fw-bold" style="font-size:0.7rem;letter-spacing:0.05em">Apellidos</span>
                            <span class="fw-semibold text-dark fs-6"><?= esc($usuario['apellidos']) ?></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3" style="border:1px solid #E2E8F0">
                            <span class="text-muted small d-block mb-1 text-uppercase fw-bold" style="font-size:0.7rem;letter-spacing:0.05em">Usuario</span>
                            <span class="fw-semibold text-dark fs-6"><?= esc($usuario['usuario']) ?></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3" style="border:1px solid #E2E8F0">
                            <span class="text-muted small d-block mb-1 text-uppercase fw-bold" style="font-size:0.7rem;letter-spacing:0.05em">Correo Electrónico</span>
                            <span class="fw-semibold text-dark fs-6"><?= esc($usuario['correo']) ?></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3" style="border:1px solid #E2E8F0">
                            <span class="text-muted small d-block mb-1 text-uppercase fw-bold" style="font-size:0.7rem;letter-spacing:0.05em">Rol en el Sistema</span>
                            <span class="fw-semibold text-dark fs-6"><?= esc($usuario['rol_nombre'] ?? 'Sin Rol') ?></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3" style="border:1px solid #E2E8F0">
                            <span class="text-muted small d-block mb-1 text-uppercase fw-bold" style="font-size:0.7rem;letter-spacing:0.05em">Fecha de Creación</span>
                            <span class="fw-semibold text-dark fs-6"><?= isset($usuario['created_at']) ? date('d/m/Y H:i', strtotime($usuario['created_at'])) : 'No disponible' ?></span>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 pt-3 border-top justify-content-end">
                    <?php if ($usuario['id'] !== 1): ?>
                        <a href="<?= base_url('usuarios/editar/' . $usuario['id']) ?>" class="btn btn-outline-primary d-inline-flex align-items-center gap-2" style="border-radius:var(--radius-md)">
                            <i class="fa-solid fa-user-pen"></i> Editar Información
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna Lateral: Restablecer Contraseña -->
    <div class="col-12 col-lg-4">
        <div class="card card-custom border-0 shadow-sm mb-4">
            <div class="card-custom-header bg-transparent">
                <h5 class="card-custom-title text-danger"><i class="fa-solid fa-key me-2"></i> Zona de Seguridad</h5>
            </div>
            <div class="card-custom-body">
                <p class="text-muted small mb-3">Restablece la contraseña de este usuario. Asegúrate de notificarle el cambio inmediatamente.</p>

                <form action="<?= base_url('usuarios/resetPassword/' . $usuario['id']) ?>" method="POST" autocomplete="off">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="nueva_contrasena" class="form-label fw-bold small text-muted text-uppercase" style="font-size:0.72rem;letter-spacing:0.05em">Nueva Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="nueva_contrasena" id="nueva_contrasena" class="form-control" placeholder="Mínimo 6 caracteres" required style="border-radius:var(--radius-md)">
                    </div>
                    <div class="mb-3">
                        <label for="confirmar_nueva_contrasena" class="form-label fw-bold small text-muted text-uppercase" style="font-size:0.72rem;letter-spacing:0.05em">Confirmar Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="confirmar_nueva_contrasena" id="confirmar_nueva_contrasena" class="form-control" placeholder="Repite la contraseña" required style="border-radius:var(--radius-md)">
                    </div>

                    <button type="submit" class="btn btn-danger w-100 d-inline-flex align-items-center justify-content-center gap-2 mt-2" style="border-radius:var(--radius-md)">
                        <i class="fa-solid fa-shield-halved"></i> Restablecer Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
