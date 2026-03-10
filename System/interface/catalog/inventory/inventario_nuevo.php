<?php
/**
 * Inventario - Agregar Producto
 */
require_once __DIR__ . '/../../../bootstrap.php';
requireAuth();

$page_title = 'Agregar al Inventario';
$base_url = '/Sistema_Inventario';

// Obtener productos para seleccionar
$productos = db()->select("SELECT ID_PRODUCTO, NOMBRE, SKU FROM productos ORDER BY NOMBRE ASC");
$ubicaciones = db()->select("SELECT ID_UBICACION, CODIGO_UBICACION FROM ubicaciones ORDER BY CODIGO_UBICACION ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = (int)$_POST['id_producto'];
    $id_ubicacion = (int)$_POST['id_ubicacion'];
    $cantidad_total = (int)$_POST['cantidad_total'];
    $id_lote = isset($_POST['id_lote']) ? (int)$_POST['id_lote'] : 1;

    db()->execute("INSERT INTO inventario (ID_PRODUCTO, ID_LOTE, ID_UBICACION, CANTIDAD_TOTAL, CANTIDAD_RESERVADA) VALUES ($id_producto, $id_lote, $id_ubicacion, $cantidad_total, 0)");
    $_SESSION['alert'] = [
        'message' => 'Producto agregado al inventario.',
        'type' => 'success'
    ];
    header('Location: inventario.php');
    exit;
}

include __DIR__ . '/../../layouts/header.php';
?>
<div class="page-container">
    <style>
        .btn-primary {
            background: linear-gradient(135deg, #0b1e36, #1a3a52);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(11,30,54,0.08);
            transition: background 0.3s, box-shadow 0.3s, transform 0.2s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1a3a52, #0b1e36);
            box-shadow: 0 4px 16px rgba(11,30,54,0.15);
            transform: translateY(-2px);
        }
        .btn-primary:disabled {
            background: #b0b8c1;
            color: #fff;
            cursor: not-allowed;
            box-shadow: none;
        }
    </style>
    <div class="page-header">
        <h1 class="page-title">Agregar Producto al Inventario</h1>
    </div>
    <button id="scan-barcode-btn" class="btn-primary" style="margin-bottom:20px;"><i class="bi bi-upc-scan"></i> Escanear Código de Barras</button>
    <div id="scanner-container" style="display:none; margin-bottom:20px; max-width:400px; margin:auto;">
        <div style="background:#f0f4ff; border-radius:18px; box-shadow:0 4px 24px rgba(11,30,54,0.15); padding:24px; border:2px solid #1a3a52; position:relative;">
            <div style="text-align:center; margin-bottom:12px; font-size:18px; color:#0b1e36; font-weight:700;">
                <i class="bi bi-upc-scan" style="font-size:24px; margin-right:8px;"></i> Escaneo en vivo
            </div>
            <div style="position:relative;">
                <div id="scanner-video" style="width:100%; height:260px; border-radius:12px; background:#e9ecef; overflow:hidden; border:2px dashed #0b1e36; box-shadow:0 2px 8px rgba(11,30,54,0.08);"></div>
                <div style="position:absolute; top:50%; left:50%; width:220px; height:60px; transform:translate(-50%,-50%); border:3px solid #28a745; border-radius:8px; pointer-events:none; box-shadow:0 0 8px #28a745; z-index:2;"></div>
                <div style="position:absolute; top:calc(50% + 40px); left:50%; transform:translate(-50%,0); color:#28a745; font-size:14px; font-weight:600; background:#f0f4ff; padding:4px 12px; border-radius:6px; z-index:2;">Coloca el código de barras dentro del marco</div>
            </div>
            <button id="stop-scan-btn" class="btn-primary" style="margin-top:18px; width:100%;"><i class="bi bi-x-circle"></i> Detener Escaneo</button>
        </div>
    </div>

    <!-- Mensaje de resultado -->
    <div id="scan-result" style="display:none; margin-bottom:20px; padding:16px; border-radius:8px; max-width:500px; margin-left:auto; margin-right:auto;"></div>

    <!-- Contenedor para formulario dinámico -->
    <div id="dynamic-form-container"></div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
var csrfTokenPHP = "<?= generateCsrfToken() ?>";
(function() {
    var scannerRunning = false;

    document.getElementById('scan-barcode-btn').addEventListener('click', function() {
        // Limpiar resultados anteriores
        document.getElementById('scan-result').style.display = 'none';
        document.getElementById('dynamic-form-container').innerHTML = '';
        document.getElementById('scanner-container').style.display = 'block';

        Quagga.init({
            inputStream: {
                name: 'Live',
                type: 'LiveStream',
                target: document.getElementById('scanner-video'),
                constraints: { facingMode: 'environment' }
            },
            decoder: {
                readers: ['code_128_reader', 'ean_reader', 'ean_8_reader', 'code_39_reader', 'upc_reader']
            }
        }, function(err) {
            if (err) {
                mostrarResultado('Error al iniciar cámara: ' + err, 'error');
                return;
            }
            Quagga.start();
            scannerRunning = true;
        });
    });

    document.getElementById('stop-scan-btn').addEventListener('click', function() {
        detenerScanner();
    });

    Quagga.onDetected(function(result) {
        var code = result.codeResult.code;
        detenerScanner();
        mostrarResultado('Buscando código: ' + code + '...', 'info');

        fetch('buscar_producto.php?codigo=' + encodeURIComponent(code))
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.existe) {
                    mostrarFormularioCantidad(data, code);
                } else {
                    mostrarFormularioRegistro(code);
                }
            })
            .catch(function(err) {
                mostrarResultado('Error al buscar producto: ' + err, 'error');
            });
    });

    function detenerScanner() {
        if (scannerRunning) {
            Quagga.stop();
            scannerRunning = false;
        }
        document.getElementById('scanner-container').style.display = 'none';
    }

    function mostrarResultado(mensaje, tipo) {
        var el = document.getElementById('scan-result');
        el.style.display = 'block';
        el.style.background = tipo === 'error' ? '#fff5f5' : tipo === 'success' ? '#f0fff4' : '#f0f4ff';
        el.style.color = tipo === 'error' ? '#dc3545' : tipo === 'success' ? '#28a745' : '#0b1e36';
        el.style.border = '1px solid ' + (tipo === 'error' ? '#dc3545' : tipo === 'success' ? '#28a745' : '#0b1e36');
        el.textContent = mensaje;
    }

    // === PRODUCTO EXISTE: pedir cantidad ===
    function mostrarFormularioCantidad(data, code) {
        mostrarResultado('Producto encontrado: ' + data.nombre + ' [' + data.sku + '] — Stock actual: ' + data.cantidad, 'success');
        var container = document.getElementById('dynamic-form-container');
        container.innerHTML = '<div style="background:white; border-radius:12px; padding:32px; max-width:500px; margin:20px auto; box-shadow:0 1px 3px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.06); border:1px solid #f0f0f0;">' +
            '<h3 style="margin-top:0; color:#0b1e36;">Agregar Stock</h3>' +
            '<p style="color:#6c757d;">Producto: <strong>' + data.nombre + '</strong></p>' +
            '<p style="color:#6c757d;">Stock actual: <strong>' + data.cantidad + '</strong></p>' +
            '<div style="margin-bottom:20px;">' +
                '<label style="display:block; font-weight:600; margin-bottom:8px;">¿Cuántas unidades agregar?</label>' +
                '<input type="number" id="cantidad_agregar" min="1" value="1" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">' +
            '</div>' +
            '<button type="button" id="btn-agregar-stock" class="btn-primary" style="width:100%;"><i class="bi bi-plus-circle"></i> Agregar al Stock</button>' +
        '</div>';

        document.getElementById('btn-agregar-stock').addEventListener('click', function() {
            var cantidad = parseInt(document.getElementById('cantidad_agregar').value);
            if (!cantidad || cantidad <= 0) {
                alert('Ingresa una cantidad válida.');
                return;
            }
            this.disabled = true;
            this.textContent = 'Guardando...';

            fetch('agregar_stock.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id_producto=' + data.id_producto + '&cantidad=' + cantidad
            })
            .then(function(resp) { return resp.json(); })
            .then(function(res) {
                if (res.success) {
                    mostrarResultado('Stock actualizado correctamente. Nuevo total: ' + res.nuevo_stock, 'success');
                    document.getElementById('dynamic-form-container').innerHTML = '';
                } else {
                    mostrarResultado('Error: ' + res.message, 'error');
                }
            })
            .catch(function() {
                mostrarResultado('Error de conexión al actualizar stock.', 'error');
            });
        });
    }

    // === PRODUCTO NO EXISTE: formulario de registro ===
    function mostrarFormularioRegistro(code) {
        mostrarResultado('Producto no encontrado. Completa el formulario para registrarlo.', 'error');
        var container = document.getElementById('dynamic-form-container');
        container.innerHTML = '<form method="POST" action="/Sistema_Inventario/System/interface/catalog/products/productos_guardar.php" id="formNuevoProducto" style="background:white; border-radius:12px; padding:32px; max-width:500px; margin:20px auto; box-shadow:0 1px 3px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.06); border:1px solid #f0f0f0;">' +
            '<h3 style="margin-top:0; color:#0b1e36;">Registrar Nuevo Producto</h3>' +
            '<div style="margin-bottom:20px;">' +
                '<label style="display:block; font-weight:600; margin-bottom:8px;">Código de Barras</label>' +
                '<input type="text" name="codigo_barras" value="' + code + '" readonly style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0; background:#f8f9fa;">' +
            '</div>' +
            '<div style="margin-bottom:20px;">' +
                '<label style="display:block; font-weight:600; margin-bottom:8px;">SKU <span style="color:#dc3545;">*</span></label>' +
                '<input type="text" name="sku" required placeholder="Ej: PROD-001" maxlength="50" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">' +
            '</div>' +
            '<div style="margin-bottom:20px;">' +
                '<label style="display:block; font-weight:600; margin-bottom:8px;">Nombre <span style="color:#dc3545;">*</span></label>' +
                '<input type="text" name="nombre" required placeholder="Nombre del producto" maxlength="200" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">' +
            '</div>' +
            '<div style="margin-bottom:20px;">' +
                '<label style="display:block; font-weight:600; margin-bottom:8px;">Categoría <span style="color:#dc3545;">*</span></label>' +
                '<select name="id_categoria" id="select-categoria" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">' +
                    '<option value="">Cargando categorías...</option>' +
                '</select>' +
            '</div>' +
            '<div style="margin-bottom:20px;">' +
                '<label style="display:block; font-weight:600; margin-bottom:8px;">Ubicación <span style="color:#dc3545;">*</span></label>' +
                '<select name="id_ubicacion" id="select-ubicacion" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">' +
                    '<option value="">Cargando ubicaciones...</option>' +
                '</select>' +
                '<div class="form-help">Selecciona la ubicación donde se almacenará el producto</div>' +
            '</div>' +
            '<div style="margin-bottom:20px;">' +
                '<label style="display:block; font-weight:600; margin-bottom:8px;">Precio <span style="color:#dc3545;">*</span></label>' +
                '<input type="number" name="precio" min="0.01" step="0.01" required placeholder="0.00" style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">' +
            '</div>' +
            '<div style="margin-bottom:20px;">' +
                '<label style="display:block; font-weight:600; margin-bottom:8px;">Stock Mínimo</label>' +
                '<input type="number" name="stock_minimo" min="1" value="10" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">' +
            '</div>' +
            '<div style="margin-bottom:20px;">' +
                '<label style="display:block; font-weight:600; margin-bottom:8px;">Cantidad Disponible <span style="color:#dc3545;">*</span></label>' +
                '<input type="number" name="cantidad_disponible" min="1" value="1" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">' +
            '</div>' +
            '<input type="hidden" name="csrf_token" value="' + csrfTokenPHP + '">' +
            '<button type="submit" class="btn-primary" style="width:100%;"><i class="bi bi-check-circle"></i> Registrar Producto</button>' +
        '</form>';

        // Cargar categorías
        fetch('/Sistema_Inventario/System/interface/catalog/products/categorias_list.php')
            .then(function(resp) { return resp.json(); })
            .then(function(cats) {
                var select = document.getElementById('select-categoria');
                select.innerHTML = '<option value="">-- Selecciona una categoría --</option>';
                cats.forEach(function(cat) {
                    var opt = document.createElement('option');
                    opt.value = cat.ID_CATEGORIA;
                    opt.textContent = cat.NOMBRE;
                    select.appendChild(opt);
                });
            })
            .catch(function() {
                document.getElementById('select-categoria').innerHTML = '<option value="">Error al cargar categorías</option>';
            });

        // Cargar ubicaciones
        fetch('/Sistema_Inventario/System/interface/catalog/inventory/ubicaciones_list.php')
            .then(function(resp) { return resp.json(); })
            .then(function(ubics) {
                var select = document.getElementById('select-ubicacion');
                select.innerHTML = '<option value="">-- Selecciona una ubicación --</option>';
                ubics.forEach(function(ubic) {
                    var opt = document.createElement('option');
                    opt.value = ubic.ID_UBICACION;
                    opt.textContent = ubic.CODIGO_UBICACION;
                    select.appendChild(opt);
                });
            })
            .catch(function() {
                document.getElementById('select-ubicacion').innerHTML = '<option value="">Error al cargar ubicaciones</option>';
            });
    }
})();
</script>
