# config

Configuración central del sistema.

## Archivos

### app.php
Configuración general de la aplicación.
- `app_name`: Nombre del sistema ("Sistema Inventario")
- `app_env`: Ambiente (development/production)
- `app_debug`: Mostrar errores en pantalla (true/false)
- `timezone`: Zona horaria ("America/Mexico_City")
- `charset`: Codificación de caracteres ("UTF-8")

### database.php
Parámetros de conexión a base de datos MySQL.
- **Base de datos:** `Base_Inventario`
- **Host:** 127.0.0.1
- **Puerto:** 3306
- **Usuario:** root
- **Charset:** utf8mb4
- Utilizado por la clase `Database.php` para conexión automática

### session.php
Configuración de sesiones PHP.
- `name`: Nombre de la cookie de sesión
- `lifetime`: Duración en minutos (120 min)
- `secure`: HTTPS only (false para desarrollo local)
- `httponly`: Protección XSS (true)
- `samesite`: Protección CSRF ('Lax')

### paths.php
Rutas base del proyecto.
- `base_path`: Ruta raíz del sistema
- `assets_path`: Carpeta de recursos estáticos
- `interface_path`: Carpeta de interfaces/vistas
- `sql_path`: Carpeta de scripts SQL

### env.example
Plantilla de variables de entorno para equipo local.

## Reglas
- No subir credenciales reales al repositorio.
- Mantener la lógica de negocio fuera de esta carpeta.
- La base de datos para este proyecto se configura de forma local (XAMPP).
- Usar `env.example` como plantilla para cada equipo/entorno local.
