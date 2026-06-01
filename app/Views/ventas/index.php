<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Historial de Ventas<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="content-title">Historial de Ventas</h1>
        <p class="text-muted mb-0">Registro completo de ventas y comprobantes emitidos</p>
    </div>
    <?php if (session()->get('rolId') === 1 || in_array('ventas.crear', session()->get('permisos') ?? [])): ?>
    <a href="<?= base_url('ventas/nueva') ?>" class="btn btn-primary d-flex align-items-center gap-2" id="btn-nueva-venta" style="border-radius:10px;padding:.6rem 1.4rem;font-weight:600">
        <i class="fa-solid fa-plus"></i> Nueva Venta
    </a>
    <?php endif; ?>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-circle-check"></i>
        <span><?= esc(session()->getFlashdata('success')) ?></span>
    </div>
    <?php if (session()->getFlashdata('reciente_venta_id')): ?>
        <button class="btn btn-sm btn-light text-dark fw-bold border d-flex align-items-center gap-2" 
                onclick="abrirTicketPrint(<?= session()->getFlashdata('reciente_venta_id') ?>)" 
                style="border-radius:8px;padding:.4rem 1rem">
            <i class="fa-solid fa-print"></i> Imprimir Ticket Reciente
        </button>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
    <i class="fa-solid fa-circle-exclamation"></i> <?= esc(session()->getFlashdata('error')) ?>
</div>
<?php endif; ?>

<!-- Tarjetas métricas -->
<?php
$totalVentas   = count($ventas);
$totalActivas  = count(array_filter($ventas, fn($v) => $v['estado'] == 1));
$totalImporte  = array_sum(array_column(array_filter($ventas, fn($v) => $v['estado'] == 1), 'total'));
$totalAnuladas = $totalVentas - $totalActivas;
?>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-custom p-3 text-center">
            <div class="fw-bold" style="font-size:1.8rem;color:#4F46E5;font-family:'Outfit',sans-serif"><?= $totalVentas ?></div>
            <div class="text-muted small">Total Ventas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom p-3 text-center">
            <div class="fw-bold" style="font-size:1.8rem;color:#10B981;font-family:'Outfit',sans-serif"><?= $totalActivas ?></div>
            <div class="text-muted small">Activas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom p-3 text-center">
            <div class="fw-bold" style="font-size:1.8rem;color:#EF4444;font-family:'Outfit',sans-serif"><?= $totalAnuladas ?></div>
            <div class="text-muted small">Anuladas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-custom p-3 text-center">
            <div class="fw-bold" style="font-size:1.8rem;color:#4F46E5;font-family:'Outfit',sans-serif">S/ <?= number_format($totalImporte, 2) ?></div>
            <div class="text-muted small">Total Recaudado</div>
        </div>
    </div>
</div>

<div class="card card-custom">
    <div class="card-custom-header">
        <h5 class="card-custom-title"><i class="fa-solid fa-file-invoice-dollar me-2 text-primary"></i> Ventas Registradas</h5>
    </div>
    <div class="card-custom-body p-0">
        <div class="table-responsive">
            <table id="tabla-ventas" class="table table-hover mb-0" style="width:100%">
                <thead style="background:#F8FAFC;border-bottom:2px solid #E2E8F0">
                    <tr>
                        <th class="px-4 py-3">N° Venta</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Cliente</th>
                        <th class="px-4 py-3">Usuario</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3 text-end">Subtotal</th>
                        <th class="px-4 py-3 text-end">Total</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td class="px-4 py-3">
                            <span class="fw-bold text-primary" style="font-family:'Outfit',sans-serif;font-size:1rem"><?= esc($v['numero_venta']) ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <?php
                            $colors = ['Boleta'=>'#3B82F6','Factura'=>'#8B5CF6','Ticket'=>'#10B981'];
                            $bgs    = ['Boleta'=>'rgba(59,130,246,.1)','Factura'=>'rgba(139,92,246,.1)','Ticket'=>'rgba(16,185,129,.1)'];
                            $tipo   = $v['tipo_comprobante'];
                            ?>
                            <span class="badge rounded-pill px-3" style="background:<?= $bgs[$tipo] ?? '#F1F5F9' ?>;color:<?= $colors[$tipo] ?? '#64748B' ?>;font-weight:600">
                                <?= esc($tipo) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <?php if ($v['cliente_nombre']): ?>
                                <span class="fw-semibold"><?= esc($v['cliente_nombre']) ?></span>
                            <?php else: ?>
                                <span class="text-muted small fst-italic">Consumidor Final</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-muted small"><?= esc($v['usuario_nombre'] ?? '—') ?></td>
                        <td class="px-4 py-3 text-muted small"><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                        <td class="px-4 py-3 text-end">S/ <?= number_format($v['subtotal'], 2) ?></td>
                        <td class="px-4 py-3 text-end">
                            <span class="fw-bold" style="color:#4F46E5;font-size:1rem">S/ <?= number_format($v['total'], 2) ?></span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <?php if ($v['estado'] == 1): ?>
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
                                <a href="<?= base_url('ventas/ver/' . $v['id']) ?>" class="btn btn-sm btn-outline-primary" title="Ver detalle" style="border-radius:8px;width:34px;height:34px;padding:0;display:flex;align-items:center;justify-content:center">
                                    <i class="fa-solid fa-eye fa-sm"></i>
                                </a>
                                <?php if ($v['estado'] == 1): ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="abrirTicketPrint(<?= $v['id'] ?>)" title="Imprimir ticket" style="border-radius:8px;width:34px;height:34px;padding:0;display:flex;align-items:center;justify-content:center">
                                    <i class="fa-solid fa-print fa-sm"></i>
                                </button>
                                <?php endif; ?>
                                <?php if ($v['estado'] == 1 && (session()->get('rolId') === 1 || in_array('ventas.anular', session()->get('permisos') ?? []))): ?>
                                <a href="<?= base_url('ventas/anular/' . $v['id']) ?>" class="btn btn-sm btn-outline-danger btn-anular-venta" title="Anular venta" data-numero="<?= esc($v['numero_venta']) ?>" style="border-radius:8px;width:34px;height:34px;padding:0;display:flex;align-items:center;justify-content:center">
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
// Función reutilizable para abrir popup de impresión térmica
function abrirTicketPrint(id) {
    const width = 450;
    const height = 650;
    const left = (screen.width / 2) - (width / 2);
    const top = (screen.height / 2) - (height / 2);
    window.open('<?= base_url('ventas/imprimir/') ?>' + id, 'ImprimirTicket', `width=${width},height=${height},left=${left},top=${top},status=no,toolbar=no,menubar=no,location=no`);
}

$(document).ready(function () {
    $('#tabla-ventas').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json' },
        order: [[4, 'desc']],
        columnDefs: [{ orderable: false, targets: [8] }],
        responsive: true,
        pageLength: 15,
    });

    $(document).on('click', '.btn-anular-venta', function(e) {
        e.preventDefault();
        const href   = $(this).attr('href');
        const numero = $(this).data('numero');
        if (confirm(`¿Estás seguro de anular la venta ${numero}?\n\nEsta acción restaurará el stock de todos los productos vendidos.`)) {
            window.location.href = href;
        }
    });
});
</script>
<?= $this->endSection() ?>
