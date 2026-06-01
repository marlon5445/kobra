<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Detalle Venta <?= esc($venta['numero_venta']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= base_url('ventas') ?>" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center">
            <i class="fa-solid fa-arrow-left fa-sm"></i>
        </a>
        <div>
            <h1 class="content-title mb-0">Detalle de Venta</h1>
            <p class="text-muted mb-0 small">Comprobante de venta emitido</p>
        </div>
    </div>
    <?php if ($venta['estado'] == 1): ?>
    <button class="btn btn-outline-secondary d-flex align-items-center gap-2" id="btn-imprimir-ticket" style="border-radius:10px;padding:.6rem 1.4rem;font-weight:600">
        <i class="fa-solid fa-print"></i> Imprimir Ticket
    </button>
    <?php endif; ?>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success d-flex align-items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?>
</div>
<?php endif; ?>

<?php
$colores = ['Boleta'=>'#3B82F6','Factura'=>'#8B5CF6','Ticket'=>'#10B981'];
$bgs     = ['Boleta'=>'rgba(59,130,246,.12)','Factura'=>'rgba(139,92,246,.12)','Ticket'=>'rgba(16,185,129,.12)'];
$tipo    = $venta['tipo_comprobante'];
$colorTipo = $colores[$tipo] ?? '#64748B';
$bgTipo    = $bgs[$tipo]     ?? '#F1F5F9';
?>

<div class="row g-4">
    <div class="col-12 col-lg-8">

        <!-- Cabecera del comprobante -->
        <div class="card card-custom mb-4">
            <div class="card-custom-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                    <div>
                        <div class="text-muted small fw-semibold mb-1">COMPROBANTE DE VENTA</div>
                        <div class="fw-bold" style="font-size:2rem;font-family:'Outfit',sans-serif;color:<?= $colorTipo ?>;line-height:1">
                            <?= esc($venta['numero_venta']) ?>
                        </div>
                        <div class="mt-1 d-flex align-items-center gap-2">
                            <span class="badge rounded-pill px-3 py-1" style="background:<?= $bgTipo ?>;color:<?= $colorTipo ?>;font-size:.85rem;font-weight:600">
                                <?= esc($tipo) ?>
                            </span>
                            <span class="text-muted small"><?= date('d \d\e F \d\e Y, H:i', strtotime($venta['fecha'])) ?></span>
                        </div>
                    </div>
                    <div>
                        <?php if ($venta['estado'] == 1): ?>
                            <span class="badge rounded-pill px-3 py-2" style="background:rgba(16,185,129,.12);color:#059669;font-size:.9rem;font-weight:600">
                                <i class="fa-solid fa-check-circle me-1"></i> Venta Activa
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
                            <div class="text-muted small fw-semibold mb-1"><i class="fa-solid fa-user me-1"></i> CLIENTE</div>
                            <?php if (!empty($venta['cliente_nombre'])): ?>
                                <div class="fw-bold"><?= esc($venta['cliente_nombre']) ?></div>
                            <?php else: ?>
                                <div class="fw-semibold text-muted fst-italic">Consumidor Final</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3" style="background:#F8FAFC;border:1px solid #E2E8F0">
                            <div class="text-muted small fw-semibold mb-1"><i class="fa-solid fa-user-tie me-1"></i> VENDEDOR</div>
                            <div class="fw-bold"><?= esc($venta['usuario_nombre'] ?? '—') ?></div>
                        </div>
                    </div>
                    <?php if (!empty($venta['observaciones'])): ?>
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:#FFFBEB;border:1px solid #FDE68A">
                            <div class="text-muted small fw-semibold mb-1"><i class="fa-solid fa-note-sticky me-1"></i> OBSERVACIONES</div>
                            <div><?= esc($venta['observaciones']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tabla de productos vendidos -->
        <div class="card card-custom">
            <div class="card-custom-header">
                <h5 class="card-custom-title"><i class="fa-solid fa-list me-2 text-primary"></i> Productos Vendidos</h5>
            </div>
            <div class="card-custom-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead style="background:#F8FAFC">
                            <tr>
                                <th class="px-4 py-3 text-muted small fw-semibold">#</th>
                                <th class="px-4 py-3 text-muted small fw-semibold">Código</th>
                                <th class="px-4 py-3 text-muted small fw-semibold">Producto</th>
                                <th class="px-4 py-3 text-center text-muted small fw-semibold">Cant.</th>
                                <th class="px-4 py-3 text-end text-muted small fw-semibold">Precio Unit.</th>
                                <th class="px-4 py-3 text-end text-muted small fw-semibold">Descuento</th>
                                <th class="px-4 py-3 text-end text-muted small fw-semibold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $n => $item): ?>
                            <tr>
                                <td class="px-4 py-3 text-muted"><?= $n + 1 ?></td>
                                <td class="px-4 py-3"><code class="fw-bold" style="color:<?= $colorTipo ?>"><?= esc($item['producto_codigo']) ?></code></td>
                                <td class="px-4 py-3 fw-semibold"><?= esc($item['producto_nombre']) ?></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="badge rounded-pill px-3" style="background:<?= $bgTipo ?>;color:<?= $colorTipo ?>;font-weight:700">
                                        <?= $item['cantidad'] ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end">S/ <?= number_format($item['precio_unitario'], 2) ?></td>
                                <td class="px-4 py-3 text-end">
                                    <?php if ($item['descuento'] > 0): ?>
                                        <span class="text-danger fw-semibold">- S/ <?= number_format($item['descuento'], 2) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
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
                <h5 class="card-custom-title"><i class="fa-solid fa-receipt me-2 text-primary"></i> Resumen</h5>
            </div>
            <div class="card-custom-body">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Subtotal ítems</span>
                    <span class="fw-semibold">S/ <?= number_format($venta['subtotal'], 2) ?></span>
                </div>

                <?php if ($venta['descuento'] > 0): ?>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Descuento global</span>
                    <span class="fw-semibold text-danger">- S/ <?= number_format($venta['descuento'], 2) ?></span>
                </div>
                <?php endif; ?>

                <?php if ($venta['impuesto'] > 0): ?>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">
                        IGV (18%)
                        <span class="badge bg-light text-dark border ms-1" style="font-size:.7rem">Factura</span>
                    </span>
                    <span class="fw-semibold text-success">S/ <?= number_format($venta['impuesto'], 2) ?></span>
                </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between py-3 mt-1">
                    <span class="fw-bold fs-5">Total</span>
                    <span class="fw-bold fs-4" style="color:<?= $colorTipo ?>">S/ <?= number_format($venta['total'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Info tipo de comprobante -->
        <div class="card card-custom mb-4" style="border-left:4px solid <?= $colorTipo ?>">
            <div class="card-custom-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:44px;height:44px;background:<?= $bgTipo ?>">
                        <i class="fa-solid fa-file-invoice" style="color:<?= $colorTipo ?>"></i>
                    </div>
                    <div>
                        <div class="fw-bold"><?= esc($tipo) ?></div>
                        <div class="text-muted small">
                            <?php if ($tipo === 'Factura'): ?>
                                Incluye IGV 18% calculado sobre el subtotal
                            <?php elseif ($tipo === 'Boleta'): ?>
                                Sin IGV separado — precio ya incluye impuesto
                            <?php else: ?>
                                Comprobante simplificado sin IGV separado
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <?php if ($venta['estado'] == 1 && (session()->get('rolId') === 1 || in_array('ventas.anular', session()->get('permisos') ?? []))): ?>
        <div class="card card-custom" style="border:1px solid #FCA5A5">
            <div class="card-custom-body">
                <h6 class="fw-bold text-danger mb-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> Zona de Peligro</h6>
                <p class="text-muted small mb-3">Anular esta venta restaurará el stock de todos los productos vendidos en ella.</p>
                <a href="<?= base_url('ventas/anular/' . $venta['id']) ?>"
                   id="btn-anular-venta"
                   class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2"
                   style="border-radius:10px;font-weight:600">
                    <i class="fa-solid fa-ban"></i> Anular Venta
                </a>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('btn-imprimir-ticket')?.addEventListener('click', function() {
    const width = 450;
    const height = 650;
    const left = (screen.width / 2) - (width / 2);
    const top = (screen.height / 2) - (height / 2);
    window.open('<?= base_url('ventas/imprimir/' . $venta['id']) ?>', 'ImprimirTicket', `width=${width},height=${height},left=${left},top=${top},status=no,toolbar=no,menubar=no,location=no`);
});

document.getElementById('btn-anular-venta')?.addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('¿Estás seguro de anular la venta <?= esc($venta['numero_venta']) ?>?\n\nEsta acción restaurará el stock de todos los productos incluidos.')) {
        window.location.href = this.href;
    }
});
</script>
<?= $this->endSection() ?>
