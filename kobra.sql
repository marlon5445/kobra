-- =====================================================================
-- MASTER SQL SCHEMA - SISTEMA DE VENTAS ERP/POS KOBRA
-- Versión de Base de Datos: v4 (Fase 6 - Módulo de Compras, Ventas e Inventario)
-- Autor: Arquitecto de Software Senior
-- Fecha: 29 de Mayo de 2026
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- Eliminar tablas en orden correcto (dependientes primero)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `movimientos_inventario`;
DROP TABLE IF EXISTS `detalle_compras`;
DROP TABLE IF EXISTS `detalle_ventas`;
DROP TABLE IF EXISTS `compras`;
DROP TABLE IF EXISTS `ventas`;
DROP TABLE IF EXISTS `rol_permisos`;
DROP TABLE IF EXISTS `usuario_roles`;
DROP TABLE IF EXISTS `productos`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `permisos`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `clientes`;
DROP TABLE IF EXISTS `proveedores`;
DROP TABLE IF EXISTS `configuracion_empresa`;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- 1. TABLA: CATEGORIAS (Fase 2)
-- =====================================================================
CREATE TABLE `categorias` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(150) NOT NULL,
    `descripcion` TEXT NULL,
    `estado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Activo, 0: Inactivo',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_categorias_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 2. TABLA: ROLES (Fase 4)
-- =====================================================================
CREATE TABLE `roles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `descripcion` TEXT NULL,
    `estado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Activo, 0: Inactivo',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_rol_nombre` (`nombre`),
    INDEX `idx_roles_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 3. TABLA: PERMISOS (Fase 4)
-- =====================================================================
CREATE TABLE `permisos` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(150) NOT NULL COMMENT 'ej: productos.crear',
    `modulo` VARCHAR(100) NOT NULL,
    `accion` VARCHAR(50) NOT NULL,
    `descripcion` TEXT NULL,
    UNIQUE KEY `unique_permiso_nombre` (`nombre`),
    INDEX `idx_permisos_modulo` (`modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 4. TABLA: USUARIOS (Fase 4)
-- =====================================================================
CREATE TABLE `usuarios` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nombres` VARCHAR(100) NOT NULL,
    `apellidos` VARCHAR(100) NOT NULL,
    `usuario` VARCHAR(50) NOT NULL,
    `correo` VARCHAR(150) NOT NULL,
    `contrasena` VARCHAR(255) NOT NULL,
    `estado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Activo, 0: Inactivo',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_usuario` (`usuario`),
    UNIQUE KEY `unique_correo` (`correo`),
    INDEX `idx_usuarios_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 5. TABLA PIVOTE: USUARIO_ROLES (Fase 4)
-- =====================================================================
CREATE TABLE `usuario_roles` (
    `usuario_id` INT UNSIGNED NOT NULL,
    `rol_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`usuario_id`, `rol_id`),
    CONSTRAINT `fk_user_roles_usuarios`
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_user_roles_roles`
        FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 6. TABLA PIVOTE: ROL_PERMISOS (Fase 4)
-- =====================================================================
CREATE TABLE `rol_permisos` (
    `rol_id` INT UNSIGNED NOT NULL,
    `permiso_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`rol_id`, `permiso_id`),
    CONSTRAINT `fk_rol_permisos_roles`
        FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_rol_permisos_permisos`
        FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 7. TABLA: PRODUCTOS (Fase 3)
-- =====================================================================
CREATE TABLE `productos` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `categoria_id` INT UNSIGNED NOT NULL,
    `codigo` VARCHAR(50) NOT NULL,
    `nombre` VARCHAR(150) NOT NULL,
    `descripcion` TEXT NULL,
    `precio_compra` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `precio_venta` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `stock` INT NOT NULL DEFAULT 0,
    `stock_minimo` INT NOT NULL DEFAULT 0,
    `imagen` VARCHAR(255) NULL,
    `estado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Activo, 0: Inactivo',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_codigo` (`codigo`),
    INDEX `idx_productos_estado` (`estado`),
    INDEX `idx_productos_codigo` (`codigo`),
    CONSTRAINT `fk_productos_categorias`
        FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 8. TABLA: CLIENTES (Fase 5)
-- =====================================================================
CREATE TABLE `clientes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tipo_documento` VARCHAR(50) NOT NULL,
    `numero_documento` VARCHAR(50) NOT NULL,
    `nombres` VARCHAR(255) NOT NULL,
    `direccion` VARCHAR(255) NULL,
    `telefono` VARCHAR(50) NULL,
    `correo` VARCHAR(150) NULL,
    `observaciones` TEXT NULL,
    `estado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Activo, 0: Inactivo',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_cliente_documento` (`numero_documento`),
    UNIQUE KEY `unique_cliente_correo` (`correo`),
    INDEX `idx_clientes_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 9. TABLA: PROVEEDORES (Fase 5)
-- =====================================================================
CREATE TABLE `proveedores` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ruc` VARCHAR(50) NOT NULL,
    `razon_social` VARCHAR(255) NOT NULL,
    `nombre_comercial` VARCHAR(255) NULL,
    `direccion` VARCHAR(255) NULL,
    `telefono` VARCHAR(50) NULL,
    `correo` VARCHAR(150) NULL,
    `contacto` VARCHAR(150) NULL,
    `observaciones` TEXT NULL,
    `estado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Activo, 0: Inactivo',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_proveedor_ruc` (`ruc`),
    UNIQUE KEY `unique_proveedor_correo` (`correo`),
    INDEX `idx_proveedores_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 10. TABLA: COMPRAS (Fase 6)
-- =====================================================================
CREATE TABLE `compras` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `numero_compra` VARCHAR(20) NOT NULL COMMENT 'Ej: C-0001',
    `proveedor_id` INT UNSIGNED NOT NULL,
    `usuario_id` INT UNSIGNED NOT NULL,
    `fecha` DATETIME NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `impuesto` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'IGV 18%',
    `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `observaciones` TEXT NULL,
    `estado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Activa, 0: Anulada',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_numero_compra` (`numero_compra`),
    INDEX `idx_compras_estado` (`estado`),
    INDEX `idx_compras_fecha` (`fecha`),
    CONSTRAINT `fk_compras_proveedores`
        FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_compras_usuarios`
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 11. TABLA: DETALLE_COMPRAS (Fase 6)
-- =====================================================================
CREATE TABLE `detalle_compras` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `compra_id` INT UNSIGNED NOT NULL,
    `producto_id` INT UNSIGNED NOT NULL,
    `cantidad` INT NOT NULL,
    `costo_unitario` DECIMAL(10,2) NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    CONSTRAINT `fk_detalle_compras_compra`
        FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_detalle_compras_producto`
        FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 12. TABLA: VENTAS (Fase 6)
-- =====================================================================
CREATE TABLE `ventas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `numero_venta` VARCHAR(20) NOT NULL COMMENT 'Ej: B-0001, F-0001, T-0001',
    `tipo_comprobante` VARCHAR(20) NOT NULL COMMENT 'Boleta / Factura / Ticket',
    `cliente_id` INT UNSIGNED NULL COMMENT 'NULL = Consumidor Final',
    `usuario_id` INT UNSIGNED NOT NULL,
    `fecha` DATETIME NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `descuento` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `impuesto` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'IGV 18% solo en Facturas',
    `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `observaciones` TEXT NULL,
    `estado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Activa, 0: Anulada',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_numero_venta` (`numero_venta`),
    INDEX `idx_ventas_estado` (`estado`),
    INDEX `idx_ventas_fecha` (`fecha`),
    INDEX `idx_ventas_tipo` (`tipo_comprobante`),
    CONSTRAINT `fk_ventas_clientes`
        FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_ventas_usuarios`
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 13. TABLA: DETALLE_VENTAS (Fase 6)
-- =====================================================================
CREATE TABLE `detalle_ventas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `venta_id` INT UNSIGNED NOT NULL,
    `producto_id` INT UNSIGNED NOT NULL,
    `cantidad` INT NOT NULL,
    `precio_unitario` DECIMAL(10,2) NOT NULL,
    `descuento` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Descuento por ítem',
    `subtotal` DECIMAL(10,2) NOT NULL,
    CONSTRAINT `fk_detalle_ventas_venta`
        FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_detalle_ventas_producto`
        FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 14. TABLA: MOVIMIENTOS_INVENTARIO (Fase 6)
-- =====================================================================
CREATE TABLE `movimientos_inventario` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `producto_id` INT UNSIGNED NOT NULL,
    `tipo_movimiento` ENUM('entrada','salida','ajuste') NOT NULL,
    `documento` VARCHAR(30) NOT NULL COMMENT 'Ej: C-0001 o B-0001',
    `cantidad` INT NOT NULL,
    `stock_anterior` INT NOT NULL,
    `stock_nuevo` INT NOT NULL,
    `usuario_id` INT UNSIGNED NOT NULL,
    `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_mov_producto` (`producto_id`),
    INDEX `idx_mov_tipo` (`tipo_movimiento`),
    INDEX `idx_mov_fecha` (`fecha`),
    CONSTRAINT `fk_mov_producto`
        FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_mov_usuario`
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 15. TABLA: CONFIGURACION_EMPRESA (Fase 7)
-- =====================================================================
CREATE TABLE `configuracion_empresa` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `razon_social` VARCHAR(255) NOT NULL,
    `nombre_comercial` VARCHAR(255) NULL,
    `ruc` VARCHAR(20) NOT NULL,
    `direccion` VARCHAR(255) NULL,
    `telefono` VARCHAR(50) NULL,
    `correo` VARCHAR(150) NULL,
    `logo` VARCHAR(255) NULL,
    `moneda` VARCHAR(50) NOT NULL DEFAULT 'Soles',
    `simbolo_moneda` VARCHAR(10) NOT NULL DEFAULT 'S/',
    `mensaje_ticket` TEXT NULL,
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- =====================================================================
-- INSERCIÓN DE DATOS SEMILLA
-- =====================================================================

-- 1. Categorías
INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `estado`) VALUES
(1, 'Bebidas', 'Refrescos, aguas minerales, jugos y bebidas energizantes', 1),
(2, 'Abarrotes', 'Productos alimenticios secos, arroz, fideos, aceites y conservas', 1),
(3, 'Lácteos', 'Leches, quesos, yogures, mantequillas y derivados lácteos', 1),
(4, 'Limpieza', 'Detergentes, desinfectantes, esponjas y artículos de limpieza general', 1);

-- 2. Productos
INSERT INTO `productos` (`id`, `categoria_id`, `codigo`, `nombre`, `descripcion`, `precio_compra`, `precio_venta`, `stock`, `stock_minimo`, `imagen`, `estado`) VALUES
(1, 1, '7501055300010', 'Coca Cola Original 600ml', 'Refresco de cola embotellado de 600 mililitros', 0.80, 1.50, 45, 10, NULL, 1),
(2, 1, '7501055300027', 'Agua Mineral Manantial 500ml', 'Agua mineral con gas de manantial purificado', 0.40, 0.90, 8, 15, NULL, 1),
(3, 2, '7501055300034', 'Arroz Premium Grano Largo 1kg', 'Arroz pulido grano largo seleccionado premium bolsa 1kg', 1.10, 1.80, 120, 20, NULL, 1),
(4, 3, '7501055300041', 'Leche Entera Cremosa 1L', 'Leche entera ultrapasteurizada enriquecida con vitaminas A y D', 0.95, 1.40, 30, 8, NULL, 1);

-- 3. Roles
INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `estado`) VALUES
(1, 'Administrador', 'Acceso total sin restricciones al sistema.', 1),
(2, 'Vendedor', 'Acceso a operaciones de venta y consultas básicas del POS.', 1);

-- 4. Permisos (35 total)
INSERT INTO `permisos` (`id`, `nombre`, `modulo`, `accion`, `descripcion`) VALUES
-- Dashboard
(1, 'dashboard.ver', 'dashboard', 'ver', 'Permite visualizar el panel principal.'),
-- Categorías
(2,  'categorias.ver',      'categorias', 'ver',      'Ver listado de categorías.'),
(3,  'categorias.crear',    'categorias', 'crear',    'Registrar nuevas categorías.'),
(4,  'categorias.editar',   'categorias', 'editar',   'Editar categorías.'),
(5,  'categorias.eliminar', 'categorias', 'eliminar', 'Eliminación lógica de categorías.'),
-- Productos
(6,  'productos.ver',      'productos', 'ver',      'Ver catálogo de productos.'),
(7,  'productos.crear',    'productos', 'crear',    'Registrar nuevos productos.'),
(8,  'productos.editar',   'productos', 'editar',   'Modificar productos.'),
(9,  'productos.eliminar', 'productos', 'eliminar', 'Inhabilitar productos.'),
-- Usuarios
(10, 'usuarios.ver',      'usuarios', 'ver',      'Ver usuarios del sistema.'),
(11, 'usuarios.crear',    'usuarios', 'crear',    'Registrar nuevos usuarios.'),
(12, 'usuarios.editar',   'usuarios', 'editar',   'Modificar usuarios.'),
(13, 'usuarios.eliminar', 'usuarios', 'eliminar', 'Deshabilitar usuarios.'),
-- Roles
(14, 'roles.ver',      'roles', 'ver',      'Listar roles.'),
(15, 'roles.crear',    'roles', 'crear',    'Crear nuevos roles.'),
(16, 'roles.editar',   'roles', 'editar',   'Modificar y asignar permisos a roles.'),
(17, 'roles.eliminar', 'roles', 'eliminar', 'Dar de baja roles.'),
-- Clientes
(18, 'clientes.ver',      'clientes', 'ver',      'Ver listado de clientes.'),
(19, 'clientes.crear',    'clientes', 'crear',    'Registrar nuevos clientes.'),
(20, 'clientes.editar',   'clientes', 'editar',   'Modificar clientes.'),
(21, 'clientes.eliminar', 'clientes', 'eliminar', 'Eliminación lógica de clientes.'),
-- Proveedores
(22, 'proveedores.ver',      'proveedores', 'ver',      'Ver listado de proveedores.'),
(23, 'proveedores.crear',    'proveedores', 'crear',    'Registrar nuevos proveedores.'),
(24, 'proveedores.editar',   'proveedores', 'editar',   'Modificar proveedores.'),
(25, 'proveedores.eliminar', 'proveedores', 'eliminar', 'Eliminación lógica de proveedores.'),
-- Compras (Fase 6)
(26, 'compras.ver',      'compras', 'ver',      'Ver historial de compras.'),
(27, 'compras.crear',    'compras', 'crear',    'Registrar nuevas compras.'),
(28, 'compras.editar',   'compras', 'editar',   'Editar compras.'),
(29, 'compras.eliminar', 'compras', 'eliminar', 'Eliminar compras del sistema.'),
(30, 'compras.anular',   'compras', 'anular',   'Anular compras y revertir stock.'),
-- Ventas (Fase 6)
(31, 'ventas.ver',      'ventas', 'ver',      'Ver historial de ventas.'),
(32, 'ventas.crear',    'ventas', 'crear',    'Registrar nuevas ventas en el POS.'),
(33, 'ventas.editar',   'ventas', 'editar',   'Editar ventas.'),
(34, 'ventas.eliminar', 'ventas', 'eliminar', 'Eliminar ventas del sistema.'),
(35, 'ventas.anular',   'ventas', 'anular',   'Anular ventas y restaurar stock.'),
-- Configuración de Empresa (Fase 7)
(36, 'configuracion.ver',    'configuracion', 'ver',    'Ver la configuración de la empresa.'),
(37, 'configuracion.editar', 'configuracion', 'editar', 'Modificar la configuración de la empresa.'),
-- Reportes (Fase 8)
(38, 'reportes.ventas', 'reportes', 'ventas', 'Ver reporte de ventas.'),
(39, 'reportes.compras', 'reportes', 'compras', 'Ver reporte de compras.');

-- 5. Permisos al Administrador (Acceso Total: 1–39)
INSERT INTO `rol_permisos` (`rol_id`, `permiso_id`) VALUES
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),
(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),
(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),
(1,31),(1,32),(1,33),(1,34),(1,35),(1,36),(1,37),(1,38),(1,39);

-- 6. Permisos al Vendedor (Dashboard, Categorías Ver, Productos Ver, Clientes Ver, Ventas Ver+Crear)
INSERT INTO `rol_permisos` (`rol_id`, `permiso_id`) VALUES
(2,1),(2,2),(2,6),(2,18),(2,31),(2,32);

-- 7. Usuario Administrador
-- Contraseña: admin123
INSERT INTO `usuarios` (`id`, `nombres`, `apellidos`, `usuario`, `correo`, `contrasena`, `estado`) VALUES
(1, 'Administrador', 'Kobra', 'admin', 'admin@kobrapos.com', '$2y$10$SBYG8bW2.vMO.jDWT/y0W.XRJwzU6TP3rqd0dbvhtYi6PT7aQ9bdm', 1);

-- 8. Asociación Admin → Rol Administrador
INSERT INTO `usuario_roles` (`usuario_id`, `rol_id`) VALUES (1, 1);

-- 9. Clientes Semilla
INSERT INTO `clientes` (`id`, `tipo_documento`, `numero_documento`, `nombres`, `direccion`, `telefono`, `correo`, `observaciones`, `estado`) VALUES
(1, 'DNI',  '10203040',    'Juan Pérez',                'Av. Larco 456, Miraflores',          '987654321', 'juan.perez@gmail.com',            'Cliente recurrente e histórico del POS.', 1),
(2, 'RUC',  '20104050607', 'Inversiones Globales S.A.C.','Calle Las Begonias 789, San Isidro', '01-4402010','contacto@inversionesglobales.com', 'Cliente corporativo, requiere factura.', 1),
(3, 'Cédula','A-897654',   'María Rodríguez',           'Jr. Junín 123, Cercado de Lima',      '912345678', 'maria.rod@outlook.com',           NULL, 1);

-- 10. Proveedores Semilla
INSERT INTO `proveedores` (`id`, `ruc`, `razon_social`, `nombre_comercial`, `direccion`, `telefono`, `correo`, `contacto`, `observaciones`, `estado`) VALUES
(1, '20503040506', 'Distribuidora de Alimentos del Norte S.A.', 'Alimentos del Norte', 'Panamericana Norte Km 14, Los Olivos', '955443322',  'ventas@alimentosnorte.pe',     'Lic. Carlos Mendoza', 'Distribuidor oficial de lácteos y abarrotes.', 1),
(2, '20908070605', 'Corporación de Bebidas del Perú S.A.C.',    'Bebidas del Perú',    'Av. El Sol 450, Ate Vitarte',         '01-3506070', 'pedidos@bebidasperu.com.pe',   'Ing. Sofía Valdivia', 'Proveedor principal de aguas y gaseosas.',    1);

-- 11. Configuración de Empresa Semilla
INSERT INTO `configuracion_empresa` (`id`, `razon_social`, `nombre_comercial`, `ruc`, `direccion`, `telefono`, `correo`, `logo`, `moneda`, `simbolo_moneda`, `mensaje_ticket`) VALUES
(1, 'Kobra Soft S.A.C.', 'Kobra POS & ERP', '20123456789', 'Av. Javier Prado Este 1024, San Isidro, Lima', '01-4445566', 'contacto@kobrasoft.com', NULL, 'Soles', 'S/', '¡Gracias por su compra! Vuelva pronto.');
