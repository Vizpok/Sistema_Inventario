<?php
/**
 * Dashboard Principal
 * Vista general del sistema de inventario
 */

// Cargar el sistema
require_once __DIR__ . '/../../bootstrap.php';

// Opcional: Requerir autenticación
// requireAuth();

// Configurar variables para el layout
$page_title = 'Dashboard';
$base_url = '../..';

// Incluir header
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-container">

    <div class="page-header">
        <h1 class="page-title">Visión General del Almacén</h1>
        <p class="page-subtitle">
            Consulta rápida del estado actual del inventario y operaciones del sistema.
        </p>
    </div>

    <section class="dashboard-content">

        <div class="card">
            <h3><i class="bi bi-currency-dollar"></i> Valor Total del Almacén</h3>
            <p style="font-size: 32px; font-weight: bold; color: #28a745; margin-top: 15px;">
                <?php
                // Ejemplo: consulta al sistema
                // $database = db();
                // $valor = $database->select("SELECT SUM(precio * cantidad) as total FROM productos");
                // echo formatMoney($valor[0]['total'] ?? 0);
                ?>
                $0.00
            </p>
            <small style="color: #6c757d;">Próximamente con datos reales</small>
        </div>

        <div class="card">
            <h3><i class="bi bi-box-seam"></i> Total de Productos</h3>
            <p style="font-size: 32px; font-weight: bold; color: #0b1e36; margin-top: 15px;">
                <?php
                // Ejemplo
                // $total = $database->select("SELECT COUNT(*) as total FROM productos");
                // echo number_format($total[0]['total'] ?? 0);
                ?>
                0
            </p>
            <small style="color: #6c757d;">Artículos en inventario</small>
        </div>

        <div class="card">
            <h3><i class="bi bi-exclamation-triangle"></i> Stock Bajo</h3>
            <p style="font-size: 32px; font-weight: bold; color: #dc3545; margin-top: 15px;">
                <?php
                // Ejemplo
                // $bajo = $database->select("SELECT COUNT(*) as total FROM productos WHERE cantidad < cantidad_minima");
                // echo $bajo[0]['total'] ?? 0;
                ?>
                0
            </p>
            <small style="color: #6c757d;">Productos por reabastecer</small>
        </div>

        <div class="card">
            <h3><i class="bi bi-clock-history"></i> Actividad Reciente</h3>
            <div style="margin-top: 15px;">
                <?php
                // Ejemplo de actividad reciente
                // $actividad = $database->select("SELECT * FROM movimientos ORDER BY fecha DESC LIMIT 5");
                // foreach($actividad as $mov) { ... }
                ?>
                <p style="color: #6c757d; font-style: italic;">
                    No hay actividad reciente
                </p>
            </div>
        </div>

    </section>

    <section style="margin-top: 30px;">
        <h2 style="margin-bottom: 20px;">Accesos Rápidos</h2>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="<?= $base_url ?>/inventario.php" class="btn btn-primary">
                <i class="bi bi-box-seam"></i> Ver Inventario
            </a>
            <a href="<?= $base_url ?>/recepcion.php" class="btn btn-success">
                <i class="bi bi-box-arrow-in-down"></i> Nueva Recepción
            </a>
            <a href="<?= $base_url ?>/movimientos.php" class="btn btn-warning">
                <i class="bi bi-arrow-left-right"></i> Ver Movimientos
            </a>
            <a href="<?= $base_url ?>/catalogo.php" class="btn btn-primary">
                <i class="bi bi-journal-text"></i> Administrar Catálogo
            </a>
        </div>
    </section>

</div>

<?php
// Incluir footer
include __DIR__ . '/../layouts/footer.php';
?>
