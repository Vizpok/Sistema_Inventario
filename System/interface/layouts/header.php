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
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <i class="bi bi-box-seam"></i>
            </div>
            <ul class="sidebar-menu">
                <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
                
                <li>
                    <a href="<?= $base_url ?? '../..' ?>/dashboard.php" class="<?= ($current_page == 'dashboard.php' || $current_page == 'index.php') ? 'active' : ''; ?>">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?? '../..' ?>/inventario.php" class="<?= ($current_page == 'inventario.php') ? 'active' : ''; ?>">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Inventario</span>
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?? '../..' ?>/recepcion.php" class="<?= ($current_page == 'recepcion.php') ? 'active' : ''; ?>">
                        <i class="bi bi-box-arrow-in-down"></i>
                        <span>Recepción</span>
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?? '../..' ?>/movimientos.php" class="<?= ($current_page == 'movimientos.php') ? 'active' : ''; ?>">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Movimientos</span>
                    </a>
                </li>

                <li>
                    <a href="<?= $base_url ?? '../..' ?>/catalogo.php" class="<?= ($current_page == 'catalogo.php') ? 'active' : ''; ?>">
                        <i class="bi bi-journal-text"></i>
                        <span>Catálogo</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <a href="<?= $base_url ?? '../..' ?>/System/interface/session/logout.php">
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
