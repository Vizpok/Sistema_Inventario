<?php
/**
 * Catálogo de Categorías - Formulario Nuevo
 * Formulario para crear una nueva categoría
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Configurar variables para el layout
$page_title = 'Nueva Categoría';
$base_url = '/Sistema_Inventario';

include __DIR__ . '/../../layouts/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Crear Nueva Categoría</h1>
        <p class="page-subtitle">Completa el formulario para agregar una nueva categoría de productos</p>
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
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #0b1e36;
            box-shadow: 0 0 0 3px rgba(11, 30, 54, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
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

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
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

    <form method="POST" action="categorias_guardar.php" id="formCategoria">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div class="form-container">
            <div class="form-group">
                <label for="nombre">Nombre de Categoría <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="nombre" 
                    required
                    placeholder="Ej: Herramientas Manuales"
                    maxlength="50"
                >
                <div class="form-help">Nombre descriptivo de la categoría</div>
            </div>

            <div class="form-group">
                <label for="codigo_prefijo">Código Prefijo <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="codigo_prefijo" 
                    name="codigo_prefijo" 
                    required
                    placeholder="Ej: HM"
                    maxlength="10"
                    style="text-transform: uppercase;"
                >
                <div class="form-help">Prefijo utilizado para generar SKUs de productos (ej: HM-001, HM-002)</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Crear Categoría
                </button>
                <a href="categorias.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </div>
    </form>

    <script>
        // Convertir a mayúsculas automáticamente
        document.getElementById('codigo_prefijo').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>

</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
