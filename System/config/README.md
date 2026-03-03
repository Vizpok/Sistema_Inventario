# config

Configuración central del sistema.

## Archivos
- `app.php`: configuración general de la aplicación.
- `database.php`: parámetros de conexión a base de datos.
- `session.php`: configuración de sesiones.
- `paths.php`: rutas base del proyecto.
- `env.example`: variables de entorno de ejemplo.

## Reglas
- No subir credenciales reales al repositorio.
- Mantener la lógica de negocio fuera de esta carpeta.
- La base de datos para este proyecto se configura de forma local (XAMPP).
- Usar `env.example` como plantilla para cada equipo/entorno local.
