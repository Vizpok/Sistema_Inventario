<?php
/**
 * Recepción - Formulario Nueva Recepción
 * Formulario para registrar entrada de productos al almacén
 */

require_once __DIR__ . '/../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Configurar variables para el layout
$page_title = 'Nueva Recepción';
$base_url = '/Sistema_Inventario';

// Obtener productos, proveedores y ubicaciones para los selects
$productos = db()->select("SELECT ID_PRODUCTO, NOMBRE, SKU FROM productos ORDER BY NOMBRE ASC");
$proveedores = db()->select("SELECT ID_PROVEEDOR, NOMBRE FROM proveedores ORDER BY NOMBRE ASC");
$ubicaciones = db()->select("SELECT ID_UBICACION, CODIGO_UBICACION FROM ubicaciones ORDER BY CODIGO_UBICACION ASC");

include __DIR__ . '/../layouts/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Nueva Recepción</h1>
        <p class="page-subtitle">Registra la entrada de productos al almacén desde proveedores</p>
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
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0b1e36;
            box-shadow: 0 0 0 3px rgba(11, 30, 54, 0.1);
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

    <form method="POST" action="<?= $base_url ?>/System/interface/reception/recepcion_procesar.php" id="formRecepcion">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div class="form-container">
            <!-- Información del Producto -->
            <div class="form-section">
                <h3 class="form-section-title">Información del Producto</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="id_producto">Producto <span class="required">*</span></label>
                        <select id="id_producto" name="id_producto" required>
                            <option value="">-- Selecciona un producto --</option>
                            <?php foreach ($productos as $prod): ?>
                            <option value="<?= $prod['ID_PRODUCTO'] ?>">
                                <?= $prod['NOMBRE'] ?> (<?= $prod['SKU'] ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-help">Producto a recibir</div>
                        <div class="error-message">Debes seleccionar un producto</div>
                    </div>

                    <div class="form-group">
                        <label for="id_proveedor">Proveedor <span class="required">*</span></label>
                        <select id="id_proveedor" name="id_proveedor" required>
                            <option value="">-- Selecciona un proveedor --</option>
                            <?php foreach ($proveedores as $prov): ?>
                            <option value="<?= $prov['ID_PROVEEDOR'] ?>"><?= $prov['NOMBRE'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-help">Proveedor del producto</div>
                        <div class="error-message">Debes seleccionar un proveedor</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cantidad">Cantidad <span class="required">*</span></label>
                        <input 
                            type="number" 
                            id="cantidad" 
                            name="cantidad" 
                            required
                            min="1"
                            placeholder="0"
                        >
                        <div class="form-help">Cantidad a recibir</div>
                        <div class="error-message">La cantidad debe ser mayor a 0</div>
                    </div>

                    <div class="form-group">
                        <label for="precio_compra">Precio de Compra <span class="required">*</span></label>
                        <input 
                            type="number" 
                            id="precio_compra" 
                            name="precio_compra" 
                            required
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                        >
                        <div class="form-help">Precio unitario de compra</div>
                        <div class="error-message">El precio debe ser mayor o igual a 0</div>
                    </div>
                </div>
            </div>

            <!-- Información del Lote -->
            <div class="form-section">
                <h3 class="form-section-title">Información del Lote</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="codigo_lote">Código de Lote / Referencia <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="codigo_lote" 
                            name="codigo_lote" 
                            required
                            placeholder="Ej: LOT-2026-001"
                        >
                        <div class="form-help">Código único del lote o referencia de recepción</div>
                        <div class="error-message">Debes ingresar un código de lote válido</div>
                    </div>

                    <div class="form-group">
                        <label for="fecha_vencimiento">Fecha de Vencimiento</label>
                        <input 
                            type="date" 
                            id="fecha_vencimiento" 
                            name="fecha_vencimiento"
                        >
                        <div class="form-help">Fecha de vencimiento del lote (opcional)</div>
                    </div>
                </div>
            </div>

            <!-- Ubicación -->
            <div class="form-section">
                <h3 class="form-section-title">Ubicación de Almacenamiento</h3>

                <div class="form-group">
                    <label for="id_ubicacion">Ubicación <span class="required">*</span></label>
                    <select id="id_ubicacion" name="id_ubicacion" required>
                        <option value="">-- Selecciona una ubicación --</option>
                        <?php foreach ($ubicaciones as $ub): ?>
                        <option value="<?= $ub['ID_UBICACION'] ?>"><?= $ub['CODIGO_UBICACION'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-help">Ubicación donde se almacenará el producto</div>
                    <div class="error-message">Debes seleccionar una ubicación</div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Registrar Recepción
                </button>
                <a href="<?= $base_url ?>/System/interface/reception/recepcion.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </div>
    </form>

    <script>
        // Validación básica del formulario
        document.getElementById('formRecepcion').addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = ['id_producto', 'id_proveedor', 'cantidad', 'precio_compra', 'codigo_lote', 'id_ubicacion'];

            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const formGroup = field.closest('.form-group');
                const errorMsg = formGroup.querySelector('.error-message');

                if (!field.value.trim()) {
                    formGroup.classList.add('error');
                    isValid = false;
                } else {
                    formGroup.classList.remove('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Por favor, completa todos los campos requeridos.');
            }
        });
    </script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>