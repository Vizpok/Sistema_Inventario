# interface/session

Módulo de sesión y autenticación.

## Archivos

### login.php
Página de login del sistema.

**Características:**
- Formulario de autenticación (usuario y contraseña)
- Redirige automáticamente a dashboard si ya está autenticado
- Valida campos no vacíos
- Muestra mensajes de error
- Credenciales de prueba mostradas
- Usa clase Auth.php para lógica de autenticación
- Usa estilos de System/assets/css/auth.css

**Credenciales de prueba:**
| Usuario | Contraseña | Rol |
|---------|-----------|-----|
| admin | ADMIN | ADMIN |
| empleado | empleado123 | OPERADOR |

**Flujo:**
1. Usuario accede a /login.php
2. Si ya está autenticado → redirige a /dashboard/
3. Si envía formulario → valida con Auth::authenticate()
4. Si credenciales son correctas → llama Auth::login() y redirige
5. Si credenciales son incorrectas → muestra error y reintenta

### logout.php
Script para cerrar sesión del usuario.

**Comportamiento:**
- Invoca `Auth::logout()`.
- Redirige a `System/interface/session/login.php`.

## Clase Auth (System/core/Auth.php)

Toda la lógica de autenticación está centralizada en esta clase.

**Métodos:**
- Auth::authenticate($usuario, $contrasena) - Valida credenciales
- Auth::login($id, $nombre, $rol) - Crea sesión
- Auth::isAuthenticated() - Verifica si hay sesión activa
- Auth::getCurrentUser() - Obtiene datos del usuario
- Auth::logout() - Destruye sesión
- Auth::hasRole($rol|$roles) - Valida roles

## Estilos (System/assets/css/auth.css)

Hoja de estilos dedicada a páginas de autenticación.

## Próximos pasos

1. **Conectar con BD:** Tabla usuarios con hash de contraseñas
2. **Seguridad:** Tokens CSRF, rate limiting
3. **Funcionalidades:** Recordar contraseña, recuperación, 2FA
