<?php
/**
 * Módulo de Recepción
 * Redirige al formulario de recepción
 */

require_once __DIR__ . '/../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Redirigir al formulario de recepción
redirect('/Sistema_Inventario/System/interface/reception/recepcion.php');
?>