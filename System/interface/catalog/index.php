<?php
/**
 * Dashboard de Catálogo
 * Acceso rápido a las opciones de catálogo
 */

require_once __DIR__ . '/../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Configurar variables para el layout
$page_title = 'Catálogo';
$base_url = '/Sistema_Inventario';

// Obtener estadísticas del catálogo
$productos = db()->select("SELECT COUNT(*) as total FROM productos")[0]['total'] ?? 0;
$categorias = db()->select("SELECT COUNT(*) as total FROM categorias")[0]['total'] ?? 0;
$proveedores = db()->select("SELECT COUNT(*) as total FROM proveedores")[0]['total'] ?? 0;
$ubicaciones = db()->select("SELECT COUNT(*) as total FROM ubicaciones")[0]['total'] ?? 0;

include __DIR__ . '/../layouts/header.php';
?>

<div class="page-container">

    <div class="page-header">
        <h1 class="page-title">Catálogo del Sistema</h1>
        <p class="page-subtitle">
            Accesso rápido a la gestión de productos, categorías, proveedores y ubicaciones.
        </p>
    </div>

    <style>
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .catalog-card {
            background: #fff;
            border-radius: 12px;
            padding: 28px 20px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .catalog-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12), 0 8px 24px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }

        .catalog-card-icon {
            font-size: 48px;
            margin-bottom: 16px;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-left: auto;
            margin-right: auto;
        }

        .catalog-card-title {
            font-size: 16px;
            font-weight: 600;
            color: #0b1e36;
            margin: 0 0 8px 0;
        }

        .catalog-card-count {
            font-size: 28px;
            font-weight: 700;
            color: #28a745;
            margin: 8px 0;
        }

        .catalog-card-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 8px;
        }

        .catalog-card-productos .catalog-card-icon {
            background: linear-gradient(135deg, #0b1e36, #1a3a52);
            color: white;
        }

        .catalog-card-categorias .catalog-card-icon {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .catalog-card-proveedores .catalog-card-icon {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: white;
        }

        .catalog-card-ubicaciones .catalog-card-icon {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .catalog-card.active {
            border: 2px solid #0b1e36;
            box-shadow: 0 4px 12px rgba(11, 30, 54, 0.2), 0 8px 24px rgba(11, 30, 54, 0.12);
        }

        .catalog-card.active::before {
            content: '';
            position: absolute;
            top: 10px;
            right: 10px;
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #0b1e36, #1a3a52);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

        .catalog-card.active::after {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #0b1e36, #1a3a52);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: bold;
            line-height: 24px;
            text-align: center;
        }

        @media (max-width: 1200px) {
            .catalog-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .catalog-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .catalog-card {
                padding: 24px 18px;
            }
        }
    </style>

    <section class="catalog-grid">
        <a href="products/productos.php" class="catalog-card catalog-card-productos">
            <div class="catalog-card-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <h3 class="catalog-card-title">Productos</h3>
            <p class="catalog-card-count"><?= number_format($productos) ?></p>
            <p class="catalog-card-label">Artículos catalogados</p>
        </a>

        <a href="categories/categorias.php" class="catalog-card catalog-card-categorias">
            <div class="catalog-card-icon">
                <i class="bi bi-folder"></i>
            </div>
            <h3 class="catalog-card-title">Categorías</h3>
            <p class="catalog-card-count"><?= number_format($categorias) ?></p>
            <p class="catalog-card-label">Tipos de productos</p>
        </a>

        <a href="suppliers/proveedores.php" class="catalog-card catalog-card-proveedores">
            <div class="catalog-card-icon">
                <i class="bi bi-building"></i>
            </div>
            <h3 class="catalog-card-title">Proveedores</h3>
            <p class="catalog-card-count"><?= number_format($proveedores) ?></p>
            <p class="catalog-card-label">Fuentes de suministro</p>
        </a>

        <a href="locations/ubicaciones.php" class="catalog-card catalog-card-ubicaciones">
            <div class="catalog-card-icon">
                <i class="bi bi-geo-alt"></i>
            </div>
            <h3 class="catalog-card-title">Ubicaciones</h3>
            <p class="catalog-card-count"><?= number_format($ubicaciones) ?></p>
            <p class="catalog-card-label">Espacios de almacén</p>
        </a>
    </section>

</div>

<script>
    // Marcar la tarjeta activa basado en la última página visitada
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.catalog-card');
        const lastVisited = sessionStorage.getItem('lastCatalogPage');
        
        // Marcar la tarjeta correspondiente como activa
        cards.forEach(card => {
            const href = card.getAttribute('href');
            if (lastVisited && href.includes(lastVisited)) {
                card.classList.add('active');
                card.style.position = 'relative';
            }
        });
        
        // Guardar la página cuando se hace clic en una tarjeta
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                // Determinar el tipo de página
                if (href.includes('producto')) {
                    sessionStorage.setItem('lastCatalogPage', 'producto');
                } else if (href.includes('categoria')) {
                    sessionStorage.setItem('lastCatalogPage', 'categoria');
                } else if (href.includes('proveedor')) {
                    sessionStorage.setItem('lastCatalogPage', 'proveedor');
                } else if (href.includes('ubicacion')) {
                    sessionStorage.setItem('lastCatalogPage', 'ubicacion');
                }
            });
        });
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
