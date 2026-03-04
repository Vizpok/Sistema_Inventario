# System

Núcleo técnico del sistema de inventario.

## Estructura

- `bootstrap.php`: inicialización global (sesión, config, helpers, clases y constantes).
- `config/`: configuración de aplicación, BD, rutas y sesión.
- `core/`: clases principales (`Auth.php`, `Database.php`).
- `helpers/`: funciones globales reutilizables.
- `interface/`: vistas y módulos de UI.
- `assets/`: recursos estáticos (CSS, iconos e imágenes).
- `sql/`: scripts para crear y poblar la base de datos.

## Estado funcional actual

- Autenticación y sesión con `Auth.php` + `session/login.php`/`logout.php`.
- Dashboard base en `interface/dashboard/index.php`.
- Layout reutilizable en `interface/layouts/`.
- Conexión y consultas a BD con `Database.php`.

## Módulos pendientes

Las carpetas `interface/catalog`, `interface/inventory`, `interface/movements` y `interface/reception` están preparadas, pero todavía sin implementación de pantallas PHP.

## Convenciones

- Incluir `System/bootstrap.php` al inicio de cada página del sistema.
- Mantener la lógica de negocio fuera de `interface/`.
- No subir credenciales reales.
