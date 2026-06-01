<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Editar Cliente<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Encabezado de la página -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-user-pen text-primary"></i> Modificar Cliente
        </h1>
        <p class="page-subtitle">Edita los datos de identificación, contacto y observaciones del cliente.</p>
    </div>
    <div>
        <a href="<?= base_url('clientes') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md)">
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
                    <i class="fa-solid fa-user-gear text-primary me-2"></i> Editar Registro: <span class="text-primary fw-bold"><?= esc($cliente['nombres']) ?></span>
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

                <form action="<?= base_url('clientes/actualizar/' . $cliente['id']) ?>" method="POST" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="row">
                        <!-- Nombres / Razón Social -->
                        <div class="col-12 mb-4">
                            <label for="nombres" class="form-label fw-bold text-dark">Nombre / Razón Social <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="nombres" 
                                   name="nombres" 
                                   class="form-control form-control-lg fs-6" 
                                   placeholder="Ej. Juan Pérez o Inversiones Kobra S.A.C." 
                                   value="<?= old('nombres', $cliente['nombres']) ?>" 
                                   required 
                                   style="border-radius: var(--radius-md)">
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="col-md-6 mb-4">
                            <label for="tipo_documento" class="form-label fw-bold text-dark">Tipo de Documento <span class="text-danger">*</span></label>
                            <select id="tipo_documento" 
                                    name="tipo_documento" 
                                    class="form-select form-select-lg fs-6" 
                                    required 
                                    style="border-radius: var(--radius-md)">
                                <?php $tipoDoc = old('tipo_documento', $cliente['tipo_documento']); ?>
                                <option value="DNI" <?= $tipoDoc === 'DNI' ? 'selected' : '' ?>>DNI (Documento Nacional de Identidad)</option>
                                <option value="RUC" <?= $tipoDoc === 'RUC' ? 'selected' : '' ?>>RUC (Registro Único de Contribuyentes)</option>
                                <option value="Cédula" <?= $tipoDoc === 'Cédula' ? 'selected' : '' ?>>Cédula de Identidad</option>
                                <option value="Pasaporte" <?= $tipoDoc === 'Pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                                <option value="Otros" <?= $tipoDoc === 'Otros' ? 'selected' : '' ?>>Otros / Carnet de Extranjería</option>
                            </select>
                        </div>

                        <!-- Número de Documento -->
                        <div class="col-md-6 mb-4">
                            <label for="numero_documento" class="form-label fw-bold text-dark">Número de Documento <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="numero_documento" 
                                   name="numero_documento" 
                                   class="form-control form-control-lg fs-6" 
                                   placeholder="Ej. 10203040" 
                                   value="<?= old('numero_documento', $cliente['numero_documento']) ?>" 
                                   required 
                                   style="border-radius: var(--radius-md)">
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="col-md-6 mb-4">
                            <label for="correo" class="form-label fw-bold text-dark">Correo Electrónico <span class="text-secondary">(Opcional)</span></label>
                            <input type="email" 
                                   id="correo" 
                                   name="correo" 
                                   class="form-control" 
                                   placeholder="Ej. cliente@correo.com" 
                                   value="<?= old('correo', $cliente['correo']) ?>" 
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
                                   value="<?= old('telefono', $cliente['telefono']) ?>" 
                                   style="border-radius: var(--radius-md)">
                        </div>

                        <!-- Dirección -->
                        <div class="col-12 mb-4">
                            <label for="direccion" class="form-label fw-bold text-dark">Dirección de Domicilio <span class="text-secondary">(Opcional)</span></label>
                            <input type="text" 
                                   id="direccion" 
                                   name="direccion" 
                                   class="form-control" 
                                   placeholder="Ej. Av. Principal 123, Urb. San Isidro" 
                                   value="<?= old('direccion', $cliente['direccion']) ?>" 
                                   style="border-radius: var(--radius-md)">
                        </div>

                        <!-- Observaciones -->
                        <div class="col-12 mb-4">
                            <label for="observaciones" class="form-label fw-bold text-dark">Observaciones <span class="text-secondary">(Opcional)</span></label>
                            <textarea id="observaciones" 
                                      name="observaciones" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Anotaciones importantes sobre el cliente..." 
                                      style="border-radius: var(--radius-md)"><?= old('observaciones', $cliente['observaciones']) ?></textarea>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex gap-3 border-top pt-4 mt-2">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md); padding: 0.75rem 1.75rem">
                            <i class="fa-solid fa-square-check"></i> Guardar Cambios
                        </button>
                        <a href="<?= base_url('clientes') ?>" class="btn btn-light" style="border-radius: var(--radius-md); padding: 0.75rem 1.75rem">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
