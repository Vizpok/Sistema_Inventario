<?php
/**
 * Movimientos - Formulario Nueva Salida/Venta
 * Formulario para registrar una salida de producto por venta
 */

require_once __DIR__ . '/../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Configurar variables para el layout
$page_title = 'Nueva Salida';
$base_url = '/Sistema_Inventario';

// Obtener productos
$productos = db()->select("SELECT ID_PRODUCTO, NOMBRE, SKU FROM productos ORDER BY NOMBRE ASC");

include __DIR__ . '/./../layouts/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Nueva Salida/Venta</h1>
        <p class="page-subtitle">Registra la salida de un producto por venta o retiro</p>
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

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #0b5ed7;
            padding: 12px 16px;
            border-radius: 6px;
            margin-top: 16px;
            font-size: 13px;
            color: #084298;
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

    <form method="POST" action="<?= $base_url ?>/System/interface/movements/venta_procesar.php" id="formVenta">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div class="form-container">
            <!-- Selección de Producto -->
            <div class="form-section">
                <h3 class="form-section-title">Seleccionar Producto</h3>

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
                    <div class="form-help">Producto a vender o retirar</div>
                    <div class="error-message">Debes seleccionar un producto</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="id_lote">Lote <span class="required">*</span></label>
                        <select id="id_lote" name="id_lote" required disabled>
                            <option value="">-- Selecciona un producto primero --</option>
                        </select>
                        <div class="form-help">Lote del producto</div>
                        <div class="error-message">Debes seleccionar un lote válido</div>
                    </div>

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
                        <div class="form-help">Cantidad a vender o retirar</div>
                        <div class="error-message">La cantidad debe ser mayor a 0</div>
                    </div>
                </div>

                <div id="disponibleInfo" class="info-box" style="display: none;">
                    Disponible: <strong id="cantidadDisponible">0</strong> unidades
                </div>
            </div>

            <!-- Ubicación de Origen -->
            <div class="form-section">
                <h3 class="form-section-title">Ubicación</h3>

                <div class="form-group">
                    <label for="id_ubicacion_origen">Ubicación <span class="required">*</span></label>
                    <select id="id_ubicacion_origen" name="id_ubicacion_origen" required disabled>
                        <option value="">-- Selecciona producto y lote --</option>
                    </select>
                    <div class="form-help">Ubicación de donde se retira el producto</div>
                    <div class="error-message">Debes seleccionar una ubicación válida</div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Registrar Salida
                </button>
                <a href="<?= $base_url ?>/System/interface/movements/movimientos.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </div>
    </form>

    <script>
        const productoSelect = document.getElementById('id_producto');
        const loteSelect = document.getElementById('id_lote');
        const ubicacionOrigenSelect = document.getElementById('id_ubicacion_origen');
        const cantidadInput = document.getElementById('cantidad');
        const disponibleInfo = document.getElementById('disponibleInfo');
        const cantidadDisponibleSpan = document.getElementById('cantidadDisponible');

        // Cargar lotes cuando se selecciona un producto
        productoSelect.addEventListener('change', async function() {
            const idProducto = this.value;
            loteSelect.innerHTML = '<option value="">-- Cargando lotes --</option>';
            ubicacionOrigenSelect.innerHTML = '<option value="">-- Selecciona un lote --</option>';
            loteSelect.disabled = true;
            ubicacionOrigenSelect.disabled = true;
            disponibleInfo.style.display = 'none';

            if (!idProducto) {
                loteSelect.innerHTML = '<option value="">-- Selecciona un producto --</option>';
                return;
            }

            try {
                const url = '<?= $base_url ?>/System/interface/movements/api_lotes.php';
                console.log('Haciendo fetch a:', url, 'con producto:', idProducto);
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id_producto=' + encodeURIComponent(idProducto)
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor: ' + response.statusText);
                }

                const data = await response.json();
                console.log('Data recibida:', data);

                if (data.success && data.lotes.length > 0) {
                    loteSelect.innerHTML = '<option value="">-- Selecciona un lote --</option>';
                    data.lotes.forEach(lote => {
                        const option = document.createElement('option');
                        option.value = lote.ID_LOTE;
                        option.textContent = lote.CODIGO_LOTE + ' (Vencimiento: ' + lote.FECHA_VENCIMIENTO + ')';
                        loteSelect.appendChild(option);
                    });
                    loteSelect.disabled = false;
                } else {
                    loteSelect.innerHTML = '<option value="">No hay lotes para este producto</option>';
                }
            } catch (error) {
                console.error('Error cargando lotes:', error);
                loteSelect.innerHTML = '<option value="">Error al cargar lotes</option>';
            }
        });

        // Cargar ubicaciones cuando se selecciona un lote
        loteSelect.addEventListener('change', async function() {
            const idProducto = productoSelect.value;
            const idLote = this.value;
            ubicacionOrigenSelect.innerHTML = '<option value="">-- Cargando ubicaciones --</option>';
            ubicacionOrigenSelect.disabled = true;
            disponibleInfo.style.display = 'none';

            if (!idProducto || !idLote) {
                ubicacionOrigenSelect.innerHTML = '<option value="">-- Selecciona producto y lote --</option>';
                return;
            }

            try {
                const response = await fetch('<?= $base_url ?>/System/interface/movements/api_ubicaciones.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id_producto=' + encodeURIComponent(idProducto) + '&id_lote=' + encodeURIComponent(idLote)
                });

                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor: ' + response.statusText);
                }

                const data = await response.json();

                if (data.success && data.ubicaciones.length > 0) {
                    ubicacionOrigenSelect.innerHTML = '<option value="">-- Selecciona una ubicación --</option>';
                    data.ubicaciones.forEach(ub => {
                        const option = document.createElement('option');
                        option.value = ub.ID_UBICACION;
                        option.textContent = ub.CODIGO_UBICACION + ' (Disponible: ' + ub.CANTIDAD_DISPONIBLE + ')';
                        option.dataset.disponible = ub.CANTIDAD_DISPONIBLE;
                        ubicacionOrigenSelect.appendChild(option);
                    });
                    ubicacionOrigenSelect.disabled = false;
                } else {
                    ubicacionOrigenSelect.innerHTML = '<option value="">No hay ubicaciones con inventario</option>';
                }
            } catch (error) {
                console.error('Error cargando ubicaciones:', error);
                ubicacionOrigenSelect.innerHTML = '<option value="">Error al cargar ubicaciones</option>';
            }
        });

        // Mostrar disponibilidad cuando se selecciona ubicación origen
        ubicacionOrigenSelect.addEventListener('change', function() {
            const disponible = this.options[this.selectedIndex]?.dataset.disponible || 0;
            if (this.value) {
                cantidadDisponibleSpan.textContent = disponible;
                disponibleInfo.style.display = 'block';
            } else {
                disponibleInfo.style.display = 'none';
            }
        });

        // Validación del formulario
        document.getElementById('formVenta').addEventListener('submit', function(e) {
            let isValid = true;
            document.querySelectorAll('.form-group.error').forEach(el => {
                el.classList.remove('error');
            });

            const idProducto = document.getElementById('id_producto').value;
            const idLote = document.getElementById('id_lote').value;
            const cantidad = document.getElementById('cantidad').value;
            const idUbicacionOrigen = document.getElementById('id_ubicacion_origen').value;

            if (!idProducto) {
                document.getElementById('id_producto').parentElement.classList.add('error');
                isValid = false;
            }

            if (!idLote) {
                document.getElementById('id_lote').parentElement.classList.add('error');
                isValid = false;
            }

            if (!cantidad || parseInt(cantidad) < 1) {
                document.getElementById('cantidad').parentElement.classList.add('error');
                isValid = false;
            }

            if (!idUbicacionOrigen) {
                document.getElementById('id_ubicacion_origen').parentElement.classList.add('error');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</div>

<?php include __DIR__ . '/./../layouts/footer.php'; ?>
