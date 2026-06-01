<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Kobra POS</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome v6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap v5.3.0 CSS (Moderna, Premium) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- CSS Personalizado - Diseño ERP/POS Moderno -->
    <style>
        :root {
            --bg-body: #F8FAFC;
            --bg-sidebar: #0F172A;
            --bg-sidebar-hover: #1E293B;
            --sidebar-active: #4F46E5;
            --sidebar-width: 260px;
            --navbar-height: 70px;
            
            --primary: #4F46E5;
            --primary-light: rgba(79, 70, 229, 0.1);
            --primary-glow: rgba(79, 70, 229, 0.25);
            --secondary: #64748B;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #06B6D4;
            
            --text-dark: #1E293B;
            --text-light: #F8FAFC;
            --text-muted: #64748B;
            
            --radius-xl: 16px;
            --radius-lg: 12px;
            --radius-md: 8px;
            
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-sidebar);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.15);
        }

        .sidebar-brand {
            height: var(--navbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-brand-link {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-light);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            letter-spacing: 0.05em;
        }

        .sidebar-brand-link i {
            background: linear-gradient(135deg, var(--sidebar-active) 0%, #06b6d4 100%);
            padding: 0.5rem;
            border-radius: var(--radius-md);
            color: #fff;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .sidebar-menu {
            list-style: none;
            padding: 1.5rem 0.75rem;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1;
        }

        .sidebar-item-header {
            font-family: 'Outfit', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.75rem 0.75rem 0.25rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem 1rem;
            color: #94A3B8;
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .sidebar-link:hover {
            color: #FFF;
            background-color: var(--bg-sidebar-hover);
        }

        .sidebar-link.active {
            color: #FFF;
            background-color: var(--sidebar-active);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            font-weight: 600;
        }

        .sidebar-link i {
            font-size: 1.15rem;
            width: 24px;
            display: flex;
            justify-content: center;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.8rem;
            color: #64748B;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Main Content Wrapper */
        .wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }

        /* Top Navbar */
        .top-navbar {
            height: var(--navbar-height);
            background-color: #FFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .btn-toggle-sidebar {
            background: none;
            border: none;
            color: var(--text-dark);
            font-size: 1.25rem;
            cursor: pointer;
            display: none;
            padding: 0.5rem;
            border-radius: var(--radius-md);
            transition: var(--transition);
        }

        .btn-toggle-sidebar:hover {
            background-color: #F1F5F9;
        }

        .top-navbar-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            transition: var(--transition);
        }

        .user-profile:hover {
            background-color: #F1F5F9;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            border: 2px solid #FFF;
            box-shadow: 0 0 0 2px var(--primary);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Page Content */
        .page-content {
            padding: 2rem;
            flex: 1;
        }

        .page-header {
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-top: 0.25rem;
        }

        /* Card Styles Premium */
        .card-custom {
            background-color: #FFF;
            border: 1px solid #E2E8F0;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .card-custom:hover {
            box-shadow: var(--shadow-md);
        }

        .card-custom-header {
            padding: 1.5rem 1.75rem;
            border-bottom: 1px solid #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: transparent;
        }

        .card-custom-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-dark);
        }

        .card-custom-body {
            padding: 1.75rem;
        }

        /* Footer */
        .footer-admin {
            background-color: #FFF;
            border-top: 1px solid #E2E8F0;
            padding: 1.25rem 2rem;
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.active {
                left: 0;
            }
            .wrapper {
                margin-left: 0;
                width: 100%;
            }
            .btn-toggle-sidebar {
                display: block;
            }
        }

        /* Alert Styling overrides */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }
    </style>
    
    <!-- Renderizador de estilos complementarios -->
    <?= $this->renderSection('styles') ?>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <a href="<?= base_url('dashboard') ?>" class="sidebar-brand-link">
                <i class="fa-solid fa-crown"></i>
                <span>KOBRA <span style="font-weight: 300; font-size: 1.1rem; color: #94a3b8">POS</span></span>
            </a>
        </div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-item-header">Principal</li>
            <li>
                <a href="<?= base_url('dashboard') ?>" class="sidebar-link <?= (url_is('dashboard') || url_is('/')) ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <?php 
            $rolId    = (int)session()->get('rolId');
            $rolNombre = session()->get('rol_nombre') ?? '';
            $permisos = session()->get('permisos') ?? [];
            $esAdmin    = ($rolId === 1 || $rolNombre === 'Administrador');
            $hasCategorias  = ($esAdmin || in_array('categorias.ver',  $permisos));
            $hasProductos   = ($esAdmin || in_array('productos.ver',   $permisos));
            $hasClientes    = ($esAdmin || in_array('clientes.ver',    $permisos));
            $hasProveedores = ($esAdmin || in_array('proveedores.ver', $permisos));
            $hasCompras     = ($esAdmin || in_array('compras.ver',     $permisos));
            $hasVentas      = ($esAdmin || in_array('ventas.ver',      $permisos));
            $hasUsuarios    = ($esAdmin || in_array('usuarios.ver',    $permisos));
            $hasRoles       = ($esAdmin || in_array('roles.ver',       $permisos));
            $hasConfiguracion = ($esAdmin || in_array('configuracion.ver', $permisos));
            $hasReportesVentas  = ($esAdmin || in_array('reportes.ventas',  $permisos));
            $hasReportesCompras = ($esAdmin || in_array('reportes.compras', $permisos));
            ?>

            <?php if ($hasCategorias || $hasProductos || $hasClientes || $hasProveedores): ?>
                <li class="sidebar-item-header">Módulos ERP</li>
                <?php if ($hasCategorias): ?>
                <li>
                    <a href="<?= base_url('categorias') ?>" class="sidebar-link <?= url_is('categorias*') ? 'active' : '' ?>">
                        <i class="fa-solid fa-tags"></i>
                        <span>Categorías</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($hasProductos): ?>
                <li>
                    <a href="<?= base_url('productos') ?>" class="sidebar-link <?= url_is('productos*') ? 'active' : '' ?>">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span>Productos</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($hasClientes): ?>
                <li>
                    <a href="<?= base_url('clientes') ?>" class="sidebar-link <?= url_is('clientes*') ? 'active' : '' ?>">
                        <i class="fa-solid fa-address-book"></i>
                        <span>Clientes</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($hasProveedores): ?>
                <li>
                    <a href="<?= base_url('proveedores') ?>" class="sidebar-link <?= url_is('proveedores*') ? 'active' : '' ?>">
                        <i class="fa-solid fa-handshake"></i>
                        <span>Proveedores</span>
                    </a>
                </li>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($hasCompras || $hasVentas): ?>
                <li class="sidebar-item-header">Operaciones POS</li>
                <?php if ($hasVentas): ?>
                <li>
                    <a href="<?= base_url('ventas') ?>" class="sidebar-link <?= url_is('ventas*') ? 'active' : '' ?>">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span>Ventas</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($hasCompras): ?>
                <li>
                    <a href="<?= base_url('compras') ?>" class="sidebar-link <?= url_is('compras*') ? 'active' : '' ?>">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Compras</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php endif; ?>

            <?php if ($hasReportesVentas || $hasReportesCompras): ?>
                <li class="sidebar-item-header">Reportes y Estadísticas</li>
                <li>
                    <a href="#submenu-reportes" data-bs-toggle="collapse" class="sidebar-link <?= url_is('reportes*') ? 'active' : '' ?> d-flex justify-content-between align-items-center" aria-expanded="<?= url_is('reportes*') ? 'true' : 'false' ?>">
                        <span class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-chart-column"></i>
                            <span>Reportes</span>
                        </span>
                        <i class="fa-solid fa-chevron-down" style="font-size:0.75rem"></i>
                    </a>
                    <ul class="collapse list-unstyled ps-3 <?= url_is('reportes*') ? 'show' : '' ?>" id="submenu-reportes">
                        <?php if ($hasReportesVentas): ?>
                        <li>
                            <a href="<?= base_url('reportes/ventas') ?>" class="sidebar-link py-1 <?= url_is('reportes/ventas') ? 'active' : '' ?>" style="font-size:0.85rem">
                                <i class="fa-solid fa-circle-dot fs-9 me-2" style="font-size:0.5rem"></i> Reporte Ventas
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ($hasReportesCompras): ?>
                        <li>
                            <a href="<?= base_url('reportes/compras') ?>" class="sidebar-link py-1 <?= url_is('reportes/compras') ? 'active' : '' ?>" style="font-size:0.85rem">
                                <i class="fa-solid fa-circle-dot fs-9 me-2" style="font-size:0.5rem"></i> Reporte Compras
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if ($hasUsuarios || $hasRoles || $hasConfiguracion): ?>
                <li class="sidebar-item-header">Administración</li>
                <?php if ($hasUsuarios): ?>
                <li>
                    <a href="<?= base_url('usuarios') ?>" class="sidebar-link <?= url_is('usuarios*') ? 'active' : '' ?>">
                        <i class="fa-solid fa-users"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($hasRoles): ?>
                <li>
                    <a href="<?= base_url('roles') ?>" class="sidebar-link <?= url_is('roles*') ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-shield"></i>
                        <span>Roles y Permisos</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($hasConfiguracion): ?>
                <li>
                    <a href="<?= base_url('configuracion') ?>" class="sidebar-link <?= url_is('configuracion*') ? 'active' : '' ?>">
                        <i class="fa-solid fa-gears"></i>
                        <span>Configuración</span>
                    </a>
                </li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
        
        <div class="sidebar-footer">
            <i class="fa-solid fa-shield-halved text-success"></i>
            <span>Sistema Seguro RBAC</span>
        </div>
    </div>

    <!-- WRAPPER -->
    <div class="wrapper" id="wrapper">
        
        <!-- TOP NAVBAR -->
        <nav class="top-navbar">
            <button class="btn-toggle-sidebar" id="sidebarCollapse">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
            
            <div class="h5 m-0 font-weight-bold d-none d-md-block text-muted" style="font-family: 'Outfit', sans-serif;">
                Panel de Control de Ventas
            </div>
            
            <div class="top-navbar-actions">
                <div class="dropdown">
                    <div class="user-profile dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <?= esc(session()->get('iniciales') ?? 'U') ?>
                        </div>
                        <div class="user-info d-none d-sm-flex">
                            <span class="user-name"><?= esc(session()->get('nombres') . ' ' . session()->get('apellidos')) ?></span>
                            <span class="user-role"><?= esc(session()->get('rol_nombre') ?? 'Usuario') ?></span>
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" aria-labelledby="profileDropdown" style="border-radius:var(--radius-md)">
                        <li class="px-3 py-2 border-bottom">
                            <span class="d-block text-dark fw-semibold text-truncate" style="max-width:180px;"><?= esc(session()->get('nombres') . ' ' . session()->get('apellidos')) ?></span>
                            <span class="d-block text-muted small text-truncate" style="max-width:180px;"><?= esc(session()->get('correo')) ?></span>
                        </li>
                        <?php if ($hasUsuarios): ?>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="<?= base_url('usuarios/ver/' . session()->get('userId')) ?>">
                                <i class="fa-regular fa-user text-muted"></i> Mi Perfil
                            </a>
                        </li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger" href="<?= base_url('logout') ?>">
                                <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- PAGE CONTENT -->
        <div class="page-content">
            <!-- Sección de alertas globales para CRUDs -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bootstrap="dismiss" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bootstrap="dismiss" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
        
        <!-- FOOTER -->
        <footer class="footer-admin">
            <div>
                &copy; <?= date('Y') ?> <strong>Kobra POS/ERP</strong> &bull; Todos los derechos reservados.
            </div>
        </footer>
        
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
            
            // Cerrar sidebar al hacer click fuera en móviles
            $(document).on('click', function(e) {
                if ($(window).width() < 992) {
                    if (!$(e.target).closest('#sidebar').length && !$(e.target).closest('#sidebarCollapse').length) {
                        $('#sidebar').removeClass('active');
                    }
                }
            });
        });
    </script>
    
    <!-- Renderizador de scripts complementarios -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
