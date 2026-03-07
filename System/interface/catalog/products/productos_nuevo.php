<?php
/**
 * Catálogo de Productos - Formulario Nuevo
 * Formulario para crear un nuevo producto
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Configurar variables para el layout
$page_title = 'Nuevo Producto';
$base_url = '/Sistema_Inventario';

// Obtener categorías para el select
$categorias = db()->select("SELECT ID_CATEGORIA, NOMBRE FROM categorias ORDER BY NOMBRE ASC");

include __DIR__ . '/../../layouts/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Crear Nuevo Producto</h1>
        <p class="page-subtitle">Completa el formulario para agregar un nuevo producto al catálogo</p>
    </div>

    <style>
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

        .form-container {
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
            max-width: 800px;
        }

        .form-section {
            margin-bottom: 32px;
        }

        .form-section-title {
            font-size: 16px;
            font-weight: 700;
            color: #0b1e36;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e9ecef;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #212529;
            font-size: 14px;
        }

        .form-group label .required {
            color: #dc3545;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #0b1e36;
            box-shadow: 0 0 0 3px rgba(11, 30, 54, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0b1e36, #1a3a52);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(11, 30, 54, 0.2);
        }

        .btn-secondary {
            background: #e9ecef;
            color: #495057;
        }

        .btn-secondary:hover {
            background: #dee2e6;
        }

        .form-help {
            font-size: 12px;
            color: #6c757d;
            margin-top: 6px;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 6px;
            display: none;
        }

        .form-group.error input,
        .form-group.error select {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .form-group.error .error-message {
            display: block;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <form method="POST" action="productos_guardar.php" id="formProducto">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div class="form-container">
            <!-- Información General -->
            <div class="form-section">
                <h3 class="form-section-title">Información General</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="sku">SKU <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="sku" 
                            name="sku" 
                            required
                            placeholder="Ej: PROD-001"
                            maxlength="50"
                        >
                        <div class="form-help">Código único para identificar el producto</div>
                        <div class="error-message">El SKU es requerido y debe ser único</div>
                    </div>

                    <div class="form-group">
                        <label for="codigo_barras">Código de Barras</label>
                        <input 
                            type="text" 
                            id="codigo_barras" 
                            name="codigo_barras" 
                            placeholder="Ej: 7501234567890"
                            maxlength="50"
                            value="<?= isset($_GET['codigo_barras']) ? htmlspecialchars($_GET['codigo_barras']) : '' ?>"
                        >
                        <div class="form-help">Código de barras del producto (opcional)</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre del Producto <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        required
                        placeholder="Ej: Destornillador Phillips #2"
                        maxlength="200"
                    >
                    <div class="form-help">Nombre descriptivo del producto</div>
                    <div class="error-message">El nombre del producto es requerido</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categoria">Categoría <span class="required">*</span></label>
                        <select id="categoria" name="id_categoria" required>
                            <option value="">-- Selecciona una categoría --</option>
                            <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['ID_CATEGORIA'] ?>"><?= $cat['NOMBRE'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="error-message">Debes seleccionar una categoría</div>
                    </div>

                    <div class="form-group">
                        <label for="precio">Precio <span class="required">*</span></label>
                        <input 
                            type="number" 
                            id="precio" 
                            name="precio" 
                            required
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                        >
                        <div class="form-help">Precio de venta del producto</div>
                        <div class="error-message">El precio es requerido y debe ser mayor a 0</div>
                    </div>
                </div>
            </div>

            <!-- Información de Stock -->
            <div class="form-section">
                <h3 class="form-section-title">Información de Stock</h3>

                <div class="form-group">
                    <label for="stock_minimo">Stock Mínimo <span class="required">*</span></label>
                    <input 
                        type="number" 
                        id="stock_minimo" 
                        name="stock_minimo" 
                        required
                        min="1"
                        value="10"
                        placeholder="10"
                    >
                    <div class="form-help">Cantidad mínima antes de generar una alerta de reorden</div>
                    <div class="error-message">El stock mínimo es requerido y debe ser mayor a 0</div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Crear Producto
                </button>
                <a href="productos.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </div>
    </form>

    <script>
        // Validación básica del formulario
        document.getElementById('formProducto').addEventListener('submit', function(e) {
            let isValid = true;
            const sku = document.getElementById('sku').value.trim();
            const nombre = document.getElementById('nombre').value.trim();
            const categoria = document.getElementById('categoria').value;
            const precio = document.getElementById('precio').value;
            const stockMinimo = document.getElementById('stock_minimo').value;

            // Limpiar errores previos
            document.querySelectorAll('.form-group.error').forEach(el => {
                el.classList.remove('error');
            });

            // Validar SKU
            if (!sku) {
                document.getElementById('sku').parentElement.classList.add('error');
                isValid = false;
            }

            // Validar nombre
            if (!nombre) {
                document.getElementById('nombre').parentElement.classList.add('error');
                isValid = false;
            }

            // Validar categoría
            if (!categoria) {
                document.getElementById('categoria').parentElement.classList.add('error');
                isValid = false;
            }

            // Validar precio
            if (!precio || parseFloat(precio) <= 0) {
                document.getElementById('precio').parentElement.classList.add('error');
                isValid = false;
            }

            // Validar stock mínimo
            if (!stockMinimo || parseInt(stockMinimo) < 1) {
                document.getElementById('stock_minimo').parentElement.classList.add('error');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>

</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
