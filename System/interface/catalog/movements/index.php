<?php
require_once __DIR__ . '/../../../bootstrap.php';
requireAuth();

$page_title = 'Movimientos';
$base_url = '/Sistema_Inventario';
$ruta_movimientos = $base_url . '/System/interface/catalog/movements/movimientos.php';

// Obtener tipos de movimiento
$tipos = db()->select("SELECT DISTINCT TIPO_MOVIMIENTO FROM movimientos ORDER BY TIPO_MOVIMIENTO ASC");

$movimientos_por_tipo = [];
foreach ($tipos as $tipo) {
    $tipo_mov = $tipo['TIPO_MOVIMIENTO'];
    $movimientos = db()->select("SELECT * FROM movimientos WHERE TIPO_MOVIMIENTO = '" . db()->escape($tipo_mov) . "' ORDER BY FECHA DESC");
    $movimientos_por_tipo[$tipo_mov] = $movimientos;
}

// Si no hay movimientos, mostrar mensaje
if (empty($movimientos_por_tipo)) {
    echo '<div style="background:#fff5f5; color:#dc3545; padding:24px; border-radius:12px; margin:40px auto; max-width:600px; text-align:center; font-size:18px;">No hay movimientos registrados en el sistema.</div>';
}

include __DIR__ . '/../layouts/header.php';
?>
<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Movimientos de Inventario</h1>
    </div>
    <?php foreach ($movimientos_por_tipo as $tipo => $movs): ?>
        <div style="margin-bottom:40px;">
            <h2 style="color:#0b1e36; margin-bottom:16px; border-bottom:2px solid #e9ecef; padding-bottom:8px;">Tipo: <?= htmlspecialchars($tipo) ?></h2>
            <table style="width:100%; border-collapse:collapse; background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.08);">
                <thead>
                    <tr style="background:#f0f4ff; color:#0b1e36;">
                        <th style="padding:12px; border-bottom:1px solid #e9ecef;">ID</th>
                        <th style="padding:12px; border-bottom:1px solid #e9ecef;">Producto</th>
                        <th style="padding:12px; border-bottom:1px solid #e9ecef;">Lote</th>
                        <th style="padding:12px; border-bottom:1px solid #e9ecef;">Usuario</th>
                        <th style="padding:12px; border-bottom:1px solid #e9ecef;">Ubicación Origen</th>
                        <th style="padding:12px; border-bottom:1px solid #e9ecef;">Ubicación Destino</th>
                        <th style="padding:12px; border-bottom:1px solid #e9ecef;">Cantidad</th>
                        <th style="padding:12px; border-bottom:1px solid #e9ecef;">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movs as $mov): ?>
                        <tr>
                            <td style="padding:10px; border-bottom:1px solid #e9ecef;"><?= $mov['ID_MOVIMIENTO'] ?></td>
                            <td style="padding:10px; border-bottom:1px solid #e9ecef;"><?= $mov['ID_PRODUCTO'] ?></td>
                            <td style="padding:10px; border-bottom:1px solid #e9ecef;"><?= $mov['ID_LOTE'] ?></td>
                            <td style="padding:10px; border-bottom:1px solid #e9ecef;"><?= $mov['ID_USUARIO'] ?></td>
                            <td style="padding:10px; border-bottom:1px solid #e9ecef;"><?= $mov['ID_UBICACION_ORIGEN'] ?></td>
                            <td style="padding:10px; border-bottom:1px solid #e9ecef;"><?= $mov['ID_UBICACION_DESTINO'] ?></td>
                            <td style="padding:10px; border-bottom:1px solid #e9ecef;"><?= $mov['CANTIDAD'] ?></td>
                            <td style="padding:10px; border-bottom:1px solid #e9ecef;"><?= $mov['FECHA'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
