# System

Este directorio contiene la base del sistema de inventario.

Metodología de trabajo: prototipo.
Base de datos: local (XAMPP), sin servicios en la nube para este entregable.

## Estructura estándar

- `assets/`: recursos estáticos (css, iconos e imágenes).
- `config/`: configuración central de la aplicación.
- `interface/`: módulos de interfaz por funcionalidad.
- `sql/`: scripts SQL versionados del sistema.

## Convenciones

- Cada carpeta debe contener al menos un archivo (`README.md` o `.gitkeep`) para que Git la incluya.
- No subir credenciales reales al repositorio.
- Mantener una estructura modular por carpeta para evitar desorden.

## Alcance del entregable

- Interfaces incluidas: login/logout, dashboard, catálogos, recepción, inventario, movimientos y ventas.
- Roles previstos: `admin` y `empleado`; en este entregable se prioriza flujo de `empleado`.
- No se crea módulo separado de empleados; la gestión se cubre desde usuarios y restricciones internas.
