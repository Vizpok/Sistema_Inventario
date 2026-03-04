# helpers

Funciones auxiliares y utilidades del sistema.

## Archivos

### functions.php
Colección de funciones de uso común en toda la aplicación.

**Configuración:**
- `config($name)`: Carga un archivo de configuración por nombre

**Navegación:**
- `redirect($url)`: Redirección HTTP
- `baseUrl()`: Obtiene la URL base del sistema
- `view($view, $data)`: Carga una vista desde `interface/`

**Seguridad:**
- `sanitize($string)`: Limpia entrada de usuario (XSS protection)
- `generateCsrfToken()`: Genera token CSRF
- `verifyCsrfToken($token)`: Valida token CSRF

**Autenticación:**
- `isAuthenticated()`: Verifica si hay sesión activa
- `currentUser()`: Obtiene datos del usuario actual (id, nombre, rol)
- `requireAuth()`: Requiere autenticación o redirige a login

**Formateo:**
- `formatDate($date)`: Formatea fecha a formato DD/MM/YYYY HH:mm
- `formatMoney($amount)`: Formatea número como moneda ($1,234.56)

**Alertas:**
- `showAlert($message, $type)`: Guarda alerta en sesión (success/error/warning/info)
- `getAlert()`: Obtiene y limpia alerta de sesión

**Uso:**
```php
require_once 'System/bootstrap.php';

// Autenticación
requireAuth(); // Protege la página

// Usuario actual
$user = currentUser();
echo $user['nombre'];

// Formateo
echo formatMoney(1500.50); // $1,500.50
echo formatDate('2024-03-15 14:30:00'); // 15/03/2024 14:30

// Alertas
showAlert('Producto guardado exitosamente', 'success');
$alert = getAlert();

// Sanitización
$nombre_limpio = sanitize($_POST['nombre']);
```

## Convenciones
- Todas las funciones son globales y están disponibles después de cargar `bootstrap.php`
- Las funciones de sesión requieren que las sesiones estén iniciadas
- Siempre sanitizar entrada de usuario antes de usarla en BD o vistas
