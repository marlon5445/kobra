<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Kobra POS</title>
    <meta name="description" content="Accede al Sistema de Punto de Venta Kobra POS con tus credenciales corporativas.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #3730A3;
            --primary-light: rgba(79, 70, 229, 0.08);
            --primary-glow: rgba(79, 70, 229, 0.3);
            --success: #10B981;
            --danger: #EF4444;
            --text-dark: #1E293B;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --bg: #F8FAFC;
            --white: #FFFFFF;
            --radius: 14px;
            --radius-sm: 10px;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            background-color: var(--bg);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ── Panel Izquierdo (Branding) ── */
        .branding-panel {
            flex: 1;
            background: linear-gradient(145deg, #0F172A 0%, #1E293B 40%, #1a1060 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 4rem;
            position: relative;
            overflow: hidden;
        }

        .branding-panel::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .branding-panel::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 3rem;
            position: relative;
            z-index: 1;
        }

        .brand-logo-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary) 0%, #06B6D4 100%);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }

        .brand-logo-text {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.04em;
        }

        .brand-logo-text span {
            color: #94A3B8;
            font-weight: 300;
            font-size: 1.6rem;
        }

        .brand-headline {
            font-family: 'Outfit', sans-serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 1.25rem;
            position: relative;
            z-index: 1;
        }

        .brand-headline em {
            font-style: normal;
            background: linear-gradient(135deg, #6366F1, #06B6D4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-description {
            font-size: 1.05rem;
            color: #94A3B8;
            line-height: 1.7;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .brand-features {
            margin-top: 3rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            color: #CBD5E1;
            font-size: 0.95rem;
        }

        .brand-feature i {
            background: rgba(79, 70, 229, 0.2);
            color: #818CF8;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        /* ── Panel Derecho (Formulario) ── */
        .login-panel {
            width: 480px;
            flex-shrink: 0;
            background: var(--white);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 3.5rem;
            position: relative;
        }

        .login-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(ellipse at top right, rgba(79, 70, 229, 0.04) 0%, transparent 50%),
                radial-gradient(ellipse at bottom left, rgba(6, 182, 212, 0.03) 0%, transparent 50%);
            pointer-events: none;
        }

        .login-form-container {
            width: 100%;
            max-width: 380px;
            position: relative;
            z-index: 1;
        }

        .login-header {
            margin-bottom: 2.5rem;
        }

        .login-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.9rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* Alerts */
        .alert-box {
            padding: 1rem 1.15rem;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.75rem;
            animation: slideIn 0.3s ease;
        }

        .alert-box.error {
            background: rgba(239, 68, 68, 0.07);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #B91C1C;
        }

        .alert-box.success {
            background: rgba(16, 185, 129, 0.07);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: #065F46;
        }

        /* Formulario */
        .form-group {
            margin-bottom: 1.4rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 0.95rem;
            pointer-events: none;
            transition: var(--transition);
        }

        .form-input {
            width: 100%;
            height: 50px;
            padding: 0 1rem 0 2.85rem;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--text-dark);
            background: #FAFBFC;
            transition: var(--transition);
            outline: none;
        }

        .form-input:focus {
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px var(--primary-light);
        }

        .form-input:focus + .input-icon,
        .input-wrapper:has(.form-input:focus) .input-icon {
            color: var(--primary);
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            font-size: 0.95rem;
            transition: var(--transition);
            padding: 0.25rem;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .password-input {
            padding-right: 3rem;
        }

        /* Recordar & olvidé contraseña */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .remember-check input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .remember-check span {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        /* Botón Submit */
        .btn-login {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            box-shadow: 0 4px 15px var(--primary-glow);
            letter-spacing: 0.015em;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--primary-glow);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login.loading {
            opacity: 0.8;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Footer del formulario */
        .login-footer {
            margin-top: 2.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            border-top: 1px solid var(--border);
            padding-top: 1.5rem;
        }

        .login-footer strong {
            color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 900px) {
            body { flex-direction: column; }
            .branding-panel { display: none; }
            .login-panel {
                width: 100%;
                padding: 3rem 2rem;
                min-height: 100vh;
                justify-content: center;
            }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- PANEL IZQUIERDO: BRANDING -->
    <div class="branding-panel">
        <div class="brand-logo">
            <div class="brand-logo-icon">
                <i class="fa-solid fa-crown"></i>
            </div>
            <div class="brand-logo-text">KOBRA <span>POS</span></div>
        </div>

        <h1 class="brand-headline">
            Tu sistema<br>
            <em>ERP de ventas</em><br>
            en un solo lugar.
        </h1>

        <p class="brand-description">
            Gestiona tu inventario, ventas, usuarios y reportes desde un panel de control moderno, rápido y seguro.
        </p>

        <div class="brand-features">
            <div class="brand-feature">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Control de acceso por roles y permisos (RBAC)</span>
            </div>
            <div class="brand-feature">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Inventario en tiempo real con alertas de stock</span>
            </div>
            <div class="brand-feature">
                <i class="fa-solid fa-chart-line"></i>
                <span>Dashboard analítico y reportes integrados</span>
            </div>
            <div class="brand-feature">
                <i class="fa-solid fa-mobile-screen-button"></i>
                <span>Interfaz responsiva y compatible con dispositivos móviles</span>
            </div>
        </div>
    </div>

    <!-- PANEL DERECHO: FORMULARIO -->
    <div class="login-panel">
        <div class="login-form-container">

            <div class="login-header">
                <h2 class="login-title">Iniciar Sesión</h2>
                <p class="login-subtitle">Ingresa tus credenciales para acceder al sistema POS.</p>
            </div>

            <!-- Alerta de error -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-box error">
                    <i class="fa-solid fa-triangle-exclamation" style="margin-top: 2px; flex-shrink: 0;"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <!-- Alerta de éxito (logout) -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-box success">
                    <i class="fa-solid fa-circle-check" style="margin-top: 2px; flex-shrink: 0;"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <!-- Formulario de Login -->
            <form id="loginForm" action="<?= base_url('login/procesar') ?>" method="POST" autocomplete="on">
                <?= csrf_field() ?>

                <!-- Usuario o Correo -->
                <div class="form-group">
                    <label for="usuario" class="form-label">Usuario o Correo Electrónico</label>
                    <div class="input-wrapper">
                        <input type="text"
                               id="usuario"
                               name="usuario"
                               class="form-input"
                               placeholder="Ej. admin o admin@kobrapos.com"
                               value="<?= old('usuario') ?>"
                               autocomplete="username"
                               required>
                        <span class="input-icon"><i class="fa-regular fa-user"></i></span>
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <div class="input-wrapper">
                        <input type="password"
                               id="contrasena"
                               name="contrasena"
                               class="form-input password-input"
                               placeholder="••••••••"
                               autocomplete="current-password"
                               required>
                        <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                        <button type="button" class="toggle-password" id="togglePwd" title="Mostrar/Ocultar contraseña">
                            <i class="fa-regular fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Recordar Sesión -->
                <div class="form-options">
                    <label class="remember-check">
                        <input type="checkbox" name="recordar" value="1">
                        <span>Recordar sesión</span>
                    </label>
                </div>

                <!-- Botón de Acceso -->
                <button type="submit" class="btn-login" id="btnLogin">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Acceder al Sistema
                </button>
            </form>

            <!-- Footer informativo -->
            <div class="login-footer">
                <p>&copy; <?= date('Y') ?> <strong>Kobra POS/ERP</strong> &mdash; Sistema de Punto de Venta</p>
                <p style="margin-top: 0.4rem;">Desarrollado sobre <strong>CodeIgniter <?= \CodeIgniter\CodeIgniter::CI_VERSION ?></strong></p>
            </div>

        </div>
    </div>

    <script>
        // Toggle visibilidad de la contraseña
        document.getElementById('togglePwd').addEventListener('click', function() {
            const input = document.getElementById('contrasena');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        // Efecto de carga al enviar el formulario
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnLogin');
            btn.classList.add('loading');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verificando...';
        });
    </script>

</body>
</html>
