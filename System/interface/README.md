# interface

Módulos de interfaz del sistema de inventario.

## Estructura

- `layouts/`: plantillas reutilizables (header.php, footer.php)
- `session/`: autenticación (login, logout)
- `dashboard/`: panel principal **CON DATOS EN TIEMPO REAL**
- `catalog/`: productos, categorías, proveedores
- `reception/`: entradas de almacén
- `inventory/`: inventario, usuarios, clientes
- `movements/`: historial de movimientos y ventas

## Estado actual

- Implementado: `session/`, `dashboard/` (ahora con datos reales), `layouts/`
- Pendiente de implementación: `catalog/`, `reception/`, `inventory/`, `movements/`

## 🔄 Flujo de Datos del Sistema

El dashboard funciona con **datos en tiempo real** que se generan en otros módulos:

```
┌─────────────────────────────────────────────────────┐
│                   DASHBOARD                         │
│  (Muestra datos reales de todas las operaciones)   │
│  • Valor total del almacén                          │
│  • Total de productos                               │
│  • Stock bajo                                       │
│  • Últimos movimientos                              │
└──────────────────┬──────────────────────────────────┘
                   ▲
        Consulta datos en tiempo real
                   │
    ┌──────────────┴──────────────┬──────────────┐
    │                             │              │
    ▼                             ▼              ▼
RECEPCION ←―――→ INVENTARIO ←―――→ MOVIMIENTOS ←― ORDENES
(Entrada)      (Cantidades)   (Historial)     (Ventas)
    │                             │              │
    └─────────────────────────────┴──────────────┘
         Tablas de base de datos
         utilizadas por el Dashboard
```

### Detalles del Flujo:

1. **Usuario accede al Dashboard**
   - Ve datos actualizados en tiempo real

2. **Opción A: Recepción de Productos** (`reception/`)
   - Registra entrada de proveedor
   - Genera movimiento tipo "RECEPCION"
   - Actualiza tabla `inventario`
   - ✅ Dashboard actualiza automáticamente

3. **Opción B: Movimiento Interno** (`movements/`)
   - Transfiere entre ubicaciones (TRANSFERENCIA)
   - O registra salida/venta (SALIDA)
   - Genera movimiento en tabla `movimientos`
   - Actualiza tabla `inventario`
   - ✅ Dashboard actualiza automáticamente

4. **Opción C: Orden de Venta** (`catalog/`)
   - Crea nueva orden
   - Reserva cantidad en `inventario`
   - Al completar venta → SALIDA (movimientos)
   - ✅ Dashboard recalcula totales

## 📊 Datos del Dashboard

### Origen de cada métrica:

| Métrica | Tabla | Consulta |
|---------|-------|----------|
| **Valor Total** | `inventario` + `productos` | SUM(precio × cantidad_disponible) |
| **Total Productos** | `productos` | COUNT(DISTINCT ID_PRODUCTO) |
| **Stock Bajo** | `inventario` + `productos` | WHERE cantidad_disponible < stock_minimo |
| **Actividad Reciente** | `movimientos` + `usuarios` | ORDER BY fecha DESC LIMIT 5 |

### Tablas involucradas:
- `productos` - Precio unitario, stock mínimo
- `inventario` - Cantidad disponible por ubicación
- `movimientos` - Historial de todas las operaciones
- `usuarios` - Quién realizó cada movimiento

## Flujo de Acceso

1. **Login** (`session/login.php`)
   - Primera página al acceder al sistema
   - Valida credenciales
   - Crea sesión si es correcto
   - Redirige a dashboard

2. **Dashboard** (`dashboard/index.php`)
   - Página principal después de login
   - **Ahora con datos en tiempo real**
   - Acceso a todos los módulos

3. **Módulos del Sistema**
   - Recepción, Movimientos, Catálogo, Inventario
   - Cada uno genera datos que Dashboard consulta

4. **Logout** (`session/logout.php`)
   - Destruye la sesión
   - Redirige a login

## Layouts Base

Todas las páginas usan:
- `layouts/header.php` - Navbar con sidebar
- `layouts/footer.php` - Cierre de HTML

**Excepción:** Login no usa layouts (página pública sin autenticación)

## Datos Iniciales para Pruebas

Se incluye `System/sql/Datos_Iniciales.sql` con:

- 5 usuarios (administrador, gerente, operadores, supervisor)
- 10 productos en 5 categorías
- 4 proveedores
- 4 clientes
- 10 ubicaciones de almacén
- 10 lotes de recepciones
- 20 movimientos registrados
- 4 órdenes de venta

**Para ver el dashboard funcionando:** Ejecutar este script en la base de datos.

## Notas Importantes

- El **Dashboard requiere datos** de los otros módulos para mostrar información
- Cada módulo **debe registrar movimientos** en la tabla `movimientos`
- Los movimientos se crean automáticamente al procesar recepciones, transferencias o salidas
- El dashboard consulta datos en **tiempo real** sin retraso
- La estructura de la BD permite auditoría completa de todas las operaciones
