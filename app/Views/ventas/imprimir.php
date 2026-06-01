<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Ticket - <?= esc($venta['numero_venta']) ?></title>
    
    <!-- Google Fonts for premium clean reading (Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome v6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --ticket-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --border-color: #E2E8F0;
            --text-color: #1E293B;
            --muted-color: #64748B;
        }
        
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #F1F5F9;
            color: var(--text-color);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        /* Top control bar (Not printed) */
        .control-bar {
            width: 100%;
            background-color: #0F172A;
            color: #FFF;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .control-title {
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .control-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-control {
            background-color: #334155;
            color: #FFF;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-control:hover {
            background-color: #475569;
        }

        .btn-print {
            background-color: #4F46E5;
        }
        .btn-print:hover {
            background-color: #4338CA;
        }

        .btn-close-window {
            background-color: #EF4444;
        }
        .btn-close-window:hover {
            background-color: #DC2626;
        }

        .width-selector {
            display: flex;
            background: #1E293B;
            padding: 3px;
            border-radius: 8px;
            border: 1px solid #334155;
        }

        .width-opt {
            padding: 5px 12px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #94A3B8;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .width-opt.active {
            background: #4F46E5;
            color: #FFF;
        }

        /* Printable Ticket Container */
        .ticket-wrapper {
            background-color: #FFF;
            padding: 24px;
            margin: 30px auto;
            border-radius: 12px;
            box-shadow: var(--ticket-shadow);
            border: 1px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Ticket Size Presets */
        .ticket-80mm {
            width: 80mm;
            max-width: 80mm;
        }
        .ticket-58mm {
            width: 58mm;
            max-width: 58mm;
            padding: 12px;
            font-size: 0.82rem;
        }

        /* Ticket Content Styling */
        .ticket-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .ticket-logo {
            max-width: 65%;
            max-height: 60px;
            object-fit: contain;
            margin-bottom: 10px;
            filter: grayscale(100%); /* Thermal printers are black & white */
        }

        .company-name {
            font-size: 1.15rem;
            font-weight: 800;
            margin: 0 0 3px 0;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .company-legal {
            font-size: 0.85rem;
            color: var(--muted-color);
            margin: 0 0 6px 0;
        }

        .company-info-text {
            font-size: 0.78rem;
            color: var(--muted-color);
            margin: 0 0 2px 0;
            line-height: 1.35;
        }

        .divider {
            border-top: 1px dashed #94A3B8;
            margin: 12px 0;
            width: 100%;
        }

        /* Info meta sections */
        .ticket-meta {
            font-size: 0.78rem;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .meta-label {
            font-weight: 500;
            color: var(--muted-color);
        }

        .meta-val {
            font-weight: 600;
            text-align: right;
        }

        /* Products Table */
        .ticket-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.78rem;
            margin-bottom: 12px;
        }

        .ticket-table th {
            border-bottom: 1px solid #000;
            font-weight: 700;
            padding: 4px 0;
            text-align: left;
            text-transform: uppercase;
            font-size: 0.72rem;
        }

        .ticket-table td {
            padding: 5px 0;
            vertical-align: top;
            border-bottom: 1px dashed #F1F5F9;
        }

        .col-qty {
            width: 12%;
            text-align: center;
        }
        .col-desc {
            width: 53%;
            padding-left: 4px;
        }
        .col-price {
            width: 17%;
            text-align: right;
        }
        .col-total {
            width: 18%;
            text-align: right;
            font-weight: 600;
        }

        .item-discount {
            font-size: 0.7rem;
            color: #DC2626;
            display: block;
            margin-top: 1px;
            font-weight: 500;
        }

        /* Financial Summary */
        .ticket-summary {
            font-size: 0.78rem;
            margin-bottom: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }

        .summary-total {
            font-size: 1.05rem;
            font-weight: 800;
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 4px;
        }

        /* Ticket Footer */
        .ticket-footer {
            text-align: center;
            font-size: 0.75rem;
            color: var(--muted-color);
            margin-top: 15px;
            line-height: 1.4;
        }

        /* Print Specifics overrides */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: #FFF !important;
                color: #000 !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                min-height: auto !important;
            }

            .ticket-wrapper {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                border-radius: 0 !important;
                background-color: #FFF !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            
            /* Apply custom padding based on class during actual print */
            .ticket-58mm {
                padding: 4px !important;
                font-size: 10px !important;
            }
            
            .ticket-58mm th {
                font-size: 9px !important;
            }
            
            .ticket-58mm td {
                font-size: 9px !important;
            }
            
            .ticket-58mm .item-discount {
                font-size: 8px !important;
            }

            .ticket-58mm .company-name {
                font-size: 1rem !important;
            }

            .ticket-80mm {
                padding: 10px !important;
            }
        }
    </style>
</head>
<body>

    <!-- CONTROL TOOLBAR (No se imprime) -->
    <div class="control-bar no-print">
        <h1 class="control-title">
            <i class="fa-solid fa-receipt text-primary"></i> Impresión de Comprobante
        </h1>
        
        <div class="control-actions">
            <!-- Selector de Bobina de Impresora -->
            <div class="width-selector" title="Seleccionar tamaño de papel térmico">
                <div class="width-opt active" id="opt-80" onclick="setTicketWidth(80)">80mm</div>
                <div class="width-opt" id="opt-58" onclick="setTicketWidth(58)">58mm</div>
            </div>
            
            <!-- Botón Imprimir -->
            <button class="btn-control btn-print" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Imprimir (Ctrl+P)
            </button>
            
            <!-- Botón Cerrar pestaña -->
            <button class="btn-control btn-close-window" onclick="window.close()">
                <i class="fa-solid fa-xmark"></i> Cerrar
            </button>
        </div>
    </div>

    <!-- TICKET DE VENTA (Imprimible) -->
    <div class="ticket-wrapper ticket-80mm" id="ticket-area">
        
        <!-- Cabecera del ticket -->
        <div class="ticket-header">
            <!-- Logo si existe y archivo existe físicamente -->
            <?php if (!empty($configuracion['logo']) && file_exists(ROOTPATH . 'public/uploads/logo/' . $configuracion['logo'])): ?>
                <img src="<?= base_url('uploads/logo/' . $configuracion['logo']) ?>" class="ticket-logo" alt="Logo">
            <?php endif; ?>
            
            <!-- Datos de empresa -->
            <h2 class="company-name"><?= esc($configuracion['nombre_comercial'] ?: $configuracion['razon_social']) ?></h2>
            <?php if ($configuracion['nombre_comercial']): ?>
                <div class="company-legal"><?= esc($configuracion['razon_social']) ?></div>
            <?php endif; ?>
            
            <div class="company-info-text"><strong>RUC:</strong> <?= esc($configuracion['ruc']) ?></div>
            <?php if ($configuracion['direccion']): ?>
                <div class="company-info-text"><?= esc($configuracion['direccion']) ?></div>
            <?php endif; ?>
            <?php if ($configuracion['telefono'] || $configuracion['correo']): ?>
                <div class="company-info-text">
                    <?= $configuracion['telefono'] ? 'Tlf: ' . esc($configuracion['telefono']) : '' ?>
                    <?= $configuracion['telefono'] && $configuracion['correo'] ? ' | ' : '' ?>
                    <?= $configuracion['correo'] ? esc($configuracion['correo']) : '' ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="divider"></div>

        <!-- Metadatos de la venta -->
        <div class="ticket-meta">
            <div class="meta-row">
                <span class="meta-label">Comprobante:</span>
                <span class="meta-val"><?= esc(strtoupper($venta['tipo_comprobante'])) ?> <?= esc($venta['numero_venta']) ?></span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Fecha Emisión:</span>
                <span class="meta-val"><?= date('d/m/Y H:i:s', strtotime($venta['fecha'])) ?></span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Cajero/Vendedor:</span>
                <span class="meta-val"><?= esc($venta['usuario_nombre'] ?? 'Sistema') ?></span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Cliente:</span>
                <span class="meta-val"><?= esc($venta['cliente_nombre'] ?: 'Consumidor Final') ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Listado de Productos -->
        <table class="ticket-table">
            <thead>
                <tr>
                    <th class="col-qty">Cant</th>
                    <th class="col-desc">Descripción</th>
                    <th class="col-price">P.Unit</th>
                    <th class="col-total">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="col-qty"><?= $item['cantidad'] ?></td>
                        <td class="col-desc">
                            <?= esc($item['producto_nombre']) ?>
                            <?php if ($item['descuento'] > 0): ?>
                                <span class="item-discount">Desc: -<?= esc($configuracion['simbolo_moneda']) ?> <?= number_format($item['descuento'], 2) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="col-price"><?= number_format($item['precio_unitario'], 2) ?></td>
                        <td class="col-total"><?= number_format($item['subtotal'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="divider"></div>

        <!-- Desglose de Totales -->
        <div class="ticket-summary">
            <!-- Subtotal -->
            <div class="summary-row">
                <span>Subtotal:</span>
                <span><?= esc($configuracion['simbolo_moneda']) ?> <?= number_format($venta['subtotal'], 2) ?></span>
            </div>
            
            <!-- Descuento global -->
            <?php if ($venta['descuento'] > 0): ?>
                <div class="summary-row" style="color:#DC2626">
                    <span>Descuento:</span>
                    <span>-<?= esc($configuracion['simbolo_moneda']) ?> <?= number_format($venta['descuento'], 2) ?></span>
                </div>
            <?php endif; ?>

            <!-- IGV 18% (Solo en facturas por normatividad peruana) -->
            <?php if ($venta['impuesto'] > 0): ?>
                <div class="summary-row">
                    <span>IGV (18%):</span>
                    <span><?= esc($configuracion['simbolo_moneda']) ?> <?= number_format($venta['impuesto'], 2) ?></span>
                </div>
            <?php endif; ?>

            <!-- Total General -->
            <div class="summary-row summary-total">
                <span>TOTAL:</span>
                <span><?= esc($configuracion['simbolo_moneda']) ?> <?= number_format($venta['total'], 2) ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Mensaje del pie -->
        <?php if (!empty($configuracion['mensaje_ticket'])): ?>
            <div class="ticket-footer">
                <?= nl2br(esc($configuracion['mensaje_ticket'])) ?>
            </div>
        <?php endif; ?>

    </div>

    <!-- Scripts para control interactivo y autodisparo -->
    <script>
        // Cambiar tamaño de ticket en pantalla
        function setTicketWidth(width) {
            const ticketArea = document.getElementById('ticket-area');
            const opt80 = document.getElementById('opt-80');
            const opt58 = document.getElementById('opt-58');

            if (width === 58) {
                ticketArea.classList.remove('ticket-80mm');
                ticketArea.classList.add('ticket-58mm');
                opt80.classList.remove('active');
                opt58.classList.add('active');
            } else {
                ticketArea.classList.remove('ticket-58mm');
                ticketArea.classList.add('ticket-80mm');
                opt58.classList.remove('active');
                opt80.classList.add('active');
            }
        }
    </script>
</body>
</html>
