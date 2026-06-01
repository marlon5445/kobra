<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Editar Producto<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
.scan-line-overlay {
    position: absolute;
    left: 5%;
    width: 90%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #4f46e5, #06b6d4, #4f46e5, transparent);
    border-radius: 999px;
    animation: scanSlide 1.8s ease-in-out infinite;
    box-shadow: 0 0 12px 2px rgba(79,70,229,0.6);
    z-index: 10;
}
@keyframes scanSlide {
    0%   { top: 10%; opacity: 0; }
    10%  { opacity: 1; }
    90%  { opacity: 1; }
    100% { top: 90%; opacity: 0; }
}
</style>
<!-- Encabezado de la página -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-box-open text-primary"></i> Editar Producto
        </h1>
        <p class="page-subtitle">Modifica la información del artículo seleccionado.</p>
    </div>
    <div>
        <a href="<?= base_url('productos') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md)">
            <i class="fa-solid fa-arrow-left"></i> Volver al Listado
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-10">
        
        <!-- Tarjeta del Formulario -->
        <div class="card card-custom border-0 shadow-sm mb-4">
            <div class="card-custom-header">
                <h5 class="card-custom-title">
                    <i class="fa-solid fa-file-pen text-primary me-2"></i> Editar Registro #<?= esc($producto['id']) ?>
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

                <form action="<?= base_url('productos/actualizar/' . $producto['id']) ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="row g-4">
                        
                        <!-- Columna Izquierda: Información Básica -->
                        <div class="col-12 col-md-6 d-flex flex-column gap-3">
                            
                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="form-label fw-bold text-dark">Nombre del Producto <span class="text-danger">*</span></label>
                                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej. Chocolate Toblerone 100g" value="<?= old('nombre', $producto['nombre']) ?>" required style="border-radius: var(--radius-md)">
                            </div>

                            <!-- Categoría -->
                            <div>
                                <label for="categoria_id" class="form-label fw-bold text-dark">Categoría del Producto <span class="text-danger">*</span></label>
                                <select id="categoria_id" name="categoria_id" class="form-select" required style="border-radius: var(--radius-md)">
                                    <option value="" disabled>-- Selecciona una categoría --</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= old('categoria_id', $producto['categoria_id']) == $cat['id'] ? 'selected' : '' ?>>
                                            <?= esc($cat['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Código de Barras / SKU -->
                            <div>
                                <label for="codigo" class="form-label fw-bold text-dark">Código de Barras / SKU <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="codigo" name="codigo" class="form-control" placeholder="Ej. 7501035002010" value="<?= old('codigo', $producto['codigo']) ?>" required style="border-radius: var(--radius-md) 0 0 var(--radius-md)">
                                    <button type="button" id="btn-escanear-editar" class="btn btn-primary d-none" style="border-radius: 0 var(--radius-md) var(--radius-md) 0">
                                        <i class="fa-solid fa-camera me-1"></i> Escanear
                                    </button>
                                </div>
                                <div class="form-text text-muted">Debe ser un código único alfanumérico.</div>
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label for="descripcion" class="form-label fw-bold text-dark">Descripción <span class="text-secondary">(Opcional)</span></label>
                                <textarea id="descripcion" name="descripcion" class="form-control" rows="4" placeholder="Especificaciones, peso, presentación..." style="border-radius: var(--radius-md)"><?= old('descripcion', $producto['descripcion']) ?></textarea>
                            </div>

                        </div>

                        <!-- Columna Derecha: Precios, Stock e Imagen -->
                        <div class="col-12 col-md-6 d-flex flex-column gap-3">
                            
                            <!-- Precios (Compra y Venta) -->
                            <div class="row g-3">
                                <div class="col-6">
                                    <label for="precio_compra" class="form-label fw-bold text-dark">Precio Compra <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" id="precio_compra" name="precio_compra" class="form-control" step="0.01" min="0" placeholder="0.00" value="<?= old('precio_compra', $producto['precio_compra']) ?>" required style="border-radius: 0 var(--radius-md) var(--radius-md) 0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label for="precio_venta" class="form-label fw-bold text-dark">Precio Venta <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" id="precio_venta" name="precio_venta" class="form-control" step="0.01" min="0" placeholder="0.00" value="<?= old('precio_venta', $producto['precio_venta']) ?>" required style="border-radius: 0 var(--radius-md) var(--radius-md) 0">
                                    </div>
                                </div>
                            </div>

                            <!-- Inventario (Stock y Mínimo) -->
                            <div class="row g-3">
                                <div class="col-6">
                                    <label for="stock" class="form-label fw-bold text-dark">Stock Actual <span class="text-danger">*</span></label>
                                    <input type="number" id="stock" name="stock" class="form-control" min="0" placeholder="Ej. 50" value="<?= old('stock', $producto['stock']) ?>" required style="border-radius: var(--radius-md)">
                                </div>
                                <div class="col-6">
                                    <label for="stock_minimo" class="form-label fw-bold text-dark">Stock Mínimo <span class="text-danger">*</span></label>
                                    <input type="number" id="stock_minimo" name="stock_minimo" class="form-control" min="0" placeholder="Ej. 10" value="<?= old('stock_minimo', $producto['stock_minimo']) ?>" required style="border-radius: var(--radius-md)">
                                </div>
                            </div>

                            <!-- Imagen del Producto -->
                            <div>
                                <label for="imagen" class="form-label fw-bold text-dark">Reemplazar Foto del Producto <span class="text-secondary">(Opcional)</span></label>
                                <input type="file" id="imagen" name="imagen" class="form-control" accept="image/*" style="border-radius: var(--radius-md)">
                                <div class="form-text text-muted">Deja vacío si prefieres conservar la foto actual. Formatos admitidos: JPG, PNG, WEBP (Max 2MB).</div>
                            </div>

                            <!-- Previsualización de Imagen -->
                            <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-light" style="min-height: 90px;">
                                <div id="preview-container" class="d-flex align-items-center justify-content-center border rounded bg-white text-muted" style="width: 60px; height: 60px; overflow: hidden;">
                                    <?php if (!empty($producto['imagen']) && file_exists(FCPATH . 'uploads/productos/' . $producto['imagen'])): ?>
                                        <img src="<?= base_url('uploads/productos/' . $producto['imagen']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <i class="fa-solid fa-image fs-4"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <span class="small fw-semibold text-dark d-block">Foto Actual / Nueva</span>
                                    <span class="small text-muted d-block">Renderizado del producto en el catálogo.</span>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex gap-3 border-top pt-4 mt-4">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-md); padding: 0.75rem 2rem">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                        </button>
                        <a href="<?= base_url('productos') ?>" class="btn btn-light" style="border-radius: var(--radius-md); padding: 0.75rem 2rem">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Mostrar previsualización dinámica de la imagen cargada
    document.getElementById('imagen').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('preview-container');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
            }
            reader.readAsDataURL(file);
        } else {
            // Regresar a la imagen original si se cancela la selección
            <?php if (!empty($producto['imagen']) && file_exists(FCPATH . 'uploads/productos/' . $producto['imagen'])): ?>
                previewContainer.innerHTML = `<img src="<?= base_url('uploads/productos/' . $producto['imagen']) ?>" style="width: 100%; height: 100%; object-fit: cover;">`;
            <?php else: ?>
                previewContainer.innerHTML = `<i class="fa-solid fa-image fs-4"></i>`;
            <?php endif; ?>
        }
    });

// ── Escáner de Código de Barras (Editar Producto) ─────────────────────────
const isMobileED = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || (window.innerWidth <= 992);
const btnEscanearED = document.getElementById('btn-escanear-editar');

if (isMobileED && btnEscanearED) {
    btnEscanearED.classList.remove('d-none');
    const scriptED = document.createElement('script');
    scriptED.src = "https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js";
    document.head.appendChild(scriptED);
}

let html5QrEditar = null;

function startEditarScanner() {
    const modalEl = document.getElementById('barcode-modal-editar');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
    modalEl.addEventListener('shown.bs.modal', function onShown() {
        modalEl.removeEventListener('shown.bs.modal', onShown);
        html5QrEditar = new Html5Qrcode("scanner-preview-editar");
        const config = { fps: 10, qrbox: function(w, h) { return { width: Math.min(w * 0.85, 280), height: 140 }; }, aspectRatio: 1.0 };
        html5QrEditar.start({ facingMode: "environment" }, config,
            (decodedText) => {
                if (navigator.vibrate) navigator.vibrate(100);
                document.getElementById('codigo').value = decodedText;
                closeEditarScanner();
            }, () => {}
        ).catch(() => { alert("No se pudo iniciar la cámara."); closeEditarScanner(); });
    });
    modalEl.addEventListener('hidden.bs.modal', function onHidden() {
        modalEl.removeEventListener('hidden.bs.modal', onHidden);
        closeEditarScanner();
    });
}

function closeEditarScanner() {
    if (html5QrEditar) {
        if (html5QrEditar.isScanning) { html5QrEditar.stop().then(() => { html5QrEditar.clear(); html5QrEditar = null; }).catch(e => {}); }
        else { html5QrEditar = null; }
    }
    const modal = bootstrap.Modal.getInstance(document.getElementById('barcode-modal-editar'));
    if (modal) modal.hide();
}

if (btnEscanearED) btnEscanearED.addEventListener('click', startEditarScanner);
</script>

<!-- Modal Escáner - Editar Producto -->
<div class="modal fade" id="barcode-modal-editar" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; overflow:hidden">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-barcode me-2 text-primary"></i> Escanear Código de Barras</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 bg-black position-relative" style="min-height:320px; display:flex; align-items:center; justify-content:center">
                <div id="scanner-preview-editar" style="width:100%; min-height:320px"></div>
                <div class="scan-line-overlay"></div>
            </div>
            <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius:8px">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
