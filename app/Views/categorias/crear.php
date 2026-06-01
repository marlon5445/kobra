<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Nueva Categoría<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Encabezado de la página -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-folder-plus text-primary"></i> Registrar Categoría
        </h1>
        <p class="page-subtitle">Añade un nuevo grupo de productos para organizar tu catálogo.</p>
    </div>
    <div>
        <a href="<?= base_url('categorias') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md)">
            <i class="fa-solid fa-arrow-left"></i> Volver al Listado
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8 col-xl-6">
        
        <!-- Tarjeta del Formulario -->
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-custom-header">
                <h5 class="card-custom-title">
                    <i class="fa-solid fa-pen-nib text-primary me-2"></i> Formulario de Registro
                </h5>
            </div>
            <div class="card-custom-body">

                <!-- Alert de errores de validación del modelo -->
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fa-solid fa-circle-xmark fs-5"></i>
                            <strong>Errores de validación:</strong>
                        </div>
                        <ul class="mb-0 ps-3">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('categorias/guardar') ?>" method="POST" autocomplete="off">
                    <?= csrf_field() ?>

                    <!-- Nombre -->
                    <div class="mb-4">
                        <label for="nombre" class="form-label fw-bold text-dark">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               class="form-control form-control-lg fs-6" 
                               placeholder="Ej. Bebidas Gasificadas, Abarrotes, etc." 
                               value="<?= old('nombre') ?>" 
                               required 
                               style="border-radius: var(--radius-md)">
                        <div class="form-text text-muted">El nombre debe ser único y descriptivo para el inventario.</div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-4">
                        <label for="descripcion" class="form-label fw-bold text-dark">Descripción <span class="text-secondary">(Opcional)</span></label>
                        <textarea id="descripcion" 
                                  name="descripcion" 
                                  class="form-control" 
                                  rows="4" 
                                  placeholder="Detalle de los productos que componen esta categoría..." 
                                  style="border-radius: var(--radius-md)"><?= old('descripcion') ?></textarea>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex gap-3 border-top pt-4 mt-2">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md); padding: 0.75rem 1.75rem">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar Categoría
                        </button>
                        <a href="<?= base_url('categorias') ?>" class="btn btn-light" style="border-radius: var(--radius-md); padding: 0.75rem 1.75rem">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
