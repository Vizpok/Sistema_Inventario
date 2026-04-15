    <style>
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
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(73, 80, 87, 0.08);
        }
        .btn-reset:hover {
            background: #dee2e6;
            color: #0b1e36;
            transform: scale(1.03);
        }
    </style>

<?php
/**
 * Inventario - Listado
 * Vista de todos los productos en inventario
 */

require_once __DIR__ . '/../../../bootstrap.php';
requireAuth();

$page_title = 'Inventario';
$base_url = '/Sistema_Inventario';

// Obtener productos del inventario

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$where = '';
if ($search) {
    $search_escaped = db()->escape($search);
    $where = " WHERE (
        p.SKU LIKE '%$search_escaped%' OR
        p.NOMBRE LIKE '%$search_escaped%' OR
        c.NOMBRE LIKE '%$search_escaped%' OR
        i.CANTIDAD_TOTAL LIKE '%$search_escaped%' OR
        i.CANTIDAD_DISPONIBLE LIKE '%$search_escaped%' OR
        l.CODIGO_UBICACION LIKE '%$search_escaped%'
    )";
}
$productos = db()->select("SELECT i.ID_INVENTARIO, p.ID_PRODUCTO, p.NOMBRE, p.SKU, c.NOMBRE AS CATEGORIA, i.CANTIDAD_TOTAL, i.CANTIDAD_DISPONIBLE, l.CODIGO_UBICACION FROM productos p LEFT JOIN categorias c ON p.ID_CATEGORIA = c.ID_CATEGORIA INNER JOIN inventario i ON p.ID_PRODUCTO = i.ID_PRODUCTO LEFT JOIN ubicaciones l ON i.ID_UBICACION = l.ID_UBICACION $where ORDER BY p.NOMBRE ASC");

include __DIR__ . '/../../layouts/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Inventario</h1>
        <p class="page-subtitle">Listado de productos en inventario</p>
        <a href="inventario_nuevo.php" class="btn-primary" style="margin-left:auto;">
            <i class="bi bi-plus-circle"></i> Agregar Producto
        </a>
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
            padding: 16px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
            color: #212529;
        }
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        .sku-badge {
            background: #e7f3ff;
            color: #0b5ed7;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: stretch;
            }
            .table {
                font-size: 12px;
            }
            .table th,
            .table td {
                padding: 12px 8px;
            }
        }
    </style>

    <div class="search-section">
        <form style="display: flex; gap: 12px; width: 100%; margin-bottom: 24px;" method="GET">
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="Buscar por SKU, Nombre, Categoría, Cantidad o Ubicación..."
                value="<?= $search ?>"
            >
            <button type="submit" class="btn-primary"><i class="bi bi-search"></i> Buscar</button>
            <?php if ($search): ?>
            <a href="inventario.php" class="btn-reset"><i class="bi bi-x-circle"></i> Limpiar</a>
            <?php endif; ?>
        </form>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Stock Total</th>
                    <th>Disponible</th>
                    <th>Ubicación</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><span class="sku-badge"><?= $producto['SKU'] ?></span></td>
                    <td><strong><?= $producto['NOMBRE'] ?></strong></td>
                    <td><?= $producto['CATEGORIA'] ?? '-' ?></td>
                    <td><?= number_format($producto['CANTIDAD_TOTAL'] ?? 0) ?></td>
                    <td><?= number_format($producto['CANTIDAD_DISPONIBLE'] ?? 0) ?></td>
                    <td><?= $producto['CODIGO_UBICACION'] ?? '-' ?></td>
                    <td style="text-align: center; display: flex; gap: 8px; justify-content: center;">
                        <a href="inventario_editar.php?id=<?= $producto['ID_INVENTARIO'] ?>" class="btn-primary" style="padding:6px 12px; font-size:12px; border-radius:6px;">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="inventario_eliminar.php?id=<?= $producto['ID_INVENTARIO'] ?>" class="btn-primary" style="padding:6px 12px; font-size:12px; border-radius:6px; background: linear-gradient(135deg, #dc3545, #c82333);" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto del inventario?');">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
