# sql

Scripts SQL del sistema para MySQL/MariaDB (XAMPP).

## Archivos

### Base_Inventario.sql
Script principal con la **estructura completa de la base de datos**.

**Contenido:**
- Todas las tablas del sistema
- Relaciones (FK) entre tablas
- Índices y constraints
- Estructura de usuarios con campos: ID_USUARIO, NOMBRE, EMAIL, PASSWORD_USUARIO, ROL, ACTIVO

**Ejecución:**
```sql
-- En phpMyAdmin o MySQL CLI
SOURCE System/sql/Base_Inventario.sql;
-- O desde phpMyAdmin: Importar archivo
```

### Datos_Iniciales.sql
Script con **datos iniciales** para desarrollo.

**Contenido:**
- Usuarios de prueba
- Datos base requeridos por el sistema en entorno local

**Ejecución (después de Base_Inventario.sql):**
```sql
SOURCE System/sql/Datos_Iniciales.sql;
```

**Credenciales:**
| Usuario | Contraseña | Rol | ID |
|---------|-----------|-----|-----|
| admin | ADMIN | ADMIN | 1 |
| empleado | empleado123 | OPERADOR | 2 |

## Flujo de Instalación

1. **Crear base de datos:** `CREATE DATABASE Base_Inventario;`
2. **Ejecutar estructura:** Importar `Base_Inventario.sql`
3. **Agregar datos de prueba:** Importar `Datos_Iniciales.sql`
4. **Usar credenciales:** admin / ADMIN

## Notas

- Las contraseñas están **sin hash** (solo para desarrollo)
- Tabla usuarios está lista para hashing con `password_hash()`
- Cuando la app pase a producción, reemplazar contraseñas planas por hash y evitar scripts con credenciales de prueba.