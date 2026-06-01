<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Nueva Venta — POS<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .search-wrapper { position: relative; }
    .autocomplete-lista {
        position: absolute; top: 100%; left: 0; right: 0; z-index: 9999;
        background: #fff; border: 1px solid #E2E8F0;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
        max-height: 300px; overflow-y: auto;
    }
    .autocomplete-item:hover { background: #F8FAFC; }
    .tipo-btn { cursor:pointer;border:2px solid #E2E8F0;border-radius:10px;padding:.5rem 1.1rem;font-weight:600;transition:all .2s;font-size:.88rem; }
    .tipo-btn.selected { border-color:#4F46E5;background:#4F46E5;color:#fff; }
    .tipo-btn.boleta.selected  { border-color:#3B82F6;background:#3B82F6;color:#fff; }
    .tipo-btn.factura.selected { border-color:#8B5CF6;background:#8B5CF6;color:#fff; }
    .tipo-btn.ticket.selected  { border-color:#10B981;background:#10B981;color:#fff; }
    .carrito-vacío { padding:3rem 1rem;text-align:center;color:#94A3B8; }
    .panel-resumen { position:sticky;top:90px; }
    .resumen-row { display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px dashed #E2E8F0; }
    .resumen-total { display:flex;justify-content:space-between;padding:.75rem 0; }
    #tabla-carrito th, #tabla-carrito td { vertical-align:middle; }
    .row-igv { display:none; }
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
    <a href="<?= base_url('ventas') ?>" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center">
        <i class="fa-solid fa-arrow-left fa-sm"></i>
    </a>
    <div>
        <h1 class="content-title mb-0">Nueva Venta — POS</h1>
        <p class="text-muted mb-0 small">Registra una venta y genera el comprobante</p>
    </div>
</div>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-exclamation"></i> <?= esc(session()->getFlashdata('error')) ?>
</div>
<?php endif; ?>

<form action="<?= base_url('ventas/registrar') ?>" method="POST" id="form-venta">
    <?= csrf_field() ?>
    <input type="hidden" name="items_json"    id="items_json"    value="[]">
    <input type="hidden" name="cliente_id"    id="cliente_id_hidden" value="">
    <input type="hidden" name="tipo_comprobante" id="tipo_comprobante" value="Boleta">
    <input type="hidden" name="descuento_global" id="descuento_global_hidden" value="0">

    <div class="row g-4">
        <!-- ══════════════════════ Panel Izquierdo ══════════════════════ -->
        <div class="col-12 col-xl-8">

            <!-- Tipo de comprobante + Cliente -->
            <div class="card card-custom mb-4">
                <div class="card-custom-header">
                    <h5 class="card-custom-title"><i class="fa-solid fa-file-invoice me-2 text-primary"></i> Datos del Comprobante</h5>
                </div>
                <div class="card-custom-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tipo de Comprobante <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="tipo-btn boleta selected" data-tipo="Boleta" id="btn-boleta" onclick="seleccionarTipo('Boleta')">
                                <i class="fa-solid fa-file-lines me-1"></i> Boleta
                            </button>
                            <button type="button" class="tipo-btn factura" data-tipo="Factura" id="btn-factura" onclick="seleccionarTipo('Factura')">
                                <i class="fa-solid fa-file-invoice me-1"></i> Factura
                            </button>
                            <button type="button" class="tipo-btn ticket" data-tipo="Ticket" id="btn-ticket" onclick="seleccionarTipo('Ticket')">
                                <i class="fa-solid fa-receipt me-1"></i> Ticket
                            </button>
                        </div>
                        <div class="form-text text-muted mt-1" id="info-tipo">Boleta: sin IGV separado (precio ya incluye impuesto).</div>
                    </div>

                    <!-- Búsqueda de cliente (opcional) -->
                    <div>
                        <label class="form-label fw-semibold">Cliente <span class="text-muted small fw-normal">(opcional — dejar vacío para Consumidor Final)</span></label>
                        <div class="search-wrapper" id="contenedor-cliente">
                            <div class="input-group" style="border-radius:10px;overflow:hidden;border:1px solid #CBD5E1">
                                <span class="input-group-text" style="background:#F8FAFC;border:none;padding-left:1rem"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" id="buscar-cliente" class="form-control" placeholder="Buscar por nombre o número de documento..." autocomplete="off" style="border:none;height:48px;font-size:.95rem">
                                <button type="button" class="btn btn-sm btn-outline-secondary border-0" id="btn-limpiar-cliente" style="display:none;border-radius:0 10px 10px 0" onclick="limpiarCliente()">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div id="sugerencias-cliente" class="autocomplete-lista d-none"></div>
                        </div>
                        <div id="cliente-seleccionado" class="mt-2 d-none">
                            <div class="p-2 rounded-3 d-flex align-items-center gap-2" style="background:rgba(79,70,229,.06);border:1px solid rgba(79,70,229,.2)">
                                <i class="fa-solid fa-circle-check text-primary"></i>
                                <span class="fw-semibold text-primary" id="cliente-nombre-display"></span>
                                <span class="text-muted small" id="cliente-doc-display"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buscador de productos -->
            <div class="card card-custom mb-4">
                <div class="card-custom-header">
                    <h5 class="card-custom-title"><i class="fa-solid fa-magnifying-glass me-2 text-primary"></i> Buscar Producto</h5>
                </div>
                <div class="card-custom-body">
                    <div class="search-wrapper" id="contenedor-producto">
                        <div class="input-group" style="border-radius:10px;overflow:hidden;border:1px solid #CBD5E1">
                            <span class="input-group-text" style="background:#F8FAFC;border:none;padding-left:1rem"><i class="fa-solid fa-barcode text-muted"></i></span>
                            <input type="text" id="buscar-producto" class="form-control" placeholder="Buscar por nombre o código de barras..." autocomplete="off" autofocus style="border:none;height:48px;font-size:.95rem">
                            <button type="button" id="btn-escanear-barcode" class="btn btn-outline-primary px-3 d-none" style="border:none;border-left:1px solid #CBD5E1;background:#F8FAFC;color:var(--primary);font-weight:600">
                                <i class="fa-solid fa-camera me-1"></i> Escanear
                            </button>
                        </div>
                        <div id="sugerencias-producto" class="autocomplete-lista d-none"></div>
                    </div>
                    <div class="form-text text-muted">Solo se muestran productos con stock disponible.</div>
                </div>
            </div>

            <!-- Carrito -->
            <div class="card card-custom">
                <div class="card-custom-header d-flex justify-content-between align-items-center">
                    <h5 class="card-custom-title mb-0"><i class="fa-solid fa-basket-shopping me-2 text-primary"></i> Carrito de Venta</h5>
                    <span class="badge bg-primary rounded-pill" id="badge-items">0 ítems</span>
                </div>
                <div class="card-custom-body p-0">
                    <div id="aviso-vacio" class="carrito-vacío">
                        <i class="fa-solid fa-cart-shopping fa-2x mb-3 d-block"></i>
                        <p class="mb-0 fw-semibold">El carrito está vacío</p>
                        <p class="small">Busca productos y agrégalos usando el buscador.</p>
                    </div>
                    <div class="table-responsive">
                        <table id="tabla-carrito" class="table mb-0" style="display:none">
                            <thead style="background:#F8FAFC">
                                <tr>
                                    <th class="px-3 py-2 text-muted small fw-semibold">Producto</th>
                                    <th class="px-3 py-2 text-center text-muted small fw-semibold">Stock</th>
                                    <th class="px-3 py-2 text-center text-muted small fw-semibold">Precio Unit.</th>
                                    <th class="px-3 py-2 text-center text-muted small fw-semibold">Cantidad</th>
                                    <th class="px-3 py-2 text-center text-muted small fw-semibold">Desc. (S/)</th>
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
                        <h5 class="card-custom-title"><i class="fa-solid fa-calculator me-2 text-primary"></i> Resumen de Venta</h5>
                    </div>
                    <div class="card-custom-body">
                        <div class="resumen-row">
                            <span class="text-muted">Subtotal de ítems</span>
                            <span class="fw-semibold" id="resumen-subtotal">S/ 0.00</span>
                        </div>
                        <div class="resumen-row">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted">Descuento global</span>
                            </div>
                            <div style="width:120px">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text" style="background:#F8FAFC;border-radius:6px 0 0 6px">S/</span>
                                    <input type="number" id="descuento_global" class="form-control text-end" step="0.01" min="0" value="0"
                                           style="border-radius:0 6px 6px 0" oninput="calcularTotales()">
                                </div>
                            </div>
                        </div>
                        <div class="resumen-row row-igv" id="fila-igv">
                            <span class="text-muted">IGV (18%) <span class="badge bg-light text-dark border" style="font-size:.7rem">Factura</span></span>
                            <span class="fw-semibold text-success" id="resumen-igv">S/ 0.00</span>
                        </div>
                        <div class="resumen-total mt-2">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-4" style="color:#4F46E5" id="resumen-total">S/ 0.00</span>
                        </div>
                    </div>
                </div>

                <div class="card card-custom mb-4">
                    <div class="card-custom-header">
                        <h5 class="card-custom-title"><i class="fa-solid fa-note-sticky me-2 text-primary"></i> Observaciones</h5>
                    </div>
                    <div class="card-custom-body">
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="3" placeholder="Notas adicionales..." style="border-radius:10px;resize:none"></textarea>
                    </div>
                </div>

                <button type="submit" id="btn-registrar" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3" style="border-radius:12px;font-size:1rem;font-weight:700">
                    <i class="fa-solid fa-check-circle"></i> Registrar Venta
                </button>
                <p class="text-center text-muted small mt-2">
                    <i class="fa-solid fa-shield-halved me-1"></i> El stock se descuenta automáticamente.
                </p>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const URL_PRODUCTO = '<?= base_url('ventas/buscarProducto') ?>';
const URL_CLIENTE  = '<?= base_url('ventas/buscarCliente') ?>';

let carrito  = [];
let tipoActual = 'Boleta';

// ── Debounce ──────────────────────────────────────────
function debounce(fn, d) { let t; return function(...a) { clearTimeout(t); t = setTimeout(() => fn.apply(this,a), d); }; }

// ── Escape HTML ───────────────────────────────────────
function esc(t) { const d=document.createElement('div'); d.appendChild(document.createTextNode(String(t||''))); return d.innerHTML; }

// ── Tipo de comprobante ───────────────────────────────
function seleccionarTipo(tipo) {
    tipoActual = tipo;
    document.getElementById('tipo_comprobante').value = tipo;
    document.querySelectorAll('.tipo-btn').forEach(b => b.classList.remove('selected'));
    document.querySelector(`.tipo-btn.${tipo.toLowerCase()}`).classList.add('selected');
    const infoEl = document.getElementById('info-tipo');
    const filaIgv = document.getElementById('fila-igv');
    if (tipo === 'Factura') {
        infoEl.textContent = 'Factura: IGV 18% se suma automáticamente al total.';
        filaIgv.style.display = 'flex';
    } else if (tipo === 'Boleta') {
        infoEl.textContent = 'Boleta: sin IGV separado (precio ya incluye impuesto).';
        filaIgv.style.display = 'none';
    } else {
        infoEl.textContent = 'Ticket: comprobante simplificado sin IGV separado.';
        filaIgv.style.display = 'none';
    }
    calcularTotales();
}

// ── Búsqueda de clientes ──────────────────────────────
const inputCliente  = document.getElementById('buscar-cliente');
const listaClientes = document.getElementById('sugerencias-cliente');

const buscarClienteDeb = debounce(async function() {
    const q = inputCliente.value.trim();
    if (q.length < 2) { listaClientes.innerHTML=''; listaClientes.classList.add('d-none'); return; }
    try {
        const res  = await fetch(`${URL_CLIENTE}?q=${encodeURIComponent(q)}`);
        const data = await res.json();
        if (data.length === 0) {
            listaClientes.innerHTML = `<div class="p-3 text-muted small text-center">Sin resultados para "${esc(q)}"</div>`;
        } else {
            listaClientes.innerHTML = data.map(c => `
                <div class="autocomplete-item px-3 py-2 d-flex justify-content-between align-items-center"
                     style="cursor:pointer;border-bottom:1px solid #F1F5F9"
                     onclick="seleccionarCliente(${c.id},'${esc(c.nombres)}','${esc(c.tipo_documento)}','${esc(c.numero_documento)}')">
                    <div class="fw-semibold" style="font-size:.9rem">${esc(c.nombres)}</div>
                    <span class="badge bg-light text-dark border">${esc(c.tipo_documento)}: ${esc(c.numero_documento)}</span>
                </div>`).join('');
        }
        listaClientes.classList.remove('d-none');
    } catch(e) {}
}, 400);

inputCliente.addEventListener('input', buscarClienteDeb);
document.addEventListener('click', e => {
    if (!e.target.closest('#contenedor-cliente')) listaClientes.classList.add('d-none');
    if (!e.target.closest('#contenedor-producto')) document.getElementById('sugerencias-producto').classList.add('d-none');
});

function seleccionarCliente(id, nombre, tipo, numero) {
    document.getElementById('cliente_id_hidden').value = id;
    document.getElementById('cliente-nombre-display').textContent = nombre;
    document.getElementById('cliente-doc-display').textContent = `(${tipo}: ${numero})`;
    document.getElementById('cliente-seleccionado').classList.remove('d-none');
    document.getElementById('btn-limpiar-cliente').style.display = '';
    inputCliente.value = nombre;
    listaClientes.innerHTML = '';
    listaClientes.classList.add('d-none');
}

function limpiarCliente() {
    document.getElementById('cliente_id_hidden').value = '';
    document.getElementById('cliente-seleccionado').classList.add('d-none');
    document.getElementById('btn-limpiar-cliente').style.display = 'none';
    inputCliente.value = '';
}

// ── Búsqueda de productos ──────────────────────────────
const inputProducto  = document.getElementById('buscar-producto');
const listaProductos = document.getElementById('sugerencias-producto');

const buscarProductoDeb = debounce(async function() {
    const q = inputProducto.value.trim();
    if (q.length < 2) { listaProductos.innerHTML=''; listaProductos.classList.add('d-none'); return; }
    try {
        const res  = await fetch(`${URL_PRODUCTO}?q=${encodeURIComponent(q)}`);
        const data = await res.json();
        if (data.length === 0) {
            listaProductos.innerHTML = `<div class="p-3 text-muted small text-center">Sin resultados o sin stock para "${esc(q)}"</div>`;
        } else {
            listaProductos.innerHTML = data.map(p => `
                <div class="autocomplete-item px-3 py-2 d-flex justify-content-between align-items-center"
                     style="cursor:pointer;border-bottom:1px solid #F1F5F9"
                     onclick="agregarProducto(${p.id},'${esc(p.codigo)}','${esc(p.nombre)}',${p.precio_venta},${p.stock})">
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem">${esc(p.nombre)}</div>
                        <code class="text-muted" style="font-size:.78rem">${esc(p.codigo)}</code>
                    </div>
                    <div class="text-end ms-3">
                        <div class="fw-bold text-primary">S/ ${parseFloat(p.precio_venta).toFixed(2)}</div>
                        <span class="badge ${p.stock <= 5 ? 'bg-danger' : 'bg-light text-dark border'}" style="font-size:.72rem">Stock: ${p.stock}</span>
                    </div>
                </div>`).join('');
        }
        listaProductos.classList.remove('d-none');
    } catch(e) {}
}, 400);

inputProducto.addEventListener('input', buscarProductoDeb);

// ── Agregar al carrito ────────────────────────────────
function agregarProducto(id, codigo, nombre, precio, stock) {
    const idx = carrito.findIndex(i => i.producto_id === id);
    if (idx >= 0) {
        if (carrito[idx].cantidad >= carrito[idx].stock) {
            alert(`⚠️ No puedes agregar más unidades de "${nombre}". Stock disponible: ${stock}`);
            return;
        }
        carrito[idx].cantidad++;
        recalcItem(idx);
    } else {
        const p = parseFloat(parseFloat(precio).toFixed(2));
        carrito.push({ producto_id:id, codigo, nombre, stock:+stock, cantidad:1, precio_unitario:p, descuento:0, subtotal:p });
    }
    inputProducto.value = '';
    listaProductos.innerHTML = '';
    listaProductos.classList.add('d-none');
    renderCarrito();
}

// ── Recalcular ítem ───────────────────────────────────
function recalcItem(idx) {
    const i = carrito[idx];
    i.subtotal = +Math.max(0, (i.precio_unitario * i.cantidad) - i.descuento).toFixed(2);
}

function actualizarCantidad(idx, v) {
    const n = parseInt(v); if (isNaN(n)||n<1) return;
    if (n > carrito[idx].stock) { alert(`Stock máximo disponible: ${carrito[idx].stock}`); return; }
    carrito[idx].cantidad = n; recalcItem(idx); renderCarrito();
}
function actualizarPrecio(idx, v) {
    const p = parseFloat(v); if (isNaN(p)||p<0) return;
    carrito[idx].precio_unitario = +p.toFixed(2); recalcItem(idx); renderCarrito();
}
function actualizarDescuento(idx, v) {
    const d = parseFloat(v)||0;
    carrito[idx].descuento = +d.toFixed(2); recalcItem(idx); renderCarrito();
}
function eliminarItem(idx) { carrito.splice(idx, 1); renderCarrito(); }

// ── Render carrito ─────────────────────────────────────
function renderCarrito() {
    const tbody = document.getElementById('carrito-tbody');
    const aviso = document.getElementById('aviso-vacio');
    const tabla = document.getElementById('tabla-carrito');
    const badge = document.getElementById('badge-items');

    badge.textContent = carrito.length + ' ítem' + (carrito.length !== 1 ? 's' : '');

    if (carrito.length === 0) { aviso.style.display=''; tabla.style.display='none'; }
    else {
        aviso.style.display='none'; tabla.style.display='';
        tbody.innerHTML = carrito.map((item, idx) => `
            <tr>
                <td class="px-3 py-2">
                    <div class="fw-semibold" style="font-size:.88rem;max-width:180px">${esc(item.nombre)}</div>
                    <code class="text-muted" style="font-size:.75rem">${esc(item.codigo)}</code>
                </td>
                <td class="px-3 py-2 text-center"><span class="badge bg-light text-dark border">${item.stock}</span></td>
                <td class="px-3 py-2" style="width:130px">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="background:#F8FAFC;border-radius:6px 0 0 6px;font-size:.8rem">S/</span>
                        <input type="number" class="form-control text-end" step="0.01" min="0" value="${item.precio_unitario.toFixed(2)}"
                               onchange="actualizarPrecio(${idx},this.value)" style="border-radius:0 6px 6px 0;font-size:.85rem">
                    </div>
                </td>
                <td class="px-3 py-2" style="width:90px">
                    <input type="number" class="form-control form-control-sm text-center fw-bold" value="${item.cantidad}" min="1" max="${item.stock}"
                           onchange="actualizarCantidad(${idx},this.value)" style="border-radius:8px">
                </td>
                <td class="px-3 py-2" style="width:110px">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" style="background:#F8FAFC;border-radius:6px 0 0 6px;font-size:.8rem">S/</span>
                        <input type="number" class="form-control text-end" step="0.01" min="0" value="${item.descuento.toFixed(2)}"
                               onchange="actualizarDescuento(${idx},this.value)" style="border-radius:0 6px 6px 0;font-size:.85rem">
                    </div>
                </td>
                <td class="px-3 py-2 text-end fw-bold" style="color:#4F46E5">S/ ${item.subtotal.toFixed(2)}</td>
                <td class="px-3 py-2 text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarItem(${idx})"
                            style="width:30px;height:30px;padding:0;border-radius:7px;display:flex;align-items:center;justify-content:center">
                        <i class="fa-solid fa-xmark fa-sm"></i>
                    </button>
                </td>
            </tr>`).join('');
    }
    calcularTotales();
}

// ── Calcular totales ───────────────────────────────────
function calcularTotales() {
    const subtotal  = carrito.reduce((s, i) => s + i.subtotal, 0);
    const descGlobal = parseFloat(document.getElementById('descuento_global').value) || 0;
    const base      = Math.max(0, subtotal - descGlobal);
    const igv       = (tipoActual === 'Factura') ? base * 0.18 : 0;
    const total     = base + igv;

    document.getElementById('resumen-subtotal').textContent = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('resumen-igv').textContent      = 'S/ ' + igv.toFixed(2);
    document.getElementById('resumen-total').textContent    = 'S/ ' + total.toFixed(2);
    document.getElementById('items_json').value             = JSON.stringify(carrito);
    document.getElementById('descuento_global_hidden').value = descGlobal.toFixed(2);
}

// ── Submit ─────────────────────────────────────────────
document.getElementById('form-venta').addEventListener('submit', function(e) {
    if (carrito.length === 0) { e.preventDefault(); alert('⚠️ El carrito está vacío. Agrega al menos un producto.'); return; }
    document.getElementById('items_json').value = JSON.stringify(carrito);
    document.getElementById('descuento_global_hidden').value = (parseFloat(document.getElementById('descuento_global').value)||0).toFixed(2);
    document.getElementById('btn-registrar').disabled = true;
    document.getElementById('btn-registrar').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Procesando...';
});

// ── Init ───────────────────────────────────────────────
renderCarrito();
seleccionarTipo('Boleta');

// ── Código de barras (Cámara / Móvil) ───────────────────
const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || (window.innerWidth <= 992);
const btnEscanear = document.getElementById('btn-escanear-barcode');

if (isMobile && btnEscanear) {
    btnEscanear.classList.remove('d-none');
    
    // Inyectar script de html5-qrcode dinámicamente si es móvil
    const script = document.createElement('script');
    script.src = "https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js";
    script.onload = function() {
        console.log("Html5Qrcode cargado.");
    };
    document.head.appendChild(script);
}

let html5QrcodeScanner = null;

function startCameraScanner() {
    const modalElement = document.getElementById('barcode-scanner-modal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    modalElement.addEventListener('shown.bs.modal', function onShown() {
        modalElement.removeEventListener('shown.bs.modal', onShown);
        
        html5QrcodeScanner = new Html5Qrcode("scanner-preview");
        const config = { 
            fps: 10, 
            qrbox: function(width, height) {
                return { width: Math.min(width * 0.85, 280), height: 140 };
            },
            aspectRatio: 1.0
        };

        html5QrcodeScanner.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                if (navigator.vibrate) navigator.vibrate(100);
                buscarYAgregarProductoPorCodigo(decodedText);
                closeCameraScanner();
            },
            (errorMessage) => {
                // Silencioso
            }
        ).catch(err => {
            console.error("Error al iniciar cámara: ", err);
            alert("No se pudo iniciar la cámara. Asegúrate de dar permisos de acceso.");
            closeCameraScanner();
        });
    });

    modalElement.addEventListener('hidden.bs.modal', function onHidden() {
        modalElement.removeEventListener('hidden.bs.modal', onHidden);
        closeCameraScanner();
    });
}

function closeCameraScanner() {
    if (html5QrcodeScanner) {
        if (html5QrcodeScanner.isScanning) {
            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
            }).catch(err => console.error(err));
        } else {
            html5QrcodeScanner = null;
        }
    }
    const modalElement = document.getElementById('barcode-scanner-modal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
}

if (btnEscanear) {
    btnEscanear.addEventListener('click', startCameraScanner);
}

// ── Búsqueda y agregado automático por código ──────────
async function buscarYAgregarProductoPorCodigo(code) {
    if (!code) return;
    try {
        const res = await fetch(`${URL_PRODUCTO}?q=${encodeURIComponent(code)}`);
        const data = await res.json();
        
        // Intentar encontrar coincidencia exacta en código
        const exactMatch = data.find(p => p.codigo === code);
        if (exactMatch) {
            agregarProducto(exactMatch.id, exactMatch.codigo, exactMatch.nombre, exactMatch.precio_venta, exactMatch.stock);
            return;
        }
        
        // Si no hay coincidencia exacta de código, tomar el único resultado si existe
        if (data.length === 1) {
            const match = data[0];
            agregarProducto(match.id, match.codigo, match.nombre, match.precio_venta, match.stock);
        } else {
            alert(`⚠️ Producto con código "${code}" no encontrado o no tiene stock.`);
        }
    } catch(e) {
        console.error("Error buscando producto: ", e);
    }
}

// Interceptar Enter en buscador de productos para soportar pistola USB y manual
if (inputProducto) {
    inputProducto.addEventListener('keydown', async function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Evita submit del formulario
            const val = this.value.trim();
            if (val.length > 0) {
                await buscarYAgregarProductoPorCodigo(val);
            }
        }
    });
}

// Enfocar automáticamente el input si se escribe fuera de inputs (para pistola USB)
document.addEventListener('keydown', function(e) {
    const active = document.activeElement;
    if (active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.tagName === 'SELECT')) {
        return;
    }
    if (e.key.length === 1 && /^[a-zA-Z0-9]$/.test(e.key)) {
        if (inputProducto) {
            inputProducto.focus();
        }
    }
});
</script>

<!-- Modal para Escáner de Código de Barras -->
<div class="modal fade" id="barcode-scanner-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; overflow:hidden">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-barcode me-2 text-primary"></i> Escáner de Código de Barras</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-black position-relative" style="min-height:320px; display:flex; align-items:center; justify-content:center">
                <!-- Preview element -->
                <div id="scanner-preview" style="width:100%; min-height:320px"></div>
                
                <!-- Scanning line overlay -->
                <div class="scan-line-overlay"></div>
            </div>
            <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius:8px">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
