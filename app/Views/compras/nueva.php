<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Nueva Compra<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .search-wrapper { position: relative; }
    #sugerencias-lista {
        position: absolute; top: 100%; left: 0; right: 0; z-index: 9999;
        background: #fff; border: 1px solid #E2E8F0;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
        max-height: 320px; overflow-y: auto;
    }
    .sugerencia-item:hover { background: #F8FAFC; }
    .carrito-vacío { padding: 3rem 1rem; text-align: center; color: #94A3B8; }
    .panel-resumen { position: sticky; top: 90px; }
    .resumen-row { display: flex; justify-content: space-between; padding: .5rem 0; border-bottom: 1px dashed #E2E8F0; }
    .resumen-total { display: flex; justify-content: space-between; padding: .75rem 0; }
    #tabla-carrito th, #tabla-carrito td { vertical-align: middle; }
    .scan-line-overlay {
        position: absolute;
        top: 50%;
        left: 10%;
        right: 10%;
        height: 3px;
        background-color: var(--danger, #EF4444);
        box-shadow: 0 0 8px var(--danger, #EF4444);
        animation: scanAnimation 2s infinite ease-in-out;
        pointer-events: none;
        z-index: 10;
    }
    @keyframes scanAnimation {
        0% { top: 20%; }
        50% { top: 80%; }
        100% { top: 20%; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-header d-flex align-items-center gap-3 mb-4">
    <a href="<?= base_url('compras') ?>" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center">
        <i class="fa-solid fa-arrow-left fa-sm"></i>
    </a>
    <div>
        <h1 class="content-title mb-0">Nueva Compra</h1>
        <p class="text-muted mb-0 small">Registra una orden de compra a un proveedor</p>
    </div>
</div>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-exclamation"></i> <?= esc(session()->getFlashdata('error')) ?>
</div>
<?php endif; ?>

<form action="<?= base_url('compras/registrar') ?>" method="POST" id="form-compra">
    <?= csrf_field() ?>
    <input type="hidden" name="items_json" id="items_json" value="[]">

    <div class="row g-4">
        <!-- ══════════════════════ Panel Izquierdo ══════════════════════ -->
        <div class="col-12 col-xl-8">

            <!-- Datos de la compra -->
            <div class="card card-custom mb-4">
                <div class="card-custom-header">
                    <h5 class="card-custom-title"><i class="fa-solid fa-truck me-2 text-primary"></i> Datos de la Compra</h5>
                </div>
                <div class="card-custom-body">
                    <div class="mb-3">
                        <label for="proveedor_id" class="form-label fw-semibold">Proveedor <span class="text-danger">*</span></label>
                        <select name="proveedor_id" id="proveedor_id" class="form-select" required style="border-radius:10px;height:48px">
                            <option value="">— Selecciona un proveedor —</option>
                            <?php foreach ($proveedores as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= esc($p['razon_social']) ?> (<?= esc($p['ruc']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Buscador de productos -->
                    <div class="mb-1">
                        <label class="form-label fw-semibold">Buscar y Agregar Productos <span class="text-danger">*</span></label>
                        <div class="search-wrapper" id="contenedor-busqueda">
                            <div class="input-group" style="border-radius:10px;overflow:hidden;border:1px solid #CBD5E1">
                                <span class="input-group-text" style="background:#F8FAFC;border:none;padding-left:1rem"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" id="buscar-producto" class="form-control" placeholder="Escribe el nombre o código del producto..." autocomplete="off" autofocus style="border:none;height:48px;font-size:.95rem">
                                <button type="button" id="btn-escanear-compra" class="btn btn-outline-primary px-3 d-none" style="border:none;border-left:1px solid #CBD5E1;background:#F8FAFC;color:var(--primary);font-weight:600">
                                    <i class="fa-solid fa-camera me-1"></i> Escanear
                                </button>
                            </div>
                            <div id="sugerencias-lista" class="d-none"></div>
                        </div>
                        <div class="form-text text-muted">Mínimo 2 caracteres para buscar. Los precios de costo son editables en el carrito.</div>
                    </div>
                </div>
            </div>

            <!-- Carrito de productos -->
            <div class="card card-custom">
                <div class="card-custom-header d-flex justify-content-between align-items-center">
                    <h5 class="card-custom-title mb-0"><i class="fa-solid fa-basket-shopping me-2 text-primary"></i> Productos Seleccionados</h5>
                    <span class="badge bg-primary rounded-pill" id="badge-items">0 ítems</span>
                </div>
                <div class="card-custom-body p-0">
                    <div id="aviso-vacio" class="carrito-vacío">
                        <i class="fa-solid fa-inbox fa-2x mb-3 d-block"></i>
                        <p class="mb-0 fw-semibold">El carrito está vacío</p>
                        <p class="small">Busca y agrega productos usando el buscador de arriba.</p>
                    </div>
                    <div class="table-responsive">
                        <table id="tabla-carrito" class="table mb-0" style="display:none">
                            <thead style="background:#F8FAFC">
                                <tr>
                                    <th class="px-3 py-2 text-muted small fw-semibold">Código</th>
                                    <th class="px-3 py-2 text-muted small fw-semibold">Producto</th>
                                    <th class="px-3 py-2 text-center text-muted small fw-semibold">Stock Act.</th>
                                    <th class="px-3 py-2 text-center text-muted small fw-semibold">Cantidad</th>
                                    <th class="px-3 py-2 text-center text-muted small fw-semibold">Costo Unit.</th>
                                    <th class="px-3 py-2 text-end text-muted small fw-semibold">Subtotal</th>
                                    <th class="px-3 py-2 text-center text-muted small fw-semibold"></th>
                                </tr>
                            </thead>
                            <tbody id="carrito-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- ══════════════════════ Panel Derecho (Resumen) ══════════════════════ -->
        <div class="col-12 col-xl-4">
            <div class="panel-resumen">
                <div class="card card-custom mb-4">
                    <div class="card-custom-header">
                        <h5 class="card-custom-title"><i class="fa-solid fa-calculator me-2 text-primary"></i> Resumen</h5>
                    </div>
                    <div class="card-custom-body">
                        <div class="resumen-row">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold" id="resumen-subtotal">S/ 0.00</span>
                        </div>
                        <div class="resumen-row">
                            <span class="text-muted">IGV (18%)</span>
                            <span class="fw-semibold" id="resumen-igv">S/ 0.00</span>
                        </div>
                        <div class="resumen-total mt-2">
                            <span class="fw-bold fs-5">Total a Pagar</span>
                            <span class="fw-bold fs-5 text-primary" id="resumen-total">S/ 0.00</span>
                        </div>
                    </div>
                </div>

                <div class="card card-custom mb-4">
                    <div class="card-custom-header">
                        <h5 class="card-custom-title"><i class="fa-solid fa-note-sticky me-2 text-primary"></i> Observaciones</h5>
                    </div>
                    <div class="card-custom-body">
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="3" placeholder="Notas adicionales sobre esta compra..." style="border-radius:10px;resize:none"></textarea>
                    </div>
                </div>

                <button type="submit" id="btn-registrar" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3" style="border-radius:12px;font-size:1rem;font-weight:700">
                    <i class="fa-solid fa-check-circle"></i> Registrar Compra
                </button>
                <p class="text-center text-muted small mt-2">
                    <i class="fa-solid fa-shield-halved me-1"></i> El stock será actualizado automáticamente.
                </p>
            </div>
        </div>

    </div><!-- /row -->
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const BASE_URL_BUSCAR = '<?= base_url('compras/buscarProducto') ?>';
let carrito = [];

// ── Debounce ─────────────────────────────────────────
function debounce(fn, delay) {
    let t;
    return function(...args) { clearTimeout(t); t = setTimeout(() => fn.apply(this, args), delay); };
}

// ── Escape HTML ───────────────────────────────────────
function esc(t) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(t));
    return d.innerHTML;
}

// ── Buscador ──────────────────────────────────────────
const inputBuscar   = document.getElementById('buscar-producto');
const listaSuger    = document.getElementById('sugerencias-lista');

const buscarDebounce = debounce(async function() {
    const q = inputBuscar.value.trim();
    if (q.length < 2) { listaSuger.innerHTML=''; listaSuger.classList.add('d-none'); return; }
    try {
        const res  = await fetch(`${BASE_URL_BUSCAR}?q=${encodeURIComponent(q)}`);
        const data = await res.json();
        if (data.length === 0) {
            listaSuger.innerHTML = `<div class="p-3 text-muted small text-center"><i class="fa-solid fa-magnifying-glass me-1"></i> Sin resultados para "${esc(q)}"</div>`;
        } else {
            listaSuger.innerHTML = data.map(p => `
                <div class="sugerencia-item px-3 py-2 d-flex justify-content-between align-items-center"
                     style="cursor:pointer;border-bottom:1px solid #F1F5F9"
                     onclick="agregarProducto(${p.id},'${esc(p.codigo)}','${esc(p.nombre)}',${p.precio_compra},${p.stock})">
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem">${esc(p.nombre)}</div>
                        <code class="text-muted" style="font-size:.78rem">${esc(p.codigo)}</code>
                    </div>
                    <div class="text-end ms-3">
                        <div class="fw-bold text-primary">S/ ${parseFloat(p.precio_compra).toFixed(2)}</div>
                        <span class="badge bg-light text-dark border" style="font-size:.72rem">Stock: ${p.stock}</span>
                    </div>
                </div>`).join('');
        }
        listaSuger.classList.remove('d-none');
    } catch(e) { console.error(e); }
}, 400);

inputBuscar.addEventListener('input', buscarDebounce);
document.addEventListener('click', e => { if (!e.target.closest('#contenedor-busqueda')) { listaSuger.classList.add('d-none'); } });

// ── Agregar producto al carrito ───────────────────────
function agregarProducto(id, codigo, nombre, costo, stock) {
    const idx = carrito.findIndex(i => i.producto_id === id);
    if (idx >= 0) {
        carrito[idx].cantidad++;
        carrito[idx].subtotal = +(carrito[idx].cantidad * carrito[idx].costo_unitario).toFixed(2);
    } else {
        const c = parseFloat(parseFloat(costo).toFixed(2));
        carrito.push({ producto_id: id, codigo, nombre, stock: +stock, cantidad: 1, costo_unitario: c, subtotal: c });
    }
    inputBuscar.value = '';
    listaSuger.innerHTML = '';
    listaSuger.classList.add('d-none');
    renderCarrito();
}

// ── Actualizar cantidad ────────────────────────────────
function actualizarCantidad(idx, v) {
    const n = parseInt(v);
    if (isNaN(n) || n < 1) return;
    carrito[idx].cantidad = n;
    carrito[idx].subtotal = +(n * carrito[idx].costo_unitario).toFixed(2);
    renderCarrito();
}

// ── Actualizar costo unitario ──────────────────────────
function actualizarCosto(idx, v) {
    const c = parseFloat(v);
    if (isNaN(c) || c < 0) return;
    carrito[idx].costo_unitario = +c.toFixed(2);
    carrito[idx].subtotal = +(carrito[idx].cantidad * c).toFixed(2);
    renderCarrito();
}

// ── Eliminar ítem ──────────────────────────────────────
function eliminarItem(idx) {
    carrito.splice(idx, 1);
    renderCarrito();
}

// ── Render carrito ─────────────────────────────────────
function renderCarrito() {
    const tbody  = document.getElementById('carrito-tbody');
    const aviso  = document.getElementById('aviso-vacio');
    const tabla  = document.getElementById('tabla-carrito');
    const badge  = document.getElementById('badge-items');

    badge.textContent = carrito.length + ' ítem' + (carrito.length !== 1 ? 's' : '');

    if (carrito.length === 0) {
        aviso.style.display = '';
        tabla.style.display = 'none';
    } else {
        aviso.style.display = 'none';
        tabla.style.display = '';
        tbody.innerHTML = carrito.map((item, idx) => `
            <tr>
                <td class="px-3 py-2"><code class="fw-bold text-primary">${esc(item.codigo)}</code></td>
                <td class="px-3 py-2 fw-semibold" style="max-width:200px">${esc(item.nombre)}</td>
                <td class="px-3 py-2 text-center"><span class="badge bg-light text-dark border">${item.stock}</span></td>
                <td class="px-3 py-2" style="width:110px">
                    <input type="number" class="form-control form-control-sm text-center fw-bold" value="${item.cantidad}" min="1"
                           onchange="actualizarCantidad(${idx},this.value)" style="border-radius:8px">
                </td>
                <td class="px-3 py-2" style="width:140px">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="border-radius:8px 0 0 8px;background:#F8FAFC">S/</span>
                        <input type="number" class="form-control text-end" step="0.01" min="0" value="${item.costo_unitario.toFixed(2)}"
                               onchange="actualizarCosto(${idx},this.value)" style="border-radius:0 8px 8px 0">
                    </div>
                </td>
                <td class="px-3 py-2 text-end fw-bold">S/ ${item.subtotal.toFixed(2)}</td>
                <td class="px-3 py-2 text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarItem(${idx})"
                            style="width:32px;height:32px;padding:0;border-radius:8px;display:flex;align-items:center;justify-content:center">
                        <i class="fa-solid fa-xmark fa-sm"></i>
                    </button>
                </td>
            </tr>`).join('');
    }
    calcularTotales();
}

// ── Calcular totales ───────────────────────────────────
function calcularTotales() {
    const subtotal = carrito.reduce((s, i) => s + i.subtotal, 0);
    const igv      = subtotal * 0.18;
    const total    = subtotal + igv;
    document.getElementById('resumen-subtotal').textContent = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('resumen-igv').textContent      = 'S/ ' + igv.toFixed(2);
    document.getElementById('resumen-total').textContent    = 'S/ ' + total.toFixed(2);
    document.getElementById('items_json').value = JSON.stringify(carrito);
}

// ── Validación antes de submit ─────────────────────────
document.getElementById('form-compra').addEventListener('submit', function(e) {
    if (carrito.length === 0) {
        e.preventDefault();
        alert('⚠️ Debes agregar al menos un producto al carrito antes de registrar la compra.');
        return;
    }
    if (!document.getElementById('proveedor_id').value) {
        e.preventDefault();
        alert('⚠️ Debes seleccionar un proveedor.');
        return;
    }
    document.getElementById('items_json').value = JSON.stringify(carrito);
    document.getElementById('btn-registrar').disabled = true;
    document.getElementById('btn-registrar').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Procesando...';
});

renderCarrito();

// ── Código de barras (Cámara / Móvil) ─────────────────
const BASE_URL_BUSCAR_C = '<?= base_url('compras/buscarProducto') ?>';
const isMobileC = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || (window.innerWidth <= 992);
const btnEscanearC = document.getElementById('btn-escanear-compra');

if (isMobileC && btnEscanearC) {
    btnEscanearC.classList.remove('d-none');
    const script = document.createElement('script');
    script.src = "https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js";
    document.head.appendChild(script);
}

let html5QrCompra = null;

function startCompraScanner() {
    const modalEl = document.getElementById('barcode-modal-compra');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    modalEl.addEventListener('shown.bs.modal', function onShown() {
        modalEl.removeEventListener('shown.bs.modal', onShown);
        html5QrCompra = new Html5Qrcode("scanner-preview-compra");
        const config = {
            fps: 10,
            qrbox: function(w, h) { return { width: Math.min(w * 0.85, 280), height: 140 }; },
            aspectRatio: 1.0
        };
        html5QrCompra.start(
            { facingMode: "environment" }, config,
            (decodedText) => {
                if (navigator.vibrate) navigator.vibrate(100);
                buscarYAgregarCompra(decodedText);
                closeCompraScanner();
            },
            () => {}
        ).catch(err => {
            alert("No se pudo iniciar la cámara. Asegúrate de dar permisos de acceso.");
            closeCompraScanner();
        });
    });

    modalEl.addEventListener('hidden.bs.modal', function onHidden() {
        modalEl.removeEventListener('hidden.bs.modal', onHidden);
        closeCompraScanner();
    });
}

function closeCompraScanner() {
    if (html5QrCompra) {
        if (html5QrCompra.isScanning) {
            html5QrCompra.stop().then(() => { html5QrCompra.clear(); html5QrCompra = null; }).catch(e => {});
        } else { html5QrCompra = null; }
    }
    const modal = bootstrap.Modal.getInstance(document.getElementById('barcode-modal-compra'));
    if (modal) modal.hide();
}

if (btnEscanearC) btnEscanearC.addEventListener('click', startCompraScanner);

async function buscarYAgregarCompra(code) {
    if (!code) return;
    try {
        const res = await fetch(`${BASE_URL_BUSCAR_C}?q=${encodeURIComponent(code)}`);
        const data = await res.json();
        const exactMatch = data.find(p => p.codigo === code);
        if (exactMatch) {
            agregarProducto(exactMatch.id, exactMatch.codigo, exactMatch.nombre, exactMatch.precio_compra, exactMatch.stock);
            return;
        }
        if (data.length === 1) {
            agregarProducto(data[0].id, data[0].codigo, data[0].nombre, data[0].precio_compra, data[0].stock);
        } else {
            alert(`⚠️ Producto con código "${code}" no encontrado.`);
        }
    } catch(e) { console.error(e); }
}

// Interceptar Enter en buscador de productos (soporta pistola USB)
const inputBuscarC = document.getElementById('buscar-producto');
if (inputBuscarC) {
    inputBuscarC.addEventListener('keydown', async function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const val = this.value.trim();
            if (val.length > 0) await buscarYAgregarCompra(val);
        }
    });
}

// Autofoco global para pistola USB
document.addEventListener('keydown', function(e) {
    const active = document.activeElement;
    if (active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.tagName === 'SELECT')) return;
    if (e.key.length === 1 && /^[a-zA-Z0-9]$/.test(e.key)) {
        if (inputBuscarC) inputBuscarC.focus();
    }
});
</script>

<!-- Modal Escáner de Código de Barras - Compras -->
<div class="modal fade" id="barcode-modal-compra" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; overflow:hidden">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-barcode me-2 text-primary"></i> Escáner de Código de Barras</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-black position-relative" style="min-height:320px; display:flex; align-items:center; justify-content:center">
                <div id="scanner-preview-compra" style="width:100%; min-height:320px"></div>
                <div class="scan-line-overlay"></div>
            </div>
            <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius:8px">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
