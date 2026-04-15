<?php
/**
 * Catálogo de Ubicaciones - Formulario Nuevo
 * Formulario para crear una nueva ubicación
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Configurar variables para el layout
$page_title = 'Nueva Ubicación';
$base_url = '/Sistema_Inventario';

include __DIR__ . '/../../layouts/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Crear Nueva Ubicación</h1>
        <p class="page-subtitle">Completa el formulario para agregar un nuevo espacio de almacenamiento</p>
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
            max-width: 600px;
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

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0b1e36;
            box-shadow: 0 0 0 3px rgba(11, 30, 54, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .codigo-preview {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            border: 2px solid #e9ecef;
            text-align: center;
        }

        .codigo-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .codigo-value {
            font-size: 24px;
            font-weight: 700;
            color: #0b1e36;
            font-family: 'Courier New', monospace;
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

        .form-group.error input {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .form-group.error .error-message {
            display: block;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 6px;
            display: none;
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

    <form method="POST" action="ubicaciones_guardar.php" id="formUbicacion">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div class="form-container">
            <div class="form-section">
                <h3 class="form-section-title">Datos de Ubicación</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="pasillo">Pasillo <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="pasillo" 
                            name="pasillo" 
                            required
                            placeholder="Ej: A"
                            maxlength="10"
                        >
                        <div class="form-help">Identificador del pasillo (A, B, C, etc.)</div>
                        <div class="error-message">El pasillo es requerido</div>
                    </div>

                    <div class="form-group">
                        <label for="estante">Estante <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="estante" 
                            name="estante" 
                            required
                            placeholder="Ej: 01"
                            maxlength="10"
                        >
                        <div class="form-help">Número o identificador del estante</div>
                        <div class="error-message">El estante es requerido</div>
                    </div>

                    <div class="form-group">
                        <label for="nivel">Nivel <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="nivel" 
                            name="nivel" 
                            required
                            placeholder="Ej: 1"
                            maxlength="10"
                        >
                        <div class="form-help">Nivel dentro del estante (1, 2, 3, etc.)</div>
                        <div class="error-message">El nivel es requerido</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Vista Previa</h3>
                <div class="codigo-preview">
                    <div class="codigo-label">Código de Ubicación</div>
                    <div class="codigo-value" id="previewCodigo">---</div>
                    <div class="form-help" style="margin-top: 12px;">Se genera automáticamente con formato PASILLO-ESTANTE-NIVEL</div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Crear Ubicación
                </button>
                <a href="ubicaciones.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </div>
    </form>

    <script>
        // Actualizar preview del código en tiempo real
        function actualizarPreview() {
            const pasillo = document.getElementById('pasillo').value.toUpperCase() || '-';
            const estante = document.getElementById('estante').value || '-';
            const nivel = document.getElementById('nivel').value || '-';
            document.getElementById('previewCodigo').textContent = `${pasillo}-${estante}-${nivel}`;
        }

        document.getElementById('pasillo').addEventListener('input', actualizarPreview);
        document.getElementById('estante').addEventListener('input', actualizarPreview);
        document.getElementById('nivel').addEventListener('input', actualizarPreview);

        // Convertir pasillo a mayúsculas
        document.getElementById('pasillo').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Validación del formulario
        document.getElementById('formUbicacion').addEventListener('submit', function(e) {
            let isValid = true;
            const pasillo = document.getElementById('pasillo').value.trim();
            const estante = document.getElementById('estante').value.trim();
            const nivel = document.getElementById('nivel').value.trim();

            // Limpiar errores previos
            document.querySelectorAll('.form-group.error').forEach(el => {
                el.classList.remove('error');
            });

            // Validar pasillo
            if (!pasillo) {
                document.getElementById('pasillo').parentElement.classList.add('error');
                isValid = false;
            }

            // Validar estante
            if (!estante) {
                document.getElementById('estante').parentElement.classList.add('error');
                isValid = false;
            }

            // Validar nivel
            if (!nivel) {
                document.getElementById('nivel').parentElement.classList.add('error');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>

</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
