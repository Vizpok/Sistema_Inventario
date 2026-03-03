# interface/session

Módulo de sesión y autenticación.

## Debe incluir

- `index.php`: vista de login.
- `login_procesar.php`: validación de acceso.
- `logout.php`: cierre de sesión.

## Notas

- Controla acceso por rol (`admin` y `empleado`) con restricciones internas.
- Para este entregable, validar principalmente el flujo de `empleado`.
