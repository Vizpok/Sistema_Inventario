# interface

Módulos de interfaz del sistema.

## Estructura

- `layouts/`: plantillas reutilizables (header.php, footer.php)
- `session/`: autenticación (login, logout)
- `dashboard/`: panel principal
- `catalog/`: productos, categorías, proveedores
- `reception/`: entradas de almacén
- `inventory/`: inventario, usuarios, clientes
- `movements/`: historial de movimientos

## Estado actual

- Implementado: `session/`, `dashboard/`, `layouts/`.
- Pendiente de implementación de pantallas: `catalog/`, `reception/`, `inventory/`, `movements/`.

## Flujo de Acceso

1. **Login** (`session/login.php`)
   - Primera página al acceder al sistema
   - Valida credenciales
   - Crea sesión si es correcto
   - Redirige a dashboard

2. **Dashboard** (`dashboard/index.php`)
   - Página principal después de login
   - Acceso a todos los módulos

3. **Logout** (`session/logout.php`)
   - Destruye la sesión
   - Redirige a login

## Layouts Base

Todas las páginas usan:
- `layouts/header.php` - Navbar con sidebar
- `layouts/footer.php` - Cierre de HTML

**Excepción:** Login no usa layouts (página pública sin autenticación)

## Nota

Las carpetas pendientes ya cuentan con README para definir alcance, pero todavía no contienen archivos PHP funcionales.
