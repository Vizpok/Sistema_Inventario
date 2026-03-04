<?php
/**
 * Página de Inicio del Sistema
 * Redirige al login o dashboard según autenticación
 */

session_start();

// Si está autenticado, va al dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: System/interface/dashboard/index.php');
} else {
    // Si no está autenticado, va al login
    header('Location: System/interface/session/login.php');
}

exit();
