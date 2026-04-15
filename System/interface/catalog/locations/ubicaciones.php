<?php
/**
 * Catálogo de Ubicaciones - Listado
 * Vista de todas las ubicaciones del almacén
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Configurar variables para el layout
$page_title = 'Catálogo de Ubicaciones';
$base_url = '/Sistema_Inventario';

// Obtener parámetros de búsqueda y paginación
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Construir consulta
$where = '';
if ($search) {
    $search_escaped = db()->escape($search);
    $where = " WHERE u.CODIGO_UBICACION LIKE '%$search_escaped%' OR u.PASILLO LIKE '%$search_escaped%' OR u.ESTANTE LIKE '%$search_escaped%' OR u.NIVEL LIKE '%$search_escaped%'";
}

// Obtener total de ubicaciones
$count_query = db()->select("SELECT COUNT(*) as total FROM ubicaciones u $where");
$total = $count_query[0]['total'] ?? 0;
$total_pages = ceil($total / $per_page);

// Obtener ubicaciones de la página actual
$ubicaciones = db()->select("
    SELECT 
        u.ID_UBICACION,
        u.PASILLO,
        u.ESTANTE,
        u.NIVEL,
        u.CODIGO_UBICACION
    FROM ubicaciones u
    $where
    ORDER BY u.PASILLO ASC, u.ESTANTE ASC, u.NIVEL ASC
    LIMIT $offset, $per_page
");

include __DIR__ . '/../../layouts/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Catálogo de Ubicaciones</h1>
        <p class="page-subtitle">Gestiona los espacios de almacenamiento disponibles</p>
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
        }

        .btn-reset:hover {
            background: #dee2e6;
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

        .codigo-badge {
            background: linear-gradient(135deg, #0b1e36, #1a3a52);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
        }

        .ubicacion-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            font-size: 13px;
        }

        .ubicacion-item {
            background: #f8f9fa;
            padding: 6px 10px;
            border-radius: 4px;
            border-left: 3px solid #0b1e36;
        }

        .ubicacion-label {
            font-weight: 600;
            color: #495057;
            font-size: 11px;
            text-transform: uppercase;
        }

        .ubicacion-value {
            color: #0b1e36;
            font-weight: 700;
        }

        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            margin-right: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #e7f3ff;
            color: #0b5ed7;
        }

        .btn-edit:hover {
            background: #0b5ed7;
            color: white;
        }

        .btn-delete {
            background: #ffe7e7;
            color: #dc3545;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: white;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            text-decoration: none;
            color: #0b1e36;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: #0b1e36;
            color: white;
            border-color: #0b1e36;
        }

        .pagination .active {
            background: #0b1e36;
            color: white;
            border-color: #0b1e36;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            border-left: 4px solid;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-color: #dc3545;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-section {
                flex-direction: column;
            }

            .search-input {
                min-width: auto;
            }

            .table {
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 12px 8px;
            }

            .ubicacion-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .btn-action {
                padding: 4px 8px;
                font-size: 11px;
            }
        }
    </style>

    <div class="page-header">
        <div class="page-header-content">
            <h2 style="margin: 0; font-size: 20px; color: #0b1e36;">Total de ubicaciones: <strong><?= number_format($total) ?></strong></h2>
        </div>
        <a href="ubicaciones_nuevo.php" class="btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Ubicación
        </a>
    </div>

    <div class="search-section">
        <form style="display: flex; gap: 12px; width: 100%;" method="GET">
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="Buscar por código, pasillo, estante o nivel..."
                value="<?= $search ?>"
            >
            <button type="submit" class="btn-search"><i class="bi bi-search"></i> Buscar</button>
            <?php if ($search): ?>
            <a href="ubicaciones.php" class="btn-reset"><i class="bi bi-x-circle"></i> Limpiar</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (count($ubicaciones) > 0): ?>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Ubicación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ubicaciones as $ubicacion): ?>
                <tr>
                    <td><span class="codigo-badge"><?= $ubicacion['CODIGO_UBICACION'] ?></span></td>
                    <td>
                        <div class="ubicacion-grid">
                            <div class="ubicacion-item">
                                <div class="ubicacion-label">Pasillo</div>
                                <div class="ubicacion-value"><?= $ubicacion['PASILLO'] ?></div>
                            </div>
                            <div class="ubicacion-item">
                                <div class="ubicacion-label">Estante</div>
                                <div class="ubicacion-value"><?= $ubicacion['ESTANTE'] ?></div>
                            </div>
                            <div class="ubicacion-item">
                                <div class="ubicacion-label">Nivel</div>
                                <div class="ubicacion-value"><?= $ubicacion['NIVEL'] ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="ubicaciones_editar.php?id=<?= $ubicacion['ID_UBICACION'] ?>" class="btn-action btn-edit">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="ubicaciones_eliminar.php?id=<?= $ubicacion['ID_UBICACION'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar esta ubicación?');">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="?page=1<?= $search ? '&search=' . urlencode($search) : '' ?>"><i class="bi bi-chevron-double-left"></i> Primera</a>
        <a href="?page=<?= $page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><i class="bi bi-chevron-left"></i> Anterior</a>
        <?php endif; ?>

        <?php 
        $start = max(1, $page - 2);
        $end = min($total_pages, $page + 2);
        for ($i = $start; $i <= $end; $i++): 
        ?>
            <?php if ($i == $page): ?>
            <span class="active"><?= $i ?></span>
            <?php else: ?>
            <a href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Siguiente <i class="bi bi-chevron-right"></i></a>
        <a href="?page=<?= $total_pages ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Última <i class="bi bi-chevron-double-right"></i></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <div class="table-container">
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No se encontraron ubicaciones</h3>
            <p><?= $search ? 'Intenta con otro criterio de búsqueda.' : 'Crea la primera ubicación para comenzar.' ?></p>
            <a href="ubicaciones_nuevo.php" class="btn-primary" style="margin-top: 20px;">
                <i class="bi bi-plus-circle"></i> Crear Ubicación
            </a>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
