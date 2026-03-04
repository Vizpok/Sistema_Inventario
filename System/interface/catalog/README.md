# interface/catalog

Módulo de catálogo de productos e información complementaria del almacén.

## Estado

Módulo **completamente implementado**.

## Estructura

### Navegación
Acceso desde el menú principal bajo "Catálogo" con un submenú desplegable que contiene:
- Productos
- Categorías
- Proveedores
- Ubicaciones

### Entidades y Archivos

#### 1. Productos (`productos.php` y relacionados)
Gestión del catálogo de productos.

**Archivos:**
- `productos.php` - Listado de productos con búsqueda, paginación y filtros
- `productos_nuevo.php` - Formulario para crear nuevo producto
- `productos_guardar.php` - Procesa la inserción del producto
- `productos_editar.php` - Formulario para editar producto existente
- `productos_actualizar.php` - Procesa la actualización del producto
- `productos_eliminar.php` - Procesa la eliminación del producto

**Funcionalidades:**
- Listado con paginación (20 productos por página)
- Búsqueda por nombre, SKU o código de barras
- Asociación con categorías
- Gestión de precio y stock mínimo
- Validaciones en cliente y servidor
- Protección contra integridad referencial

#### 2. Categorías (`categorias.php` y relacionados)
Gestión de categorías de productos.

**Archivos:**
- `categorias.php` - Listado de categorías en vista de grid
- `categorias_nuevo.php` - Formulario para crear categoria
- `categorias_guardar.php` - Procesa la inserción
- `categorias_editar.php` - Formulario de edición
- `categorias_actualizar.php` - Procesa la actualización
- `categorias_eliminar.php` - Procesa la eliminación

**Funcionalidades:**
- Vista de grid para mejor visualización
- Código prefijo para generación de SKUs
- Contador de productos por categoría
- Validación de unicidad de nombre y código
- Prevención de eliminación si hay productos asociados

#### 3. Proveedores (`proveedores.php` y relacionados)
Gestión de proveedores de la empresa.

**Archivos:**
- `proveedores.php` - Listado de proveedores con búsqueda
- `proveedores_nuevo.php` - Formulario para crear proveedor
- `proveedores_guardar.php` - Procesa la inserción
- `proveedores_editar.php` - Formulario de edición
- `proveedores_actualizar.php` - Procesa la actualización
- `proveedores_eliminar.php` - Procesa la eliminación

**Funcionalidades:**
- Registro de RFC y contacto
- Contador de lotes registrados
- Búsqueda por nombre, RFC o contacto
- Validación de unicidad de nombre
- Protección de integridad referencial

#### 4. Ubicaciones (`ubicaciones.php` y relacionados)
Gestión de espacios de almacenamiento en el almacén.

**Archivos:**
- `ubicaciones.php` - Listado de ubicaciones
- `ubicaciones_nuevo.php` - Formulario para crear ubicación
- `ubicaciones_guardar.php` - Procesa la inserción
- `ubicaciones_editar.php` - Formulario de edición
- `ubicaciones_actualizar.php` - Procesa la actualización
- `ubicaciones_eliminar.php` - Procesa la eliminación

**Funcionalidades:**
- Estructura: PASILLO → ESTANTE → NIVEL
- Código automático generado: PASILLO-ESTANTE-NIVEL
- Vista previa en tiempo real del código
- Contador de items y cantidad total
- Búsqueda inteligente
- Validación de unicidad de ubicación

## Características Globales

### Seguridad
- ✅ Validación CSRF en todos los formularios
- ✅ Sanitización de inputs
- ✅ Escaping de SQL (prepared statements con escape)
- ✅ Validación en cliente y servidor
- ✅ Protección de integridad referencial

### Interfaz
- ✅ Diseño responsivo (desktop, tablet, móvil)
- ✅ Bootstrap Icons para iconografía
- ✅ Layouts reutilizables (header/footer)
- ✅ Alertas visuales de éxito/error
- ✅ Paginación inteligente
- ✅ Búsqueda en tiempo real

### Funcionalidad
- ✅ CRUD completo para cada entidad
- ✅ Validaciones de negocio
- ✅ Contadores y estadísticas
- ✅ Mensajes de confirmación
- ✅ Manejo de errores

## Flujos de Uso

### Crear un Producto
1. Acceder a Catálogo → Productos
2. Clic en "Nuevo Producto"
3. Completar formulario (nombre, SKU, categoría, precio, stock mínimo)
4. Validación automática en cliente
5. Al guardar, se crea el registro y redirige a listado

### Editar una Ubicación
1. Acceder a Catálogo → Ubicaciones
2. Clic en "Editar" en la ubicación deseada
3. Modificar los datos (pasillo, estante, nivel)
4. El código se actualiza en tiempo real
5. Al guardar, se actualiza y redirige

### Eliminar una Categoría
1. Acceder a Catálogo → Categorías
2. Clic en "Eliminar" en la categoría deseada
3. Confirmación (solo se puede si no tiene productos)
4. Se elimina y redirige con mensaje de éxito

## Integración con Base de Datos

Las siguientes tablas son utilizadas:
- `productos` - Catálogo de productos
- `categorias` - Categorías de productos
- `proveedores` - Información de proveedores
- `ubicaciones` - Ubicaciones de almacenamiento
- `inventario` - Inventario (relación con ubicaciones y productos)
- `lotes` - Lotes de productos (relación con proveedores)

## Patrón de Desarrollo

Cada entidad sigue el patrón MVC simple:
```
entidad.php → listado y búsqueda
entidad_nuevo.php → formulario para crear
entidad_guardar.php → procesa POST y guarda
entidad_editar.php → formulario para actualizar
entidad_actualizar.php → procesa POST y actualiza
entidad_eliminar.php → procesa eliminación
```

## Validaciones Implementadas

### Productos
- SKU es requerido y único
- Nombre es requerido
- Categoría es requerida
- Precio debe ser > 0
- Stock mínimo debe ser > 0

### Categorías
- Nombre es requerido y único
- Código prefijo es requerido y único
- No se puede eliminar si tiene productos

### Proveedores
- Nombre es requerido y único
- RFC es opcional
- Contacto es opcional
- No se puede eliminar si tiene lotes

### Ubicaciones
- Pasillo es requerido
- Estante es requerido
- Nivel es requerido
- Combinación de pasillo-estante-nivel es única
- No se puede eliminar si tiene inventario

## Mejoras Futuras

- Importación de datos desde CSV/Excel
- Generación automática de SKU basada en categoría
- Panel de control de stock bajo
- Historial de cambios en productos
- Asignación de fotos/imágenes de productos

## Notas

- El menú de Catálogo es desplegable y se muestra en el sidebar
- Todos los formularios tienen validación en cliente y servidor
- Los mensajes de error/éxito se muestran al usuario de manera clara
- La interfaz es responsiva y funciona en dispositivos móviles
- Se utiliza la función `db()` global para todas las consultas
- Se utiliza Bootstrap Icons para la iconografía

