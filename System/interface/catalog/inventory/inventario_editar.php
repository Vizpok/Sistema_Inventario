<?php
/**
 * Inventario - Editar Cantidad
 */
require_once __DIR__ . '/../../../bootstrap.php';
requireAuth();

$page_title = 'Editar Inventario';
$base_url = '/Sistema_Inventario';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$item = db()->select("SELECT i.ID_INVENTARIO, p.NOMBRE, p.SKU, i.CANTIDAD_TOTAL, i.CANTIDAD_RESERVADA, i.ID_PRODUCTO, i.ID_UBICACION, (i.CANTIDAD_TOTAL - i.CANTIDAD_RESERVADA) AS CANTIDAD_DISPONIBLE FROM inventario i LEFT JOIN productos p ON i.ID_PRODUCTO = p.ID_PRODUCTO WHERE i.ID_INVENTARIO = $id");
$item = $item[0] ?? null;
$ubicaciones = db()->select("SELECT ID_UBICACION, CODIGO_UBICACION FROM ubicaciones ORDER BY CODIGO_UBICACION ASC");

if (!$item) {
    $_SESSION['alert'] = [
        'message' => 'Registro no encontrado.',
        'type' => 'error'
    ];
    header('Location: inventario.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cantidad_total = (int)$_POST['cantidad_total'];
    $cantidad_disponible = (int)$_POST['cantidad_disponible'];
    $id_ubicacion = (int)$_POST['id_ubicacion'];
    
    // Validar que cantidad disponible no sea mayor a cantidad total
    if ($cantidad_disponible > $cantidad_total) {
        $_SESSION['alert'] = [
            'message' => 'Error: La cantidad disponible no puede ser mayor que el stock total.',
            'type' => 'error'
        ];
        header('Location: inventario_editar.php?id=' . $id);
        exit;
    }
    
    // Validar que cantidad disponible no sea negativa
    if ($cantidad_disponible < 0) {
        $_SESSION['alert'] = [
            'message' => 'Error: La cantidad disponible no puede ser negativa.',
            'type' => 'error'
        ];
        header('Location: inventario_editar.php?id=' . $id);
        exit;
    }
    
    // CANTIDAD_DISPONIBLE se calcula como: CANTIDAD_TOTAL - CANTIDAD_RESERVADA
    // Por lo tanto, si queremos que sea X, CANTIDAD_RESERVADA debe ser: CANTIDAD_TOTAL - X
    $cantidad_reservada = $cantidad_total - $cantidad_disponible;
    
    db()->execute("UPDATE inventario SET CANTIDAD_TOTAL = $cantidad_total, CANTIDAD_RESERVADA = $cantidad_reservada, ID_UBICACION = $id_ubicacion WHERE ID_INVENTARIO = $id");
    $_SESSION['alert'] = [
        'message' => 'Stock actualizado correctamente.',
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
        .btn-reset {
            background: #e9ecef;
            color: #495057;
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
        .btn-reset:hover {
            background: #dee2e6;
            box-shadow: 0 4px 16px rgba(11,30,54,0.12);
            transform: translateY(-2px);
        }
    </style>
    <div class="page-header">
        <h1 class="page-title">Editar Inventario</h1>
    </div>
    <button id="scan-barcode-btn" class="btn-primary" style="margin-bottom:20px;">Escanear Código de Barras</button>
    <div id="scanner-container" style="display:none; margin-bottom:20px; max-width:500px; margin-left:auto; margin-right:auto;">
        <div style="background:#f0f4ff; border-radius:18px; box-shadow:0 4px 24px rgba(11,30,54,0.15); padding:24px; border:2px solid #1a3a52;">
            <div style="text-align:center; margin-bottom:12px; font-size:18px; color:#0b1e36; font-weight:700;">
                <i class="bi bi-upc-scan" style="font-size:24px; margin-right:8px;"></i> Escaneo en vivo
            </div>
            <div style="position:relative;">
                <div id="scanner-video" style="width:100%; height:300px; border-radius:12px; background:#e9ecef; overflow:hidden; border:2px dashed #0b1e36; box-shadow:0 2px 8px rgba(11,30,54,0.08);"></div>
                <div style="position:absolute; top:50%; left:50%; width:220px; height:60px; transform:translate(-50%,-50%); border:3px solid #28a745; border-radius:8px; pointer-events:none; box-shadow:0 0 8px #28a745; z-index:2;"></div>
                <div style="position:absolute; top:calc(50% + 40px); left:50%; transform:translate(-50%,0); color:#28a745; font-size:14px; font-weight:600; background:#f0f4ff; padding:4px 12px; border-radius:6px; z-index:2;">Coloca el código de barras</div>
            </div>
            <div id="scan-result" style="margin-top:15px; font-weight:bold; text-align:center; color:#0b1e36; min-height:20px;"></div>
            <button id="stop-scan-btn" class="btn-reset" style="margin-top:18px; width:100%;"><i class="bi bi-x-circle"></i> Detener Escaneo</button>
        </div>
    </div>
    <form method="POST" style="background:white; border-radius:12px; padding:32px; max-width:500px; margin:auto; box-shadow:0 1px 3px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.06); border:1px solid #f0f0f0;">
        <div style="margin-bottom:20px;">
            <label style="display:block; font-weight:600; margin-bottom:8px;">Producto</label>
            <input type="text" id="sku-input" name="sku" value="<?= '[' . $item['SKU'] . '] ' . $item['NOMBRE'] ?>" style="width:100%; padding:12px; border-radius:8px; background:#f8f9fa;" readonly>
        </div>
        <div style="margin-bottom:20px;">
            <label style="display:block; font-weight:600; margin-bottom:8px;">Ubicación</label>
            <select name="id_ubicacion" required style="width:100%; padding:12px; border-radius:8px;">
                <?php foreach ($ubicaciones as $u): ?>
                <option value="<?= $u['ID_UBICACION'] ?>" <?= $item['ID_UBICACION'] == $u['ID_UBICACION'] ? 'selected' : '' ?>><?= $u['CODIGO_UBICACION'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="margin-bottom:20px;">
            <label style="display:block; font-weight:600; margin-bottom:8px;">Stock Total <span style="color:#dc3545;">*</span></label>
            <input type="number" id="cantidad_total" name="cantidad_total" min="0" value="<?= $item['CANTIDAD_TOTAL'] ?>" required style="width:100%; padding:12px; border-radius:8px;">
            <small style="color:#6c757d; display:block; margin-top:6px;">Cantidad de unidades almacenadas</small>
        </div>
        <div style="margin-bottom:20px;">
            <label style="display:block; font-weight:600; margin-bottom:8px;">Cantidad Disponible <span style="color:#dc3545;">*</span></label>
            <input type="number" id="cantidad_disponible" name="cantidad_disponible" min="0" value="<?= $item['CANTIDAD_DISPONIBLE'] ?>" required style="width:100%; padding:12px; border-radius:8px;">
            <small style="color:#6c757d; display:block; margin-top:6px;">Unidades disponibles para venta (no debe ser mayor que el stock total)</small>
        </div>
        <div id="error-message" style="margin-bottom:20px; padding:12px; border-radius:8px; background:#fff5f5; border:1px solid #dc3545; color:#dc3545; display:none; font-size:14px;"></div>
        <button type="submit" class="btn-primary" style="width:100%;"><i class="bi bi-pencil"></i> Guardar Cambios</button>
    </form>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
// Validación de cantidades
document.getElementById('cantidad_total').addEventListener('change', function() {
    var cantidadTotal = parseInt(this.value);
    var cantidadDisponible = parseInt(document.getElementById('cantidad_disponible').value);
    
    if (cantidadDisponible > cantidadTotal) {
        document.getElementById('cantidad_disponible').value = cantidadTotal;
        mostrarError('La cantidad disponible ha sido ajustada al stock total');
    }
});

document.getElementById('cantidad_disponible').addEventListener('change', function() {
    var cantidadDisponible = parseInt(this.value);
    var cantidadTotal = parseInt(document.getElementById('cantidad_total').value);
    var errorDiv = document.getElementById('error-message');
    
    if (cantidadDisponible > cantidadTotal) {
        errorDiv.textContent = 'Error: La cantidad disponible no puede ser mayor que el stock total (' + cantidadTotal + ')';
        errorDiv.style.display = 'block';
        this.style.borderColor = '#dc3545';
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            return false;
        }, {once: true});
    } else if (cantidadDisponible < 0) {
        errorDiv.textContent = 'Error: La cantidad disponible no puede ser negativa';
        errorDiv.style.display = 'block';
        this.style.borderColor = '#dc3545';
    } else {
        errorDiv.style.display = 'none';
        this.style.borderColor = '#e0e0e0';
    }
});

// Validar al enviar el formulario
document.querySelector('form').addEventListener('submit', function(e) {
    var cantidadTotal = parseInt(document.getElementById('cantidad_total').value);
    var cantidadDisponible = parseInt(document.getElementById('cantidad_disponible').value);
    
    if (cantidadDisponible > cantidadTotal) {
        e.preventDefault();
        mostrarError('La cantidad disponible no puede ser mayor que el stock total');
        return false;
    }
    if (cantidadDisponible < 0) {
        e.preventDefault();
        mostrarError('La cantidad disponible no puede ser negativa');
        return false;
    }
});

function mostrarError(mensaje) {
    var errorDiv = document.getElementById('error-message');
    errorDiv.textContent = mensaje;
    errorDiv.style.display = 'block';
}

document.getElementById('scan-barcode-btn').addEventListener('click', function() {
    document.getElementById('scanner-container').style.display = 'block';
    Quagga.init({
        inputStream: {
            name: 'Live',
            type: 'LiveStream',
            target: document.getElementById('scanner-video'),
            constraints: {
                facingMode: 'environment'
            }
        },
        decoder: {
            readers: ['code_128_reader', 'ean_reader', 'ean_8_reader', 'code_39_reader', 'upc_reader']
        }
    }, function(err) {
        if (err) {
            document.getElementById('scan-result').textContent = 'Error al iniciar cámara: ' + err;
            return;
        }
        Quagga.start();
    });
});
document.getElementById('stop-scan-btn').addEventListener('click', function() {
    Quagga.stop();
    document.getElementById('scanner-container').style.display = 'none';
    document.getElementById('scan-result').textContent = '';
});
Quagga.onDetected(function(result) {
    var code = result.codeResult.code;
    var resultDiv = document.getElementById('scan-result');
    resultDiv.innerHTML = 'Código detectado: ' + code;
    document.getElementById('sku-input').value = code;
    fetch('buscar_producto.php?codigo=' + encodeURIComponent(code))
        .then(response => response.json())
        .then(data => {
            if (data.existe) {
                resultDiv.innerHTML = 'Producto encontrado: ' + data.nombre + '. Cantidad actual: ' + data.cantidad +
                    '<div style="margin-top:15px;"><label style="display:block; margin-bottom:8px; font-weight:600;">¿Cuántas unidades agregar al stock?</label>' +
                    '<input type="number" id="cantidad_agregar" min="1" style="width:100%; padding:10px; border-radius:8px; margin-bottom:10px;" value="1">' +
                    '<button id="btn-agregar-stock" class="btn-primary" style="width:100%;">Agregar al stock</button></div>';
                document.getElementById('btn-agregar-stock').addEventListener('click', function() {
                    let cantidad = document.getElementById('cantidad_agregar').value;
                    if (!cantidad || cantidad <= 0) {
                        alert('Ingresa una cantidad válida.');
                        return;
                    }
                    fetch('agregar_stock.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'id_producto=' + data.id_producto + '&cantidad=' + cantidad
                    })
                    .then(resp => resp.json())
                    .then(res => {
                        if (res.success) {
                            resultDiv.innerHTML = '<span style="color:green; font-weight:bold;">Stock actualizado. Nuevo total: ' + res.nuevo_stock + '</span>';
                        } else {
                            resultDiv.innerHTML = '<span style="color:red; font-weight:bold;">Error: ' + res.message + '</span>';
                        }
                    })
                    .catch(err => {
                        resultDiv.innerHTML = '<span style="color:red; font-weight:bold;">Error de conexión.</span>';
                    });
                });
            } else {
                resultDiv.innerHTML = 'Producto no existe. Redirigiendo para dar de alta...';
                setTimeout(function() {
                    window.location.href = 'productos_nuevo.php?codigo_barras=' + encodeURIComponent(code);
                }, 1500);
            }
        })
        .catch(() => {
            resultDiv.innerHTML = '<span style="color:red; font-weight:bold;">Error al buscar producto.</span>';
        });
    Quagga.stop();
    document.getElementById('scanner-container').style.display = 'none';
});
</script>
