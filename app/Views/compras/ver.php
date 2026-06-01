<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Detalle Compra <?= esc($compra['numero_compra']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-header d-flex align-items-center gap-3 mb-4">
    <a href="<?= base_url('compras') ?>" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center">
        <i class="fa-solid fa-arrow-left fa-sm"></i>
    </a>
    <div>
        <h1 class="content-title mb-0">Detalle de Compra</h1>
        <p class="text-muted mb-0 small">Comprobante de orden de compra registrada</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success d-flex align-items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?>
</div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-12 col-lg-8">

        <!-- Cabecera del comprobante -->
        <div class="card card-custom mb-4">
            <div class="card-custom-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                    <div>
                        <div class="text-muted small fw-semibold mb-1">ORDEN DE COMPRA</div>
                        <div class="fw-bold" style="font-size:2rem;font-family:'Outfit',sans-serif;color:#4F46E5;line-height:1"><?= esc($compra['numero_compra']) ?></div>
                        <div class="text-muted small mt-1"><?= date('d \d\e F \d\e Y, H:i', strtotime($compra['fecha'])) ?></div>
                    </div>
                    <div>
                        <?php if ($compra['estado'] == 1): ?>
                            <span class="badge rounded-pill px-3 py-2" style="background:rgba(16,185,129,.12);color:#059669;font-size:.9rem;font-weight:600">
                                <i class="fa-solid fa-check-circle me-1"></i> Compra Activa
                            </span>
                        <?php else: ?>
                            <span class="badge rounded-pill px-3 py-2" style="background:rgba(239,68,68,.12);color:#DC2626;font-size:.9rem;font-weight:600">
                                <i class="fa-solid fa-ban me-1"></i> Anulada
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3" style="background:#F8FAFC;border:1px solid #E2E8F0">
                            <div class="text-muted small fw-semibold mb-1"><i class="fa-solid fa-truck me-1"></i> PROVEEDOR</div>
                            <div class="fw-bold"><?= esc($compra['proveedor_nombre'] ?? '—') ?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3" style="background:#F8FAFC;border:1px solid #E2E8F0">
                            <div class="text-muted small fw-semibold mb-1"><i class="fa-solid fa-user me-1"></i> REGISTRADO POR</div>
                            <div class="fw-bold"><?= esc($compra['usuario_nombre'] ?? '—') ?></div>
                        </div>
                    </div>
                    <?php if (!empty($compra['observaciones'])): ?>
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:#FFFBEB;border:1px solid #FDE68A">
                            <div class="text-muted small fw-semibold mb-1"><i class="fa-solid fa-note-sticky me-1"></i> OBSERVACIONES</div>
                            <div><?= esc($compra['observaciones']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tabla de ítems -->
        <div class="card card-custom">
            <div class="card-custom-header">
                <h5 class="card-custom-title"><i class="fa-solid fa-list me-2 text-primary"></i> Productos Comprados</h5>
            </div>
            <div class="card-custom-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead style="background:#F8FAFC">
                            <tr>
                                <th class="px-4 py-3 text-muted small fw-semibold">#</th>
                                <th class="px-4 py-3 text-muted small fw-semibold">Código</th>
                                <th class="px-4 py-3 text-muted small fw-semibold">Producto</th>
                                <th class="px-4 py-3 text-center text-muted small fw-semibold">Cantidad</th>
                                <th class="px-4 py-3 text-end text-muted small fw-semibold">Costo Unit.</th>
                                <th class="px-4 py-3 text-end text-muted small fw-semibold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $n => $item): ?>
                            <tr>
                                <td class="px-4 py-3 text-muted"><?= $n + 1 ?></td>
                                <td class="px-4 py-3"><code class="fw-bold text-primary"><?= esc($item['producto_codigo']) ?></code></td>
                                <td class="px-4 py-3 fw-semibold"><?= esc($item['producto_nombre']) ?></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="badge rounded-pill px-3" style="background:rgba(79,70,229,.1);color:#4F46E5;font-weight:700"><?= $item['cantidad'] ?></span>
                                </td>
                                <td class="px-4 py-3 text-end">S/ <?= number_format($item['costo_unitario'], 2) ?></td>
                                <td class="px-4 py-3 text-end fw-bold">S/ <?= number_format($item['subtotal'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Panel lateral -->
    <div class="col-12 col-lg-4">
        <!-- Totales -->
        <div class="card card-custom mb-4">
            <div class="card-custom-header">
                <h5 class="card-custom-title"><i class="fa-solid fa-receipt me-2 text-primary"></i> Totales</h5>
            </div>
            <div class="card-custom-body">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-semibold">S/ <?= number_format($compra['subtotal'], 2) ?></span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">IGV (18%)</span>
                    <span class="fw-semibold">S/ <?= number_format($compra['impuesto'], 2) ?></span>
                </div>
                <div class="d-flex justify-content-between py-3 mt-1">
                    <span class="fw-bold fs-5">Total Pagado</span>
                    <span class="fw-bold fs-5" style="color:#4F46E5">S/ <?= number_format($compra['total'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <?php if ($compra['estado'] == 1 && (session()->get('rolId') === 1 || in_array('compras.anular', session()->get('permisos') ?? []))): ?>
        <div class="card card-custom border-danger" style="border:1px solid #FCA5A5">
            <div class="card-custom-body">
                <h6 class="fw-bold text-danger mb-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> Zona de Peligro</h6>
                <p class="text-muted small mb-3">Anular esta compra revertirá el stock de todos los productos registrados en ella.</p>
                <a href="<?= base_url('compras/anular/' . $compra['id']) ?>"
                   id="btn-anular-compra"
                   class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2"
                   style="border-radius:10px;font-weight:600">
                    <i class="fa-solid fa-ban"></i> Anular Compra
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('btn-anular-compra')?.addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('¿Estás seguro de anular la compra <?= esc($compra['numero_compra']) ?>?\n\nEsta acción revertirá el stock de todos los productos incluidos.')) {
        window.location.href = this.href;
    }
});
</script>
<?= $this->endSection() ?>
