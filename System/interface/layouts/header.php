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

    <link rel="stylesheet" href="<?= $base_url ?? '../..' ?>/System/assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

    <div class="app-wrapper">
        
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="bi bi-list fs-4"></i>
            </div>
            
            <ul class="sidebar-menu">
                <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
                
                <li>
                    <a href="<?= $base_url ?? '../..' ?>/dashboard.php" class="<?= ($current_page == 'dashboard.php' || $current_page == 'index.php') ? 'active' : ''; ?>">
                        <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?? '../..' ?>/inventario.php" class="<?= ($current_page == 'inventario.php') ? 'active' : ''; ?>">
                        <i class="bi bi-box-seam-fill me-2"></i> Inventario
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?? '../..' ?>/recepcion.php" class="<?= ($current_page == 'recepcion.php') ? 'active' : ''; ?>">
                        <i class="bi bi-box-arrow-in-down me-2"></i> Recepción
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?? '../..' ?>/movimientos.php" class="<?= ($current_page == 'movimientos.php') ? 'active' : ''; ?>">
                        <i class="bi bi-arrow-left-right me-2"></i> Movimientos
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?? '../..' ?>/catalogo.php" class="<?= ($current_page == 'catalogo.php') ? 'active' : ''; ?>">
                        <i class="bi bi-journal-text me-2"></i> Catálogo
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <a href="<?= $base_url ?? '../..' ?>/System/interface/session/login.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Salir
                </a>
            </div>
        </aside>

        <main class="main-content">
            
            <header class="top-header">
                <div class="user-info">
                    <i class="bi bi-person-circle me-2"></i> 
                    <?php 
                        if (function_exists('currentUser')) {
                            $user = currentUser();
                            echo $user ? htmlspecialchars($user['nombre']) : 'Usuario';
                        } else {
                            echo 'Usuario';
                        }
                    ?>
                </div>
            </header>

            <div class="page-content">
                <!-- El contenido de la página va aquí -->
