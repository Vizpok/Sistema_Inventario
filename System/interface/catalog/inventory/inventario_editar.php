<?php
/**
 * Inventario - Editar Cantidad
 */
require_once __DIR__ . '/../../../bootstrap.php';
requireAuth();

$page_title = 'Editar Inventario';
$base_url = '/Sistema_Inventario';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$item = db()->select("SELECT i.ID_INVENTARIO, p.NOMBRE, p.SKU, i.CANTIDAD_TOTAL, i.ID_PRODUCTO, i.ID_UBICACION FROM inventario i LEFT JOIN productos p ON i.ID_PRODUCTO = p.ID_PRODUCTO WHERE i.ID_INVENTARIO = $id");
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
    $id_ubicacion = (int)$_POST['id_ubicacion'];
    db()->execute("UPDATE inventario SET CANTIDAD_TOTAL = $cantidad_total, ID_UBICACION = $id_ubicacion WHERE ID_INVENTARIO = $id");
    $_SESSION['alert'] = [
        'message' => 'Cantidad actualizada.',
        'type' => 'success'
    ];
    header('Location: inventario.php');
    exit;
}

include __DIR__ . '/../../layouts/header.php';
?>
<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Editar Inventario</h1>
    </div>
    <button id="scan-barcode-btn" class="btn-primary" style="margin-bottom:20px;">Escanear Código de Barras</button>
    <div id="scanner-container" style="display:none; margin-bottom:20px;">
        <div id="html5-qrcode" style="width:100%; max-width:400px; margin:auto;"></div>
        <div id="scan-result" style="margin-top:10px; font-weight:bold;"></div>
        <button id="stop-scan-btn" class="btn-reset" style="margin-top:10px;">Detener Escaneo</button>
    </div>
    <form method="POST" style="background:white; border-radius:12px; padding:32px; max-width:500px; margin:auto; box-shadow:0 1px 3px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.06); border:1px solid #f0f0f0;">
        <div style="margin-bottom:20px;">
            <label>Producto</label>
            <input type="text" id="sku-input" name="sku" value="<?= '[' . $item['SKU'] . '] ' . $item['NOMBRE'] ?>" style="width:100%; padding:10px; border-radius:8px;" readonly>
        </div>
        <div style="margin-bottom:20px;">
            <label>Ubicación</label>
            <select name="id_ubicacion" required style="width:100%; padding:10px; border-radius:8px;">
                <?php foreach ($ubicaciones as $u): ?>
                <option value="<?= $u['ID_UBICACION'] ?>" <?= $item['ID_UBICACION'] == $u['ID_UBICACION'] ? 'selected' : '' ?>><?= $u['CODIGO_UBICACION'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="margin-bottom:20px;">
            <label>Cantidad Total</label>
            <input type="number" name="cantidad_total" min="1" value="<?= $item['CANTIDAD_TOTAL'] ?>" required style="width:100%; padding:10px; border-radius:8px;">
        </div>
        <button type="submit" class="btn-primary"><i class="bi bi-pencil"></i> Guardar Cambios</button>
    </form>
</div>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
document.getElementById('scan-barcode-btn').addEventListener('click', function() {
    document.getElementById('scanner-container').style.display = 'block';
    Quagga.init({
        inputStream: {
            name: 'Live',
            type: 'LiveStream',
            target: document.querySelector('#scanner-video') || document.getElementById('scanner-container'),
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
    document.getElementById('scan-result').textContent = 'Código detectado: ' + code;
    document.getElementById('sku-input').value = code;
    fetch('buscar_producto.php?sku=' + encodeURIComponent(code))
        .then(response => response.json())
        .then(data => {
            if (data.existe) {
                document.getElementById('scan-result').textContent = 'Producto encontrado: ' + data.nombre + '. Cantidad actual: ' + data.cantidad;
                // Mostrar input para cantidad y botón para agregar al stock
                let cantidadDiv = document.createElement('div');
                cantidadDiv.innerHTML = '<label>¿Cuántos agregar al stock?</label><input type="number" id="cantidad_agregar" min="1" style="width:100%; padding:10px; border-radius:8px; margin-bottom:10px;"> <button id="btn-agregar-stock" class="btn-primary">Agregar al stock</button>';
                document.getElementById('scan-result').appendChild(cantidadDiv);
                document.getElementById('btn-agregar-stock').addEventListener('click', function() {
                    let cantidad = document.getElementById('cantidad_agregar').value;
                    // Aquí puedes hacer un fetch o submit para agregar al stock
                    alert('Se agregarán ' + cantidad + ' unidades al stock.');
                    // Puedes enviar el dato al backend aquí
                });
            } else {
                document.getElementById('scan-result').textContent = 'Producto no existe. Redirigiendo para dar de alta...';
                setTimeout(function() {
                    window.location.href = 'productos_nuevo.php?codigo_barras=' + encodeURIComponent(code);
                }, 1500);
            }
        })
        .catch(() => {
            document.getElementById('scan-result').textContent = 'Error al buscar producto.';
        });
    Quagga.stop();
    document.getElementById('scanner-container').style.display = 'none';
});
</script>
</script>
