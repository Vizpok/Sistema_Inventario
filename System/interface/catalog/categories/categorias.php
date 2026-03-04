<?php
/**
 * Catálogo de Categorías - Listado
 * Vista de todas las categorías del sistema
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Configurar variables para el layout
$page_title = 'Catálogo de Categorías';
$base_url = '/Sistema_Inventario';

// Obtener parámetros de búsqueda
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

// Construir consulta
$where = '';
if ($search) {
    $search_escaped = db()->escape($search);
    $where = " WHERE NOMBRE LIKE '%$search_escaped%' OR CODIGO_PREFIJO LIKE '%$search_escaped%'";
}

// Obtener categorías
$categorias = db()->select("
    SELECT 
        c.ID_CATEGORIA,
        c.NOMBRE,
        c.CODIGO_PREFIJO,
        COUNT(p.ID_PRODUCTO) as TOTAL_PRODUCTOS
    FROM categorias c
    LEFT JOIN productos p ON c.ID_CATEGORIA = p.ID_CATEGORIA
    $where
    GROUP BY c.ID_CATEGORIA
    ORDER BY c.NOMBRE ASC
");

include __DIR__ . '/../../layouts/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Catálogo de Categorías</h1>
        <p class="page-subtitle">Gestiona las categorías de productos disponibles</p>
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

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12), 0 8px 24px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #0b1e36;
            margin: 0;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #0b1e36, #1a3a52);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .card-content {
            margin-bottom: 12px;
        }

        .card-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-value {
            font-size: 18px;
            font-weight: 700;
            color: #0b1e36;
            margin-top: 4px;
        }

        .card-footer {
            display: flex;
            gap: 8px;
            padding-top: 16px;
            border-top: 1px solid #eee;
        }

        .btn-action {
            flex: 1;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
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

            .grid-container {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-header">
        <div class="page-header-content">
            <h2 style="margin: 0; font-size: 20px; color: #0b1e36;">Total de categorías: <strong><?= number_format(count($categorias)) ?></strong></h2>
        </div>
        <a href="categorias_nuevo.php" class="btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Categoría
        </a>
    </div>

    <div class="search-section">
        <form style="display: flex; gap: 12px; width: 100%;" method="GET">
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="Buscar por nombre o código..."
                value="<?= $search ?>"
            >
            <button type="submit" class="btn-search"><i class="bi bi-search"></i> Buscar</button>
            <?php if ($search): ?>
            <a href="categorias.php" class="btn-reset"><i class="bi bi-x-circle"></i> Limpiar</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (count($categorias) > 0): ?>
    <div class="grid-container">
        <?php foreach ($categorias as $categoria): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $categoria['NOMBRE'] ?></h3>
                <div class="card-icon">
                    <i class="bi bi-folder"></i>
                </div>
            </div>

            <div class="card-content">
                <div style="margin-bottom: 16px;">
                    <div class="card-label">Código Prefijo</div>
                    <div class="card-value" style="color: #0b5ed7;"><?= $categoria['CODIGO_PREFIJO'] ?></div>
                </div>
                
                <div>
                    <div class="card-label">Productos</div>
                    <div class="card-value"><?= number_format($categoria['TOTAL_PRODUCTOS']) ?></div>
                </div>
            </div>

            <div class="card-footer">
                <a href="categorias_editar.php?id=<?= $categoria['ID_CATEGORIA'] ?>" class="btn-action btn-edit">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <a href="categorias_eliminar.php?id=<?= $categoria['ID_CATEGORIA'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Estás seguro? Esto no afectará los productos existentes.');">
                    <i class="bi bi-trash"></i> Eliminar
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.06); border: 1px solid #f0f0f0;">
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>No se encontraron categorías</h3>
            <p><?= $search ? 'Intenta con otro criterio de búsqueda.' : 'Crea la primera categoría para comenzar.' ?></p>
            <a href="categorias_nuevo.php" class="btn-primary" style="margin-top: 20px;">
                <i class="bi bi-plus-circle"></i> Crear Categoría
            </a>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
