<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Reporte de Ventas<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<style>
    .card-kpi {
        border-radius: var(--radius-md);
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-kpi:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .dt-buttons .btn {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    .dataTables_filter input {
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        padding: 0.4rem 0.8rem;
        outline: none;
    }
    .dataTables_filter input:focus {
        border-color: var(--primary);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="content-title"><i class="fa-solid fa-chart-column text-primary me-2"></i> Reporte de Ventas</h1>
        <p class="text-muted mb-0">Consolidado y filtros avanzados del historial de ventas facturadas.</p>
    </div>
</div>

<!-- Filtros de Búsqueda -->
<div class="card card-custom mb-4">
    <div class="card-custom-header">
        <h5 class="card-custom-title"><i class="fa-solid fa-filter text-primary me-2"></i> Filtros de Búsqueda</h5>
    </div>
    <div class="card-custom-body">
        <form method="GET" action="<?= base_url('reportes/ventas') ?>" class="row g-3">
            <div class="col-12 col-md-3">
                <label for="fecha_inicio" class="form-label fw-semibold text-muted small">Fecha Inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= esc($fecha_inicio) ?>" style="border-radius:8px">
            </div>
            <div class="col-12 col-md-3">
                <label for="fecha_fin" class="form-label fw-semibold text-muted small">Fecha Fin</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= esc($fecha_fin) ?>" style="border-radius:8px">
            </div>
            <div class="col-12 col-md-3">
                <label for="cliente_id" class="form-label fw-semibold text-muted small">Cliente</label>
                <select class="form-select" id="cliente_id" name="cliente_id" style="border-radius:8px">
                    <option value="">-- Todos los Clientes --</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $cliente_id == $c['id'] ? 'selected' : '' ?>>
                            <?= esc($c['nombres']) ?> (<?= esc($c['numero_documento']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="usuario_id" class="form-label fw-semibold text-muted small">Vendedor</label>
                <select class="form-select" id="usuario_id" name="usuario_id" style="border-radius:8px">
                    <option value="">-- Todos los Vendedores --</option>
                    <?php foreach ($vendedores as $v): ?>
                        <option value="<?= $v['id'] ?>" <?= $usuario_id == $v['id'] ? 'selected' : '' ?>>
                            <?= esc($v['nombres'] . ' ' . $v['apellidos']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="estado" class="form-label fw-semibold text-muted small">Estado de la Venta</label>
                <select class="form-select" id="estado" name="estado" style="border-radius:8px">
                    <option value="">-- Todos los Estados --</option>
                    <option value="1" <?= $estado === '1' ? 'selected' : '' ?>>Activa</option>
                    <option value="0" <?= $estado === '0' ? 'selected' : '' ?>>Anulada</option>
                </select>
            </div>
            <div class="col-12 col-md-9 d-flex align-items-end justify-content-end gap-2">
                <a href="<?= base_url('reportes/ventas') ?>" class="btn btn-light" style="border-radius:8px; padding:.6rem 1.2rem">
                    <i class="fa-solid fa-rotate-left me-1"></i> Limpiar Filtros
                </a>
                <button type="submit" class="btn btn-primary" style="border-radius:8px; padding:.6rem 1.8rem">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Filtrar Reporte
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tarjetas KPI -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6">
        <div class="card card-kpi p-4 h-100 d-flex flex-row align-items-center justify-content-between shadow-sm" style="background: linear-gradient(135deg, #FFF 0%, #F1F5F9 100%)">
            <div>
                <h3 class="text-uppercase text-muted fs-7 fw-bold tracking-wider mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Cantidad de Ventas</h3>
                <div class="fs-2 fw-extrabold text-dark" style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800;"><?= $cantidadVentas ?></div>
                <span class="text-muted small">Transacciones encontradas</span>
            </div>
            <div class="d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: var(--radius-md); background-color: rgba(79, 70, 229, 0.1); color: var(--primary);">
                <i class="fa-solid fa-receipt fs-4"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="card card-kpi p-4 h-100 d-flex flex-row align-items-center justify-content-between shadow-sm" style="background: linear-gradient(135deg, #FFF 0%, #ECFDF5 100%)">
            <div>
                <h3 class="text-uppercase text-muted fs-7 fw-bold tracking-wider mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Total Vendido (Ingresos)</h3>
                <div class="fs-2 fw-extrabold text-success" style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800;">S/ <?= number_format($totalVendido, 2) ?></div>
                <span class="text-success-emphasis small fw-semibold"><i class="fa-solid fa-check-double"></i> Solo ventas activas</span>
            </div>
            <div class="d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: var(--radius-md); background-color: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fa-solid fa-coins fs-4"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Resultados -->
<div class="card card-custom">
    <div class="card-custom-header">
        <h5 class="card-custom-title"><i class="fa-solid fa-list text-primary me-2"></i> Detalle de Ventas</h5>
    </div>
    <div class="card-custom-body p-0">
        <div class="table-responsive p-3">
            <table id="tabla-reporte-ventas" class="table table-hover align-middle mb-0" style="width:100%">
                <thead style="background:#F8FAFC;border-bottom:2px solid #E2E8F0">
                    <tr>
                        <th class="px-3 py-2">N° Venta</th>
                        <th class="px-3 py-2">Fecha</th>
                        <th class="px-3 py-2">Cliente</th>
                        <th class="px-3 py-2">Vendedor</th>
                        <th class="px-3 py-2 text-end">Subtotal</th>
                        <th class="px-3 py-2 text-end">IGV</th>
                        <th class="px-3 py-2 text-end">Total</th>
                        <th class="px-3 py-2 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td><span class="fw-bold text-primary"><?= esc($v['numero_venta']) ?></span></td>
                            <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                            <td>
                                <?php if ($v['cliente_nombre']): ?>
                                    <span class="fw-semibold"><?= esc($v['cliente_nombre']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted small fst-italic">Consumidor Final</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small"><?= esc($v['usuario_nombre']) ?></td>
                            <td class="text-end">S/ <?= number_format($v['subtotal'], 2) ?></td>
                            <td class="text-end text-muted">S/ <?= number_format($v['impuesto'], 2) ?></td>
                            <td class="text-end fw-bold" style="color:var(--primary)">S/ <?= number_format($v['total'], 2) ?></td>
                            <td class="text-center">
                                <?php if ($v['estado'] == 1): ?>
                                    <span class="badge rounded-pill" style="background:rgba(16,185,129,.12);color:#059669;padding:.35rem .75rem;font-weight:600">Activa</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill" style="background:rgba(239,68,68,.12);color:#DC2626;padding:.35rem .75rem;font-weight:600">Anulada</span>
                                <?php endif; ?>
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
<!-- DataTables Buttons and Export Requirements -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabla-reporte-ventas').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json'
            },
            order: [[1, 'desc']],
            responsive: true,
            pageLength: 25,
            dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"Bf>rtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa-solid fa-file-excel me-2"></i> Exportar a Excel',
                    className: 'btn btn-outline-success fw-semibold border-2',
                    title: 'Reporte de Ventas - Kobra POS',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa-solid fa-file-pdf me-2"></i> Exportar a PDF',
                    className: 'btn btn-outline-danger fw-semibold border-2',
                    title: 'Reporte de Ventas - Kobra POS',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    },
                    customize: function (doc) {
                        doc.content[1].table.widths = ['12%', '15%', '20%', '18%', '10%', '8%', '10%', '7%'];
                        doc.styles.tableHeader.fillColor = '#4F46E5';
                        doc.styles.tableHeader.color = '#FFFFFF';
                        doc.defaultStyle.fontSize = 9;
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa-solid fa-print me-2"></i> Imprimir Reporte',
                    className: 'btn btn-outline-primary fw-semibold border-2',
                    title: 'Reporte de Ventas - Kobra POS',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                }
            ]
        });
    });
</script>
<?= $this->endSection() ?>
