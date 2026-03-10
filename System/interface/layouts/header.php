<?php
/**
 * Layout Header
 * Incluye: DOCTYPE, HEAD, Navbar, Sidebar
 * Debe usarse junto con layouts/footer.php
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?>SGI - Sistema Inventario</title>

    <link rel="stylesheet" href="<?= $base_url ?? '../..' ?>/System/assets/css/styles.css?v=<?= filemtime(__DIR__ . '/../../assets/css/styles.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

    <div class="app-wrapper">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <i class="bi bi-box-seam"></i>
            </div>
            <ul class="sidebar-menu">
                <?php 
                    // Convertir ruta a formato consistente
                    $script_path = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
                    $is_dashboard = strpos($script_path, '/dashboard/') !== false;
                    $is_inventory = strpos($script_path, '/inventory/') !== false && strpos($script_path, '/catalog/') === false;
                    $is_reception = strpos($script_path, '/reception/') !== false;
                    $is_movements = strpos($script_path, '/movements/') !== false;
                    $is_catalog = strpos($script_path, '/catalog/') !== false;
                ?>
                
                <li>
                    <a href="<?= $base_url ?? '/Sistema_Inventario' ?>/System/interface/dashboard/" class="nav-dashboard <?= $is_dashboard ? 'active' : ''; ?>">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?? '/Sistema_Inventario' ?>/System/interface/inventory/" class="<?= $is_inventory ? 'active' : ''; ?>">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Inventario</span>
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?? '/Sistema_Inventario' ?>/System/interface/reception/" class="<?= $is_reception ? 'active' : ''; ?>">
                        <i class="bi bi-box-arrow-in-down"></i>
                        <span>Recepción</span>
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?? '/Sistema_Inventario' ?>/System/interface/movements/movimientos.php/" class="<?= $is_movements ? 'active' : ''; ?>">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Movimientos</span>
                    </a>
                </li>

                <li class="menu-item-with-submenu" id="catalogMenu">
                    <a href="javascript:void(0);" class="menu-toggle <?= $is_catalog ? 'active' : ''; ?>" role="button">
                        <i class="bi bi-journal-text"></i>
                        <span>Catálogo</span>
                        <i class="bi bi-chevron-down chevron-icon"></i>
                    </a>
                    <ul class="submenu" id="catalogSubmenu">
                        <li>
                            <a href="<?= $base_url ?? '/Sistema_Inventario' ?>/System/interface/catalog/products/productos.php" class="nav-catalog-item <?= ($is_catalog && strpos($script_path, 'producto') !== false) ? 'active' : ''; ?>">
                                <i class="bi bi-box-seam"></i>
                                <span>Productos</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= $base_url ?? '/Sistema_Inventario' ?>/System/interface/catalog/categories/categorias.php" class="nav-catalog-item <?= ($is_catalog && strpos($script_path, 'categoria') !== false) ? 'active' : ''; ?>">
                                <i class="bi bi-folder"></i>
                                <span>Categorías</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= $base_url ?? '/Sistema_Inventario' ?>/System/interface/catalog/suppliers/proveedores.php" class="nav-catalog-item <?= ($is_catalog && strpos($script_path, 'proveedor') !== false) ? 'active' : ''; ?>">
                                <i class="bi bi-building"></i>
                                <span>Proveedores</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= $base_url ?? '/Sistema_Inventario' ?>/System/interface/catalog/locations/ubicaciones.php" class="nav-catalog-item <?= ($is_catalog && strpos($script_path, 'ubicacion') !== false) ? 'active' : ''; ?>">
                                <i class="bi bi-geo-alt"></i>
                                <span>Ubicaciones</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="sidebar-footer">
                <a href="<?= $base_url ?? '/Sistema_Inventario' ?>/System/interface/session/logout.php">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Salir</span>
                </a>
            </div>
        </aside>

        <main class="main-content">
            
            <header class="top-header">
                                <button class="sidebar-toggle" title="Alternar sidebar">
                                    <i class="bi bi-list"></i>
                                </button>
                <div class="user-info">
                    <i class="bi bi-person-circle me-2"></i> 
                    <?php 
                        $nombreUsuario = null;

                        if (function_exists('currentUser')) {
                            $user = currentUser();
                            $nombreUsuario = $user['nombre'] ?? null;
                        }

                        if (!$nombreUsuario && isset($_SESSION['user_nombre'])) {
                            $nombreUsuario = $_SESSION['user_nombre'];
                        }

                        echo htmlspecialchars($nombreUsuario ?? 'Usuario');
                    ?>
                </div>
            </header>

            <div class="page-content">
                <!-- El contenido de la página va aquí -->
