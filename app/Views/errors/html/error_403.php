<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Acceso Denegado - Kobra POS</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4F46E5;
            --danger: #EF4444;
            --text-dark: #1E293B;
            --text-muted: #64748B;
            --bg: #F8FAFC;
            --white: #FFFFFF;
            --radius: 16px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
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
            background-color: var(--bg);
            color: var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            overflow-x: hidden;
        }

        .error-container {
            max-width: 520px;
            width: 100%;
            background-color: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 3.5rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid #E2E8F0;
        }

        .error-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--danger) 0%, #F59E0B 100%);
        }

        .shield-icon-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 110px;
            height: 110px;
            background-color: rgba(239, 68, 68, 0.07);
            border-radius: 50%;
            margin-bottom: 2rem;
            animation: pulse 2s infinite;
        }

        .shield-icon {
            font-size: 3.25rem;
            color: var(--danger);
        }

        .lock-badge {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background-color: #F59E0B;
            color: #fff;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            border: 3px solid var(--white);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .error-code {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--danger);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
        }

        .error-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            line-height: 1.25;
        }

        .error-message {
            font-size: 0.975rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2.25rem;
        }

        .user-meta-info {
            background-color: var(--bg);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 2.25rem;
            font-size: 0.825rem;
            text-align: left;
            border: 1px dashed #E2E8F0;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.4rem;
        }

        .meta-row:last-child {
            margin-bottom: 0;
        }

        .meta-label {
            font-weight: 600;
            color: #64748B;
        }

        .meta-value {
            font-family: monospace;
            color: #334155;
            font-weight: 600;
        }

        .actions-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
            border: none;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--text-dark);
            border: 1.5px solid #E2E8F0;
        }

        .btn-outline:hover {
            background-color: #F1F5F9;
            border-color: #CBD5E1;
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.2); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        @media (max-width: 576px) {
            .error-container { padding: 2.5rem 1.5rem; }
            .error-title { font-size: 1.75rem; }
            .actions-group { flex-direction: column; }
            .btn-action { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

    <div class="error-container">
        <div class="shield-icon-wrapper">
            <i class="fa-solid fa-shield-halved shield-icon"></i>
            <div class="lock-badge">
                <i class="fa-solid fa-lock"></i>
            </div>
        </div>

        <div class="error-code">Error 403</div>
        <h1 class="error-title">Acceso Restringido</h1>
        
        <p class="error-message">
            Lo sentimos, pero tu cuenta no posee los permisos suficientes para acceder a este módulo. Por favor, ponte en contacto con tu administrador de sistema si crees que esto es un error.
        </p>

        <!-- Información de Metadatos de la Petición para Soporte Técnico -->
        <div class="user-meta-info">
            <div class="meta-row">
                <span class="meta-label">Usuario:</span>
                <span class="meta-value"><?= esc(session()->get('usuario') ?? 'Invitado') ?></span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Rol:</span>
                <span class="meta-value"><?= esc(session()->get('rol_nombre') ?? 'Ninguno') ?></span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Módulo Solicitado:</span>
                <span class="meta-value"><?= esc(uri_string()) ?></span>
            </div>
        </div>

        <div class="actions-group">
            <a href="<?= base_url('dashboard') ?>" class="btn-action btn-primary">
                <i class="fa-solid fa-house"></i> Ir al Dashboard
            </a>
            <button onclick="history.back()" class="btn-action btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Regresar
            </button>
        </div>
    </div>

</body>
</html>
