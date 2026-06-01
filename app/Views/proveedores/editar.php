<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Editar Proveedor<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Encabezado de la página -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-truck-ramp-box text-primary"></i> Modificar Proveedor
        </h1>
        <p class="page-subtitle">Edita los datos comerciales, fiscales y de contacto del proveedor.</p>
    </div>
    <div>
        <a href="<?= base_url('proveedores') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md)">
            <i class="fa-solid fa-arrow-left"></i> Volver al Listado
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-8">
        
        <!-- Tarjeta del Formulario -->
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-custom-header">
                <h5 class="card-custom-title">
                    <i class="fa-solid fa-truck-field text-primary me-2"></i> Editar Registro: <span class="text-primary fw-bold"><?= esc($proveedor['razon_social']) ?></span>
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

                <form action="<?= base_url('proveedores/actualizar/' . $proveedor['id']) ?>" method="POST" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="row">
                        <!-- RUC -->
                        <div class="col-md-5 mb-4">
                            <label for="ruc" class="form-label fw-bold text-dark">RUC / Registro Fiscal <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="ruc" 
                                   name="ruc" 
                                   class="form-control form-control-lg fs-6" 
                                   placeholder="Ej. 20503040506" 
                                   value="<?= old('ruc', $proveedor['ruc']) ?>" 
                                   required 
                                   style="border-radius: var(--radius-md)">
                        </div>

                        <!-- Razón Social -->
                        <div class="col-md-7 mb-4">
                            <label for="razon_social" class="form-label fw-bold text-dark">Razón Social <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="razon_social" 
                                   name="razon_social" 
                                   class="form-control form-control-lg fs-6" 
                                   placeholder="Ej. Corporación de Alimentos S.A." 
                                   value="<?= old('razon_social', $proveedor['razon_social']) ?>" 
                                   required 
                                   style="border-radius: var(--radius-md)">
                        </div>

                        <!-- Nombre Comercial -->
                        <div class="col-md-6 mb-4">
                            <label for="nombre_comercial" class="form-label fw-bold text-dark">Nombre Comercial <span class="text-secondary">(Opcional)</span></label>
                            <input type="text" 
                                   id="nombre_comercial" 
                                   name="nombre_comercial" 
                                   class="form-control" 
                                   placeholder="Ej. Alimentos del Sol" 
                                   value="<?= old('nombre_comercial', $proveedor['nombre_comercial']) ?>" 
                                   style="border-radius: var(--radius-md)">
                        </div>

                        <!-- Contacto (Persona) -->
                        <div class="col-md-6 mb-4">
                            <label for="contacto" class="form-label fw-bold text-dark">Persona de Contacto <span class="text-secondary">(Opcional)</span></label>
                            <input type="text" 
                                   id="contacto" 
                                   name="contacto" 
                                   class="form-control" 
                                   placeholder="Ej. Lic. Carlos Mendoza" 
                                   value="<?= old('contacto', $proveedor['contacto']) ?>" 
                                   style="border-radius: var(--radius-md)">
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="col-md-6 mb-4">
                            <label for="correo" class="form-label fw-bold text-dark">Correo Electrónico <span class="text-secondary">(Opcional)</span></label>
                            <input type="email" 
                                   id="correo" 
                                   name="correo" 
                                   class="form-control" 
                                   placeholder="Ej. proveedores@empresa.com" 
                                   value="<?= old('correo', $proveedor['correo']) ?>" 
                                   style="border-radius: var(--radius-md)">
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-6 mb-4">
                            <label for="telefono" class="form-label fw-bold text-dark">Teléfono <span class="text-secondary">(Opcional)</span></label>
                            <input type="text" 
                                   id="telefono" 
                                   name="telefono" 
                                   class="form-control" 
                                   placeholder="Ej. 987654321" 
                                   value="<?= old('telefono', $proveedor['telefono']) ?>" 
                                   style="border-radius: var(--radius-md)">
                        </div>

                        <!-- Dirección -->
                        <div class="col-12 mb-4">
                            <label for="direccion" class="form-label fw-bold text-dark">Dirección de Oficina/Almacén <span class="text-secondary">(Opcional)</span></label>
                            <input type="text" 
                                   id="direccion" 
                                   name="direccion" 
                                   class="form-control" 
                                   placeholder="Ej. Av. El Sol 450, Ate Vitarte" 
                                   value="<?= old('direccion', $proveedor['direccion']) ?>" 
                                   style="border-radius: var(--radius-md)">
                        </div>

                        <!-- Observaciones -->
                        <div class="col-12 mb-4">
                            <label for="observaciones" class="form-label fw-bold text-dark">Observaciones <span class="text-secondary">(Opcional)</span></label>
                            <textarea id="observaciones" 
                                      name="observaciones" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Anotaciones importantes sobre el proveedor o convenios..." 
                                      style="border-radius: var(--radius-md)"><?= old('observaciones', $proveedor['observaciones']) ?></textarea>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex gap-3 border-top pt-4 mt-2">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md); padding: 0.75rem 1.75rem">
                            <i class="fa-solid fa-square-check"></i> Guardar Cambios
                        </button>
                        <a href="<?= base_url('proveedores') ?>" class="btn btn-light" style="border-radius: var(--radius-md); padding: 0.75rem 1.75rem">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
