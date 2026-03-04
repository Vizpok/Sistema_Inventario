<?php
/**
 * Logout - Cerrar Sesión
 */

require_once __DIR__ . '/../../bootstrap.php';

// Cerrar sesión
Auth::logout();

// Redirigir al login
header('Location: /Sistema_Inventario/System/interface/session/login.php');
exit();
