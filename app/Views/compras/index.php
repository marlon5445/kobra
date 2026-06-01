<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Historial de Compras<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="content-title">Historial de Compras</h1>
        <p class="text-muted mb-0">Registro completo de órdenes de compra a proveedores</p>
    </div>
    <?php if (session()->get('rolId') === 1 || in_array('compras.crear', session()->get('permisos') ?? [])): ?>
    <a href="<?= base_url('compras/nueva') ?>" class="btn btn-primary d-flex align-items-center gap-2" id="btn-nueva-compra" style="border-radius:10px;padding:.6rem 1.4rem;font-weight:600">
        <i class="fa-solid fa-plus"></i> Nueva Compra
    </a>
    <?php endif; ?>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success d-flex align-items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-exclamation"></i> <?= esc(session()->getFlashdata('error')) ?>
</div>
<?php endif; ?>

<div class="card card-custom">
    <div class="card-custom-header">
        <h5 class="card-custom-title"><i class="fa-solid fa-cart-shopping me-2 text-primary"></i> Compras Registradas</h5>
    </div>
    <div class="card-custom-body p-0">
        <div class="table-responsive">
            <table id="tabla-compras" class="table table-hover mb-0" style="width:100%">
                <thead style="background:#F8FAFC;border-bottom:2px solid #E2E8F0">
                    <tr>
                        <th class="px-4 py-3">N° Compra</th>
                        <th class="px-4 py-3">Proveedor</th>
                        <th class="px-4 py-3">Registrado por</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3 text-end">Subtotal</th>
                        <th class="px-4 py-3 text-end">IGV</th>
                        <th class="px-4 py-3 text-end">Total</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($compras as $c): ?>
                    <tr>
                        <td class="px-4 py-3">
                            <span class="fw-bold text-primary" style="font-family:'Outfit',sans-serif;font-size:1rem"><?= esc($c['numero_compra']) ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:34px;height:34px;background:rgba(79,70,229,.1);color:#4F46E5;font-weight:700;font-size:.8rem">
                                    <?= mb_strtoupper(mb_substr($c['proveedor_nombre'] ?? '?', 0, 2)) ?>
                                </div>
                                <span class="fw-semibold"><?= esc($c['proveedor_nombre'] ?? '—') ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-muted small"><?= esc($c['usuario_nombre'] ?? '—') ?></td>
                        <td class="px-4 py-3 text-muted small"><?= date('d/m/Y H:i', strtotime($c['fecha'])) ?></td>
                        <td class="px-4 py-3 text-end fw-semibold">S/ <?= number_format($c['subtotal'], 2) ?></td>
                        <td class="px-4 py-3 text-end text-muted small">S/ <?= number_format($c['impuesto'], 2) ?></td>
                        <td class="px-4 py-3 text-end">
                            <span class="fw-bold" style="color:#4F46E5;font-size:1rem">S/ <?= number_format($c['total'], 2) ?></span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <?php if ($c['estado'] == 1): ?>
                                <span class="badge rounded-pill" style="background:rgba(16,185,129,.12);color:#059669;padding:.4rem .85rem;font-weight:600">
                                    <i class="fa-solid fa-check-circle me-1"></i> Activa
                                </span>
                            <?php else: ?>
                                <span class="badge rounded-pill" style="background:rgba(239,68,68,.12);color:#DC2626;padding:.4rem .85rem;font-weight:600">
                                    <i class="fa-solid fa-ban me-1"></i> Anulada
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <a href="<?= base_url('compras/ver/' . $c['id']) ?>" class="btn btn-sm btn-outline-primary" title="Ver detalle" style="border-radius:8px;width:34px;height:34px;padding:0;display:flex;align-items:center;justify-content:center">
                                    <i class="fa-solid fa-eye fa-sm"></i>
                                </a>
                                <?php if ($c['estado'] == 1 && (session()->get('rolId') === 1 || in_array('compras.anular', session()->get('permisos') ?? []))): ?>
                                <a href="<?= base_url('compras/anular/' . $c['id']) ?>" class="btn btn-sm btn-outline-danger btn-anular-compra" title="Anular compra" data-numero="<?= esc($c['numero_compra']) ?>" style="border-radius:8px;width:34px;height:34px;padding:0;display:flex;align-items:center;justify-content:center">
                                    <i class="fa-solid fa-ban fa-sm"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {
    $('#tabla-compras').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json' },
        order: [[3, 'desc']],
        columnDefs: [{ orderable: false, targets: [8] }],
        responsive: true,
        pageLength: 15,
    });

    // Confirmación de anulación
    $(document).on('click', '.btn-anular-compra', function(e) {
        e.preventDefault();
        const href   = $(this).attr('href');
        const numero = $(this).data('numero');
        if (confirm(`¿Estás seguro de anular la compra ${numero}?\n\nEsta acción revertirá el stock de todos los productos de esta compra.`)) {
            window.location.href = href;
        }
    });
});
</script>
<?= $this->endSection() ?>
