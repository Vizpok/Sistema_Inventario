# Sistema de Inventario

Sistema web en PHP para gestión de inventario, con autenticación, dashboard base y estructura modular para crecer por áreas (catálogo, recepción, inventario y movimientos).

## Estado actual

- **Funcional:** login/logout, sesión de usuario, dashboard base, layouts globales, bootstrap del sistema y acceso a BD.
- **En construcción:** módulos funcionales de catálogo, recepción, inventario y movimientos (carpetas creadas, aún sin páginas PHP).

## Inicio rápido

1. Importa `System/sql/Base_Inventario.sql`.
2. Importa `System/sql/Datos_Iniciales.sql` (usuarios y datos de prueba).
3. Configura credenciales en `System/config/database.php`.
4. Abre `http://localhost/Sistema_Inventario/`.

## Credenciales de prueba

| Usuario | Contraseña | Rol |
|---------|------------|-----|
| admin | ADMIN | ADMIN |
| empleado | empleado123 | OPERADOR |

## Estructura principal

```text
Sistema_Inventario/
├── index.php                    # Punto de entrada (redirige a login/dashboard)
└── System/
    ├── bootstrap.php            # Carga configuración, helpers y clases base
    ├── config/                  # app, database, paths, session, env.example
    ├── core/                    # Auth.php y Database.php
    ├── helpers/                 # Funciones globales
    ├── interface/
    │   ├── dashboard/index.php  # Dashboard actual
    │   ├── layouts/             # header.php y footer.php
    │   ├── session/             # login.php y logout.php
    │   ├── catalog/             # Pendiente de implementación
    │   ├── inventory/           # Pendiente de implementación
    │   ├── movements/           # Pendiente de implementación
    │   └── reception/           # Pendiente de implementación
    ├── assets/
    │   ├── css/                 # styles.css y auth.css
    │   ├── icons/               # Reservado para iconos propios
    │   └── imgs/                # Reservado para imágenes
    └── sql/                     # Scripts SQL del proyecto
```

## Convenciones

- Incluir `System/bootstrap.php` al inicio de cada página PHP del sistema.
- Reutilizar `System/interface/layouts/header.php` y `footer.php`.
- Usar `db()` para consultas y funciones de `System/helpers/functions.php` para utilidades.
- Mantener actualizado el README del módulo cuando se agreguen nuevas pantallas o scripts.

## Tecnologías

- PHP 7.4+
- MySQL / MariaDB (XAMPP)
- HTML, CSS y JavaScript
- Bootstrap Icons (CDN)

## 👥 Roles del Sistema

- **Admin:** Acceso completo al sistema
- **Empleado:** Operaciones básicas de inventario

## 📚 Documentación Adicional

- [System/README.md](System/README.md) - Core del sistema
- [System/config/README.md](System/config/README.md) - Configuraciones
- [System/helpers/README.md](System/helpers/README.md) - Funciones auxiliares
- [System/interface/README.md](System/interface/README.md) - Módulos de interfaz
