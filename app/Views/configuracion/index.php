<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Configuración de Empresa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="content-title">Configuración de Empresa</h1>
        <p class="text-muted mb-0">Administra los datos comerciales, logo y detalles para la emisión de comprobantes</p>
    </div>
</div>

<?php if (session()->getFlashdata('errors')): ?>
<div class="alert alert-danger d-flex align-items-start gap-2 mb-4">
    <i class="fa-solid fa-triangle-exclamation mt-1"></i>
    <div>
        <span class="fw-bold d-block mb-1">Por favor corrige los siguientes errores:</span>
        <ul class="mb-0 ps-3">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>

<div class="card card-custom">
    <div class="card-custom-header">
        <h5 class="card-custom-title"><i class="fa-solid fa-gears me-2 text-primary"></i> Datos de la Organización</h5>
    </div>
    
    <div class="card-custom-body">
        <form action="<?= base_url('configuracion/actualizar') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row g-4">
                <!-- Seccion izquierda: Formulario -->
                <div class="col-12 col-lg-8">
                    <h6 class="fw-bold mb-3 pb-1 border-bottom" style="color:var(--primary)"><i class="fa-solid fa-circle-info me-1"></i> Información Comercial</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="razon_social" class="form-label fw-semibold">Razón Social <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="razon_social" name="razon_social" value="<?= esc(old('razon_social', $configuracion['razon_social'])) ?>" required style="border-radius:8px">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="nombre_comercial" class="form-label fw-semibold">Nombre Comercial</label>
                            <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" value="<?= esc(old('nombre_comercial', $configuracion['nombre_comercial'])) ?>" style="border-radius:8px">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="ruc" class="form-label fw-semibold">RUC <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ruc" name="ruc" value="<?= esc(old('ruc', $configuracion['ruc'])) ?>" required style="border-radius:8px">
                        </div>

                        <div class="col-md-6">
                            <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= esc(old('telefono', $configuracion['telefono'])) ?>" style="border-radius:8px">
                        </div>

                        <div class="col-md-6">
                            <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" value="<?= esc(old('correo', $configuracion['correo'])) ?>" style="border-radius:8px">
                        </div>

                        <div class="col-md-6">
                            <label for="direccion" class="form-label fw-semibold">Dirección Física</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="<?= esc(old('direccion', $configuracion['direccion'])) ?>" style="border-radius:8px">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 pb-1 border-bottom" style="color:var(--primary)"><i class="fa-solid fa-coins me-1"></i> Moneda y Finanzas</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="moneda" class="form-label fw-semibold">Nombre de la Moneda <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="moneda" name="moneda" value="<?= esc(old('moneda', $configuracion['moneda'])) ?>" placeholder="Ej: Soles, Dólares" required style="border-radius:8px">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="simbolo_moneda" class="form-label fw-semibold">Símbolo Monetario <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="simbolo_moneda" name="simbolo_moneda" value="<?= esc(old('simbolo_moneda', $configuracion['simbolo_moneda'])) ?>" placeholder="Ej: S/, $" required style="border-radius:8px">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 pb-1 border-bottom" style="color:var(--primary)"><i class="fa-solid fa-receipt me-1"></i> Personalización de Comprobante</h6>
                    
                    <div class="mb-4">
                        <label for="mensaje_ticket" class="form-label fw-semibold">Mensaje en el Pie del Ticket</label>
                        <textarea class="form-control" id="mensaje_ticket" name="mensaje_ticket" rows="3" placeholder="Mensaje de agradecimiento u otros..." style="border-radius:8px;resize:none"><?= esc(old('mensaje_ticket', $configuracion['mensaje_ticket'])) ?></textarea>
                    </div>
                </div>

                <!-- Seccion derecha: Carga del Logo -->
                <div class="col-12 col-lg-4 border-start-lg">
                    <div class="ps-lg-3">
                        <h6 class="fw-bold mb-3 pb-1 border-bottom" style="color:var(--primary)"><i class="fa-regular fa-image me-1"></i> Logotipo Corporativo</h6>
                        <p class="text-muted small">Este logo se imprimirá en la parte superior de los comprobantes térmicos y se mostrará en los reportes de ventas.</p>
                        
                        <div class="d-flex flex-column align-items-center justify-content-center p-4 border rounded-3 text-center mb-3" style="background:#F8FAFC; border-style:dashed !important">
                            <div class="mb-3" style="width:140px; height:140px; border-radius:12px; background:#FFF; border:1px solid #E2E8F0; overflow:hidden; display:flex; align-items:center; justify-content:center; box-shadow:var(--shadow-sm)" id="logo-preview-container">
                                <?php if (!empty($configuracion['logo']) && file_exists(ROOTPATH . 'public/uploads/logo/' . $configuracion['logo'])): ?>
                                    <img src="<?= base_url('uploads/logo/' . $configuracion['logo']) ?>" id="logo-preview" class="img-fluid" style="max-height:100%; max-width:100%; object-fit:contain" alt="Logo de Empresa">
                                <?php else: ?>
                                    <div class="text-muted d-flex flex-column align-items-center justify-content-center" id="logo-placeholder">
                                        <i class="fa-solid fa-store fa-3x mb-2 text-primary" style="opacity:0.3"></i>
                                        <span class="small" style="font-size:0.75rem">Sin logotipo</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="w-100">
                                <label for="logo" class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center gap-2 mb-2" style="border-radius:8px; cursor:pointer">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> Seleccionar Imagen
                                </label>
                                <input type="file" id="logo" name="logo" class="d-none" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewImage(event)">
                                <span class="text-muted small" style="font-size:0.72rem">Formatos: PNG, JPG, JPEG, WEBP. Máx: 2MB</span>
                            </div>
                        </div>

                        <?php if (!empty($configuracion['logo'])): ?>
                            <div class="alert alert-light border small text-muted d-flex align-items-center gap-2" style="border-radius:8px">
                                <i class="fa-solid fa-file-signature text-primary"></i>
                                <span class="text-truncate d-inline-block" style="max-width:220px">Archivo actual: <code><?= esc($configuracion['logo']) ?></code></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row mt-4 pt-3 border-top">
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2 ms-auto" style="border-radius:8px;font-weight:600">
                        <i class="fa-solid fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function previewImage(event) {
    const input = event.target;
    const file = input.files[0];
    const previewContainer = document.getElementById('logo-preview-container');
    
    if (file) {
        // Validar tamaño (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            alert('El archivo es demasiado grande. El límite es 2MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = `<img src="${e.target.result}" id="logo-preview" class="img-fluid" style="max-height:100%; max-width:100%; object-fit:contain" alt="Vista Previa">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
<?= $this->endSection() ?>
