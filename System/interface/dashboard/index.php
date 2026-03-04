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
$base_url = '/Sistema_Inventario';

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

    <style>
        .dashboard-content {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 30px;
        }
        
        .dashboard-content > .card:nth-child(4) {
            grid-column: 1 / -1;
        }
        
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            border: 1px solid #f0f0f0;
        }
        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12), 0 8px 24px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }
        
        @media (max-width: 1200px) {
            .dashboard-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-content {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .card {
                padding: 20px;
            }
        }
    </style>

    <section class="dashboard-content">

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
                    <p style="margin: 0; font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Valor Total del Almacén</p>
                    <p style="font-size: 36px; font-weight: 700; color: #28a745; margin: 12px 0 0 0;">
                        <?php
                        $valor_query = db()->select("
                            SELECT SUM(p.PRECIO * i.CANTIDAD_DISPONIBLE) as total 
                            FROM inventario i
                            JOIN productos p ON i.ID_PRODUCTO = p.ID_PRODUCTO
                            WHERE i.CANTIDAD_DISPONIBLE > 0
                        ");
                        $valor_total = $valor_query[0]['total'] ?? 0;
                        echo '$' . number_format($valor_total, 2, '.', ',');
                        ?>
                    </p>
                    <p style="margin: 10px 0 0 0; font-size: 12px; color: #6c757d;">Inventario valorizado</p>
                </div>
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 32px; padding-top: 4px;">
                    <i class="bi bi-currency-dollar" style="color: white;"></i>
                </div>
            </div>
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 11px; color: #6c757d;"><i class="bi bi-box-seam"></i> En almacén</span>
                <span style="font-size: 11px; color: #6c757d;"> • </span>
                <span style="font-size: 11px; color: #6c757d;"><i class="bi bi-graph-up"></i> Actualizado hoy</span>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
                    <p style="margin: 0; font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Total de Productos</p>
                    <p style="font-size: 36px; font-weight: 700; color: #0b1e36; margin: 12px 0 0 0;">
                        <?php
                        $total_query = db()->select("
                            SELECT COUNT(DISTINCT ID_PRODUCTO) as total 
                            FROM productos
                        ");
                        $total_productos = $total_query[0]['total'] ?? 0;
                        echo number_format($total_productos);
                        ?>
                    </p>
                    <p style="margin: 10px 0 0 0; font-size: 12px; color: #6c757d;">Artículos catalogados</p>
                </div>
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #0b1e36, #1a3a52); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 32px; padding-top: 4px;">
                    <i class="bi bi-box-seam" style="color: white;"></i>
                </div>
            </div>
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 11px; color: #6c757d;"><i class="bi bi-list-check"></i> En categorías</span>
                <span style="font-size: 11px; color: #6c757d;"> • </span>
                <span style="font-size: 11px; color: #6c757d;">
                    <?php
                    $cat_query = db()->select("SELECT COUNT(*) as total FROM categorias");
                    echo $cat_query[0]['total'] ?? 0;
                    ?> categorías
                </span>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
                    <p style="margin: 0; font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Stock Bajo</p>
                    <p style="font-size: 36px; font-weight: 700; color: <?= $stock_bajo > 0 ? '#dc3545' : '#28a745' ?>; margin: 12px 0 0 0;">
                        <?php
                        $bajo_query = db()->select("
                            SELECT COUNT(DISTINCT i.ID_PRODUCTO) as total 
                            FROM inventario i
                            JOIN productos p ON i.ID_PRODUCTO = p.ID_PRODUCTO
                            WHERE i.CANTIDAD_DISPONIBLE < p.STOCK_MINIMO
                            AND i.CANTIDAD_DISPONIBLE > 0
                        ");
                        $stock_bajo = $bajo_query[0]['total'] ?? 0;
                        echo $stock_bajo;
                        ?>
                    </p>
                    <p style="margin: 10px 0 0 0; font-size: 12px; color: #6c757d;">Requieren reabastecimiento</p>
                </div>
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, <?= $stock_bajo > 0 ? '#dc3545, #fd7e14' : '#28a745, #20c997' ?>); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 32px; padding-top: 4px;">
                    <i class="bi <?= $stock_bajo > 0 ? 'bi-exclamation-triangle' : 'bi-check-circle' ?>" style="color: white;"></i>
                </div>
            </div>
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; display: flex; align-items: center; gap: 10px;">
                <?php if ($stock_bajo > 0): ?>
                    <span style="font-size: 11px; color: #dc3545;"><i class="bi bi-bell"></i> Prioritario</span>
                    <span style="font-size: 11px; color: #6c757d;"> • </span>
                    <span style="font-size: 11px; color: #6c757d;">Revisar ahora</span>
                <?php else: ?>
                    <span style="font-size: 11px; color: #28a745;"><i class="bi bi-check-circle"></i> Todo bien</span>
                    <span style="font-size: 11px; color: #6c757d;"> • </span>
                    <span style="font-size: 11px; color: #6c757d;">No requiere revisión</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="card" style="grid-column: 1 / -1;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h3 style="margin: 0; font-size: 16px; color: #0b1e36; font-weight: 600;"><i class="bi bi-clock-history"></i> Actividad Reciente</h3>
                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #6c757d;">Últimos movimientos registrados en el sistema</p>
                </div>
                <div style="background-color: #f8f9fa; padding: 8px 12px; border-radius: 6px; font-size: 12px; color: #6c757d;">
                    Últimas 24h
                </div>
            </div>
            <div style="margin-top: 15px;">
                <?php
                // Últimos 5 movimientos recientes (iniciamos con 5, JavaScript ajustará según pantalla)
                $actividad_query = db()->select("
                    SELECT 
                        m.ID_MOVIMIENTO,
                        m.TIPO_MOVIMIENTO,
                        m.CANTIDAD,
                        m.FECHA,
                        p.NOMBRE,
                        u.NOMBRE as USUARIO_NOMBRE
                    FROM movimientos m
                    JOIN productos p ON m.ID_PRODUCTO = p.ID_PRODUCTO
                    JOIN usuarios u ON m.ID_USUARIO = u.ID_USUARIO
                    ORDER BY m.FECHA DESC
                    LIMIT 10
                ");
                
                if (!empty($actividad_query)) {
                    ?>
                    <style>
                        .actividad-table-container {
                            width: 100%;
                            overflow-x: auto;
                            -webkit-overflow-scrolling: touch;
                            border-radius: 8px;
                            background-color: #f8f9fa;
                            padding: 0;
                        }
                        
                        .actividad-table {
                            width: 100%;
                            font-size: 12px;
                            border-collapse: collapse;
                            min-width: 600px;
                        }
                        
                        .actividad-table thead tr {
                            border-bottom: 2px solid #e9ecef;
                            background-color: transparent;
                        }
                        
                        .actividad-table thead th {
                            text-align: left;
                            padding: 14px 16px;
                            color: #6c757d;
                            font-weight: 600;
                            font-size: 11px;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                            white-space: nowrap;
                        }
                        
                        .actividad-table tbody tr {
                            border-bottom: 1px solid #e9ecef;
                            transition: background-color 0.2s ease;
                        }
                        
                        .actividad-table tbody tr:hover {
                            background-color: #fff;
                        }
                        
                        .actividad-table tbody td {
                            padding: 14px 16px;
                            vertical-align: middle;
                        }
                        
                        .tipo-movimiento {
                            font-weight: 600;
                            padding: 6px 10px;
                            border-radius: 6px;
                            display: inline-block;
                            white-space: nowrap;
                            font-size: 11px;
                        }
                        
                        .tipo-recepcion {
                            color: #fff;
                            background-color: #28a745;
                        }
                        
                        .tipo-transferencia {
                            color: #0b1e36;
                            background-color: #ffc107;
                        }
                        
                        .tipo-salida {
                            color: #fff;
                            background-color: #dc3545;
                        }
                        
                        /* Responsive: Tablet */
                        @media (max-width: 992px) {
                            .actividad-table {
                                font-size: 11px;
                                min-width: 550px;
                            }
                            
                            .actividad-table thead th,
                            .actividad-table tbody td {
                                padding: 12px 12px;
                            }
                        }
                        
                        /* Responsive: Mobile */
                        @media (max-width: 768px) {
                            .actividad-table {
                                font-size: 10px;
                                min-width: 480px;
                            }
                            
                            .actividad-table thead th,
                            .actividad-table tbody td {
                                padding: 10px 10px;
                            }
                            
                            .actividad-table-container {
                                margin: 0 -15px;
                            }
                        }
                        
                        /* Responsive: Small Mobile */
                        @media (max-width: 480px) {
                            .actividad-table {
                                font-size: 9px;
                                min-width: 420px;
                            }
                            
                            .actividad-table thead th,
                            .actividad-table tbody td {
                                padding: 8px 8px;
                            }
                            
                            .tipo-movimiento {
                                padding: 4px 8px;
                                font-size: 8px;
                            }
                        }
                    </style>
                    
                    <div class="actividad-table-container" id="actividadContainer">
                        <table class="actividad-table" id="actividadTable">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Producto</th>
                                    <th style="text-align: center;">Cantidad</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody id="actividadBody">
                                <?php
                                foreach ($actividad_query as $mov) {
                                    $tipo_class = match($mov['TIPO_MOVIMIENTO']) {
                                        'RECEPCION' => 'tipo-recepcion',
                                        'TRANSFERENCIA' => 'tipo-transferencia',
                                        'SALIDA' => 'tipo-salida',
                                        default => 'tipo-transferencia'
                                    };
                                    
                                    echo '<tr data-id="' . $mov['ID_MOVIMIENTO'] . '">';
                                    echo '<td><span class="tipo-movimiento ' . $tipo_class . '">' . htmlspecialchars($mov['TIPO_MOVIMIENTO']) . '</span></td>';
                                    echo '<td title="' . htmlspecialchars($mov['NOMBRE']) . '">' . htmlspecialchars(substr($mov['NOMBRE'], 0, 30)) . (strlen($mov['NOMBRE']) > 30 ? '...' : '') . '</td>';
                                    echo '<td style="text-align: center;">' . $mov['CANTIDAD'] . '</td>';
                                    echo '<td title="' . htmlspecialchars($mov['USUARIO_NOMBRE']) . '">' . htmlspecialchars(substr($mov['USUARIO_NOMBRE'], 0, 15)) . (strlen($mov['USUARIO_NOMBRE']) > 15 ? '...' : '') . '</td>';
                                    echo '<td>' . date('d/m H:i', strtotime($mov['FECHA'])) . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <script>
                        (function() {
                            const container = document.getElementById('actividadContainer');
                            const table = document.getElementById('actividadTable');
                            const tbody = document.getElementById('actividadBody');
                            const allRows = Array.from(tbody.querySelectorAll('tr'));
                            
                            function adjustRowsPerScreen() {
                                const rows = Array.from(tbody.querySelectorAll('tr'));
                                const containerHeight = container.offsetWidth;
                                
                                // Calcular filas según tamaño de pantalla
                                let visibleRows = 5;
                                if (window.innerWidth <= 480) {
                                    visibleRows = 3; // Mobile pequeño: 3 filas
                                } else if (window.innerWidth <= 768) {
                                    visibleRows = 4; // Tablet: 4 filas
                                } else if (window.innerWidth <= 992) {
                                    visibleRows = 4; // Tablet grande: 4 filas
                                } else {
                                    visibleRows = 5; // Desktop: 5 filas
                                }
                                
                                // Mostrar/ocultar filas
                                rows.forEach((row, index) => {
                                    if (index < visibleRows) {
                                        row.style.display = '';
                                    } else {
                                        row.style.display = 'none';
                                    }
                                });
                            }
                            
                            // Ejecutar al cargar
                            adjustRowsPerScreen();
                            
                            // Re-ajustar en cambio de tamaño de ventana
                            window.addEventListener('resize', adjustRowsPerScreen);
                        })();
                    </script>
                    <?php
                } else {
                    echo '<div style="background-color: #f8f9fa; padding: 30px; text-align: center; border-radius: 8px;">';
                    echo '<i class="bi bi-inbox" style="font-size: 32px; color: #ccc; display: block; margin-bottom: 10px;"></i>';
                    echo '<p style="color: #6c757d; margin: 0; font-size: 14px;">No hay actividad registrada</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

    </section>

    <section style="margin-top: 40px;">
        <h2 style="margin-bottom: 24px; font-size: 18px; font-weight: 600; color: #0b1e36;">Accesos Rápidos</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <a href="<?= $base_url ?>/inventario.php" class="btn btn-primary" style="display: flex; align-items: center; justify-content: center; padding: 16px; border-radius: 10px; border: none; background: linear-gradient(135deg, #0b1e36, #1a3a52); color: white; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(11, 30, 54, 0.15);">
                <i class="bi bi-box-seam" style="margin-right: 8px; font-size: 18px;"></i> Ver Inventario
            </a>
            <a href="<?= $base_url ?>/recepcion.php" class="btn btn-success" style="display: flex; align-items: center; justify-content: center; padding: 16px; border-radius: 10px; border: none; background: linear-gradient(135deg, #28a745, #20c997); color: white; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.15);">
                <i class="bi bi-box-arrow-in-down" style="margin-right: 8px; font-size: 18px;"></i> Nueva Recepción
            </a>
            <a href="<?= $base_url ?>/movimientos.php" class="btn btn-warning" style="display: flex; align-items: center; justify-content: center; padding: 16px; border-radius: 10px; border: none; background: linear-gradient(135deg, #ffc107, #fd7e14); color: white; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(255, 193, 7, 0.15);">
                <i class="bi bi-arrow-left-right" style="margin-right: 8px; font-size: 18px;"></i> Ver Movimientos
            </a>
            <a href="<?= $base_url ?>/catalogo.php" class="btn btn-primary" style="display: flex; align-items: center; justify-content: center; padding: 16px; border-radius: 10px; border: none; background: linear-gradient(135deg, #6f42c1, #7c3aed); color: white; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(111, 66, 193, 0.15);">
                <i class="bi bi-journal-text" style="margin-right: 8px; font-size: 18px;"></i> Administrar Catálogo
            </a>
        </div>
    </section>

</div>

<?php
// Incluir footer
include __DIR__ . '/../layouts/footer.php';
?>
