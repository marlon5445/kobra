<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Editar Rol<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-pen-to-square text-primary"></i> Editar Rol</h1>
        <p class="page-subtitle">Modifica la información del rol seleccionado.</p>
    </div>
    <a href="<?= base_url('roles') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius:var(--radius-md)">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row"><div class="col-12 col-lg-7">
    <div class="card card-custom border-0 shadow-sm">
        <div class="card-custom-header">
            <h5 class="card-custom-title"><i class="fa-solid fa-file-pen text-primary me-2"></i> Editar Registro #<?= $rol['id'] ?></h5>
        </div>
        <div class="card-custom-body">
            <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0 ps-3"><?php foreach (session()->getFlashdata('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <form action="<?= base_url('roles/actualizar/' . $rol['id']) ?>" method="POST" autocomplete="off">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label for="nombre" class="form-label fw-bold">Nombre del Rol <span class="text-danger">*</span></label>
                    <input type="text" id="nombre" name="nombre" class="form-control form-control-lg fs-6" value="<?= old('nombre', $rol['nombre']) ?>" required style="border-radius:var(--radius-md)">
                </div>
                <div class="mb-4">
                    <label for="descripcion" class="form-label fw-bold">Descripción <span class="text-secondary">(Opcional)</span></label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="4" style="border-radius:var(--radius-md)"><?= old('descripcion', $rol['descripcion']) ?></textarea>
                </div>
                <div class="d-flex gap-3 border-top pt-4">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius:var(--radius-md);padding:0.75rem 1.75rem">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                    </button>
                    <a href="<?= base_url('roles') ?>" class="btn btn-light" style="border-radius:var(--radius-md);padding:0.75rem 1.75rem">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
<?= $this->endSection() ?>
