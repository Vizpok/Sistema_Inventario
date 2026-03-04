<?php
/**
 * Página de Login
 * Vista de autenticación del sistema
 */

// Cargar bootstrap para usar la misma configuración de sesión en todo el sistema
require_once __DIR__ . '/../../bootstrap.php';

// Si ya está autenticado, redirige al dashboard
if (Auth::isAuthenticated()) {
    header('Location: ../dashboard/index.php');
    exit();
}

// Procesar login si viene POST
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    
    // Intentar autenticar
    $user = Auth::authenticate($usuario, $contrasena);
    
    if ($user) {
        // Login exitoso
        Auth::login($user['id'], $user['nombre'], $user['rol']);
        header('Location: ../dashboard/index.php');
        exit();
    } else {
        // Credenciales inválidas
        if (empty($usuario) || empty($contrasena)) {
            $error = 'Por favor ingresa usuario y contraseña';
        } else {
            $error = 'Usuario o contraseña incorrectos';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SGI Sistema Inventario</title>
    <link rel="stylesheet" href="../../assets/css/auth.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="auth-page">

    <div class="auth-container">
        
        <div class="auth-header">
            <div class="auth-logo">
                <i class="bi bi-box-seam"></i>
            </div>
            <h1 class="auth-title">SGI Sistema</h1>
            <p class="auth-subtitle">Sistema de Gestión de Inventario</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="auth-alert auth-alert-error">
                <i class="bi bi-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            
            <div class="auth-form-group">
                <label class="auth-form-label">Usuario</label>
                <div class="auth-input-icon">
                    <i class="bi bi-person-fill"></i>
                    <input 
                        type="text" 
                        class="auth-form-control" 
                        name="usuario" 
                        placeholder="Usuario o correo"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="auth-form-group">
                <label class="auth-form-label">Contraseña</label>
                <div class="auth-input-icon">
                    <i class="bi bi-lock-fill"></i>
                    <input 
                        type="password" 
                        class="auth-form-control" 
                        name="contrasena" 
                        placeholder="Tu contraseña"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="auth-btn-submit">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
            </button>

        </form>

        <div class="auth-footer">
            <p>© 2026 Sistema de Inventario</p>
        </div>

    </div>

</body>
</html>

