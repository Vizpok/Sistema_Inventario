<?php
require_once __DIR__ . '/../../../bootstrap.php';
requireAuth();

$page_title = 'Movimientos';
$base_url = '/Sistema_Inventario';

// Obtener parámetros de búsqueda
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

// Construir consulta con búsqueda
$where = '';
if ($search) {
    $search_escaped = db()->escape($search);
    $where = " WHERE (
        m.TIPO_MOVIMIENTO LIKE '%$search_escaped%' OR
        COALESCE(p.NOMBRE, '') LIKE '%$search_escaped%' OR
        COALESCE(p.SKU, '') LIKE '%$search_escaped%' OR
        COALESCE(l.CODIGO_LOTE, '') LIKE '%$search_escaped%' OR
        m.CANTIDAD LIKE '%$search_escaped%' OR
        COALESCE(uo.CODIGO_UBICACION, '') LIKE '%$search_escaped%' OR
        COALESCE(ud.CODIGO_UBICACION, '') LIKE '%$search_escaped%' OR
        COALESCE(u.NOMBRE, '') LIKE '%$search_escaped%' OR
        DATE_FORMAT(m.FECHA, '%Y-%m-%d') LIKE '%$search_escaped%'
    )";
}

// Obtener movimientos con información detallada
$query = "
    SELECT 
        m.ID_MOVIMIENTO,
        m.TIPO_MOVIMIENTO,
        COALESCE(p.NOMBRE, 'N/A') AS NOMBRE_PRODUCTO,
        COALESCE(p.SKU, '-') AS SKU,
        COALESCE(l.CODIGO_LOTE, '-') AS CODIGO_LOTE,
        m.CANTIDAD,
        COALESCE(uo.CODIGO_UBICACION, 'N/A') AS UBICACION_ORIGEN,
        COALESCE(ud.CODIGO_UBICACION, 'N/A') AS UBICACION_DESTINO,
        COALESCE(u.NOMBRE, 'N/A') AS NOMBRE_USUARIO,
        m.FECHA
    FROM movimientos m
    LEFT JOIN productos p ON m.ID_PRODUCTO = p.ID_PRODUCTO
    LEFT JOIN lotes l ON m.ID_LOTE = l.ID_LOTE
    LEFT JOIN ubicaciones uo ON m.ID_UBICACION_ORIGEN = uo.ID_UBICACION
    LEFT JOIN ubicaciones ud ON m.ID_UBICACION_DESTINO = ud.ID_UBICACION
    LEFT JOIN usuarios u ON m.ID_USUARIO = u.ID_USUARIO
    $where
    ORDER BY m.FECHA DESC
";

$movimientos = db()->select($query);

include __DIR__ . '/../layouts/header.php';
?>
<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Movimientos de Inventario</h1>
        <p class="page-subtitle">Historial de movimientos de stock</p>
    </div>

    <style>
        .search-section {
            margin-bottom: 24px;
        }
        .search-form {
            display: flex;
            gap: 12px;
            width: 100%;
            flex-wrap: wrap;
        }
        .search-input {
            flex: 1;
            min-width: 200px;
            padding: 12px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }
        .search-input:focus {
            outline: none;
            border-color: #0b1e36;
            box-shadow: 0 0 0 3px rgba(11, 30, 54, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #0b1e36, #1a3a52);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(11, 30, 54, 0.15);
        }
        .btn-primary:hover {
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 8px 24px rgba(11, 30, 54, 0.25);
            background: linear-gradient(135deg, #1a3a52, #0b1e36);
        }
        .btn-reset {
            background: #e9ecef;
            color: #495057;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(73, 80, 87, 0.08);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-reset:hover {
            background: #dee2e6;
            color: #0b1e36;
            transform: scale(1.03);
        }
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e0e0e0;
        }
        .table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table td {
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
            color: #212529;
        }
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-info {
            background: #e7f3ff;
            color: #0b5ed7;
        }
        .badge-success {
            background: #d1e7dd;
            color: #0f5132;
        }
        .badge-warning {
            background: #fff3cd;
            color: #664d03;
        }
        .empty-state {
            background: #f8f9fa;
            border: 2px dashed #e9ecef;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            color: #6c757d;
        }
        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }
        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }
            .search-input {
                width: 100%;
            }
            .table {
                font-size: 12px;
            }
            .table th,
            .table td {
                padding: 10px 8px;
            }
        }
    </style>

    <div class="search-section">
        <form method="GET" class="search-form">
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="Buscar por Tipo, Producto, SKU, Lote, Cantidad, Ubicación, Usuario o Fecha..."
                value="<?= htmlspecialchars($search) ?>"
            >
            <button type="submit" class="btn-primary">
                <i class="bi bi-search"></i> Buscar
            </button>
            <?php if ($search): ?>
            <a href="index.php" class="btn-reset">
                <i class="bi bi-x-circle"></i> Limpiar
            </a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($movimientos)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
            <h3 style="margin: 0 0 8px; color: #212529;">No hay movimientos</h3>
            <p style="margin: 0;">No se encontraron movimientos que coincidan con tu búsqueda.</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Lote</th>
                        <th style="text-align: right;">Cantidad</th>
                        <th>Origen → Destino</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movimientos as $mov): ?>
                    <tr>
                        <td>
                            <span class="badge badge-info"><?= htmlspecialchars($mov['TIPO_MOVIMIENTO']) ?></span>
                        </td>
                        <td><strong><?= htmlspecialchars($mov['NOMBRE_PRODUCTO'] ?? 'N/A') ?></strong></td>
                        <td><span class="badge badge-success"><?= htmlspecialchars($mov['SKU'] ?? '-') ?></span></td>
                        <td><?= htmlspecialchars($mov['CODIGO_LOTE'] ?? '-') ?></td>
                        <td style="text-align: right;"><strong><?= number_format($mov['CANTIDAD'] ?? 0) ?></strong></td>
                        <td>
                            <small>
                                <strong><?= htmlspecialchars($mov['UBICACION_ORIGEN'] ?? 'N/A') ?></strong> 
                                <i class="bi bi-arrow-right" style="margin: 0 4px; color: #6c757d;"></i>
                                <strong><?= htmlspecialchars($mov['UBICACION_DESTINO'] ?? 'N/A') ?></strong>
                            </small>
                        </td>
                        <td><?= htmlspecialchars($mov['NOMBRE_USUARIO'] ?? 'N/A') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($mov['FECHA'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p style="margin-top: 16px; color: #6c757d; text-align: right; font-size: 12px;">
            Total de movimientos: <strong><?= count($movimientos) ?></strong>
        </p>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
