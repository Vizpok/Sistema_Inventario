<?php
/**
 * Movimientos - Historial y Trazabilidad
 * Vista de todos los movimientos internos, transferencias y salidas
 */

require_once __DIR__ . '/../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Configurar variables para el layout
$page_title = 'Historial de Movimientos';
$base_url = '/Sistema_Inventario';

// Obtener parámetros de búsqueda
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Construir consulta de búsqueda en 9 campos
$where = '';
if ($search) {
    $search_escaped = db()->escape($search);
    $where = " WHERE (
        m.TIPO_MOVIMIENTO LIKE '%$search_escaped%' OR
        COALESCE(p.NOMBRE, '') LIKE '%$search_escaped%' OR
        COALESCE(p.SKU, '') LIKE '%$search_escaped%' OR
        COALESCE(l.CODIGO_LOTE, '') LIKE '%$search_escaped%' OR
        m.CANTIDAD LIKE '%$search_escaped%' OR
        COALESCE(u_o.CODIGO_UBICACION, '') LIKE '%$search_escaped%' OR
        COALESCE(u_d.CODIGO_UBICACION, '') LIKE '%$search_escaped%' OR
        COALESCE(u.NOMBRE, '') LIKE '%$search_escaped%' OR
        DATE_FORMAT(m.FECHA, '%d/%m/%Y %H:%i') LIKE '%$search_escaped%' OR
        m.FECHA LIKE '%$search_escaped%'
    )";
}

// Obtener total de movimientos
$count_query = db()->select("
    SELECT COUNT(DISTINCT m.ID_MOVIMIENTO) as total 
    FROM movimientos m
    LEFT JOIN productos p ON m.ID_PRODUCTO = p.ID_PRODUCTO
    LEFT JOIN lotes l ON m.ID_LOTE = l.ID_LOTE
    LEFT JOIN usuarios u ON m.ID_USUARIO = u.ID_USUARIO
    LEFT JOIN ubicaciones u_o ON m.ID_UBICACION_ORIGEN = u_o.ID_UBICACION
    LEFT JOIN ubicaciones u_d ON m.ID_UBICACION_DESTINO = u_d.ID_UBICACION
    $where
");
$total = $count_query[0]['total'] ?? 0;
$total_pages = ceil($total / $per_page);

// Obtener movimientos de la página actual
$movimientos = db()->select("
    SELECT 
        m.ID_MOVIMIENTO,
        m.TIPO_MOVIMIENTO,
        m.CANTIDAD,
        m.FECHA,
        p.NOMBRE as PRODUCTO_NOMBRE,
        p.SKU,
        l.CODIGO_LOTE,
        l.FECHA_VENCIMIENTO,
        u.NOMBRE as USUARIO_NOMBRE,
        u_o.CODIGO_UBICACION as UBICACION_ORIGEN,
        u_d.CODIGO_UBICACION as UBICACION_DESTINO
    FROM movimientos m
    LEFT JOIN productos p ON m.ID_PRODUCTO = p.ID_PRODUCTO
    LEFT JOIN lotes l ON m.ID_LOTE = l.ID_LOTE
    LEFT JOIN usuarios u ON m.ID_USUARIO = u.ID_USUARIO
    LEFT JOIN ubicaciones u_o ON m.ID_UBICACION_ORIGEN = u_o.ID_UBICACION
    LEFT JOIN ubicaciones u_d ON m.ID_UBICACION_DESTINO = u_d.ID_UBICACION
    $where
    ORDER BY m.FECHA DESC
    LIMIT $offset, $per_page
");

include __DIR__ . '/./../layouts/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">Historial de Movimientos</h1>
            <p class="page-subtitle">Visualiza el registro de todas las transacciones internas, transferencias y salidas</p>
        </div>
        <div class="page-header-actions">
            <a href="<?= $base_url ?>/System/interface/movements/movimiento_nuevo.php" class="btn-primary">
                <i class="bi bi-plus-lg"></i> Nueva Transferencia
            </a>
            <a href="<?= $base_url ?>/System/interface/movements/venta_nueva.php" class="btn-primary">
                <i class="bi bi-bag-check"></i> Nueva Salida
            </a>
        </div>
    </div>

    <?php if ($alert = getAlert()): ?>
    <div class="alert alert-<?= $alert['type'] ?>" style="margin-bottom: 20px;">
        <i class="bi bi-check-circle"></i> <?= $alert['message'] ?>
    </div>
    <?php endif; ?>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .page-header-content {
            flex: 1;
            min-width: 300px;
        }

        .page-header-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #0b1e36;
        }

        .page-subtitle {
            margin: 8px 0 0 0;
            font-size: 14px;
            color: #6c757d;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0b1e36, #1a3a52);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(11, 30, 54, 0.2);
        }

        .search-section {
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 10px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }

        .search-input:focus {
            outline: none;
            border-color: #0b1e36;
            box-shadow: 0 0 0 3px rgba(11, 30, 54, 0.1);
        }

        .btn-search {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            background: #5a6268;
        }

        .btn-reset {
            background: #e9ecef;
            color: #495057;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-reset:hover {
            background: #dee2e6;
            color: #0b1e36;
        }

        .filter-select {
            padding: 10px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            background: white;
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: #0b1e36;
            box-shadow: 0 0 0 3px rgba(11, 30, 54, 0.1);
        }

        .table-responsive {
            overflow-x: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        th {
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            color: #212529;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-transferencia {
            background: #cfe2ff;
            color: #084298;
        }

        .badge-salida {
            background: #f8d7da;
            color: #842029;
        }

        .badge-recepcion {
            background: #d1e7dd;
            color: #0a3622;
        }

        .table-actions {
            display: flex;
            gap: 8px;
        }

        .btn-small {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            text-decoration: none;
            color: #0b1e36;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: #e9ecef;
        }

        .pagination .current {
            background: #0b1e36;
            color: white;
            border-color: #0b1e36;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .no-data i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #d1e7dd;
            color: #0a3622;
            border: 1px solid #badbcc;
        }

        .alert-error {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }
    </style>

    <!-- Formulario de búsqueda y filtros -->
    <div class="search-section">
        <form method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; width: 100%;">
            <input 
                type="text" 
                name="search" 
                placeholder="Buscar por Tipo, Producto, SKU, Lote, Cantidad, Ubicación, Usuario o Fecha..." 
                value="<?= htmlspecialchars($search) ?>"
                class="search-input"
            >
            
            <button type="submit" class="btn-search">
                <i class="bi bi-search"></i> Buscar
            </button>

            <?php if ($search): ?>
            <a href="movimientos.php" class="btn-reset">
                <i class="bi bi-x-circle"></i> Limpiar
            </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabla de movimientos -->
    <?php if (count($movimientos) > 0): ?>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Producto</th>
                    <th>SKU</th>
                    <th>Lote</th>
                    <th>Cantidad</th>
                    <th>Origen → Destino</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movimientos as $mov): ?>
                <tr>
                    <td>
                        <?php 
                        $type = $mov['TIPO_MOVIMIENTO'];
                        $badge_class = 'badge-' . strtolower($type);
                        ?>
                        <span class="badge <?= $badge_class ?>">
                            <?= $type ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($mov['PRODUCTO_NOMBRE']) ?></td>
                    <td><strong><?= htmlspecialchars($mov['SKU']) ?></strong></td>
                    <td>
                        <small><?= htmlspecialchars($mov['CODIGO_LOTE']) ?></small><br>
                        <?php 
                        if ($mov['FECHA_VENCIMIENTO']) {
                            $venc = new DateTime($mov['FECHA_VENCIMIENTO']);
                            $hoy = new DateTime();
                            $diff = $venc->diff($hoy)->days;
                            $estado = $venc < $hoy ? 'VENCIDO' : ($diff <= 30 ? 'POR VENCER' : 'OK');
                            $color = $venc < $hoy ? '#dc3545' : ($diff <= 30 ? '#ffc107' : '#28a745');
                            echo "<small style='color: $color; font-weight: 600;'>Vto: " . formatDate($mov['FECHA_VENCIMIENTO']) . "</small>";
                        }
                        ?>
                    </td>
                    <td><strong><?= $mov['CANTIDAD'] ?></strong></td>
                    <td>
                        <?php 
                        if ($mov['TIPO_MOVIMIENTO'] === 'TRANSFERENCIA') {
                            echo htmlspecialchars($mov['UBICACION_ORIGEN'] ?? '-') . ' → ' . htmlspecialchars($mov['UBICACION_DESTINO'] ?? '-');
                        } else {
                            echo '—';
                        }
                        ?>
                    </td>
                    <td><?= htmlspecialchars($mov['USUARIO_NOMBRE']) ?></td>
                    <td><?= formatDate($mov['FECHA']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php 
        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $page + 2);

        // Botón anterior
        if ($page > 1) {
            echo '<a href="?search=' . urlencode($search) . '&page=' . ($page - 1) . '">';
            echo '<i class="bi bi-chevron-left"></i> Anterior</a>';
        }

        // Números de página
        if ($start_page > 1) {
            echo '<a href="?search=' . urlencode($search) . '&page=1">1</a>';
            if ($start_page > 2) echo '<span>...</span>';
        }

        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i === $page) {
                echo '<span class="current">' . $i . '</span>';
            } else {
                echo '<a href="?search=' . urlencode($search) . '&page=' . $i . '">' . $i . '</a>';
            }
        }

        if ($end_page < $total_pages) {
            if ($end_page < $total_pages - 1) echo '<span>...</span>';
            echo '<a href="?search=' . urlencode($search) . '&page=' . $total_pages . '">' . $total_pages . '</a>';
        }

        // Botón siguiente
        if ($page < $total_pages) {
            echo '<a href="?search=' . urlencode($search) . '&page=' . ($page + 1) . '">';
            echo 'Siguiente <i class="bi bi-chevron-right"></i></a>';
        }
        ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <div class="table-responsive">
        <div class="no-data">
            <i class="bi bi-inbox"></i>
            <h3>No hay movimientos registrados</h3>
            <p>Comienza creando una nueva transferencia o salida</p>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/./../layouts/footer.php'; ?>
