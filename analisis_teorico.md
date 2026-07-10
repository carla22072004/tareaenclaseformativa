# Análisis Teórico y Comparativa: Sesiones de Servidor y JWT

## 1. Tabla Comparativa de Gestión de Estado

| Criterio | PHP 8 | ASP.NET Core 8 | Java / Spring Boot 3 |
| :--- | :--- | :--- | :--- |
| **Iniciar sesión** | `session_start()` al inicio de cada script. | `AddSession()` en `Program.cs` + `UseSession()` middleware. | Automático con Spring MVC; `HttpSession` inyectada en el método del controlador. |
| **Guardar objeto** | `$_SESSION[k] = array(...)` nativo. | Serializar a JSON + `Session.SetString()`. | `session.setAttribute(k, objeto)` de forma directa. |
| **Leer objeto** | `$_SESSION[k] ?? []` | `Session.GetString()` + deserializar JSON. | `(Tipo) session.getAttribute(k)` con un cast explícito. |
| **Destruir sesión** | `session_destroy()` | `Session.Clear()` | `session.invalidate()` |
| **Almacenamiento** | Archivos en disco (ej. `/tmp`) por defecto. | Memoria, Redis o SQL Server (configurable). | Memoria de la JVM (por defecto), Redis o JDBC. |

## 2. Flags de Seguridad de Cookies de Sesión

Para que una cookie de sesión se considere segura en un entorno de producción, debe incluir de forma obligatoria tres banderas:

- **HttpOnly**: Impide por completo que el código JavaScript del lado del cliente pueda leer o acceder a la cookie. Esto previene eficazmente el robo de identificadores de sesión en caso de que un atacante logre inyectar código malicioso mediante un ataque de **XSS** (Cross-Site Scripting).
- **Secure**: Obliga al navegador a enviar la cookie exclusivamente a través de conexiones cifradas con **HTTPS**. Esto evita que la cookie viaje en texto claro a través de la red, protegiéndola contra interceptación (ataques Man-in-the-Middle).
- **SameSite**: Al configurarse en `Strict`, indica que la cookie únicamente debe enviarse si la petición se origina desde el mismo dominio que la emitió. Es el principal mecanismo de defensa contra ataques **CSRF** (Cross-Site Request Forgery).

## 3. Justificación: ¿Por qué el PFC usa JWT en lugar de sesiones de servidor?

En el Proyecto Final de Carrera (PFC), la arquitectura implementada consta de un frontend **SPA (Single Page Application)** desarrollado en Angular, el cual consume una **API REST** de Spring Boot. Esta arquitectura difiere de las aplicaciones web multi-página tradicionales, haciendo que las sesiones de servidor resulten inadecuadas.

El uso de **JWT almacenado en una cookie HttpOnly** soluciona esto por las siguientes razones:

1. **Cumplimiento del Principio Stateless de REST**: Las APIs REST requieren que cada petición sea independiente y que el servidor no mantenga estado (stateless). Las sesiones de servidor violan este principio al almacenar los datos en la memoria del backend. JWT, en cambio, encapsula el estado y las credenciales en el propio token, el cual viaja al servidor en cada petición.
2. **Facilidad de Escalabilidad Horizontal**: Con las sesiones tradicionales de servidor, si la aplicación aumenta en tráfico y se despliegan múltiples instancias de Spring Boot detrás de un balanceador de carga, el estado de la sesión queda asilado en la memoria de una instancia particular (causando inconsistencias). Para arreglarlo, habría que implementar infraestructuras adicionales como Redis o "sticky sessions". Con JWT, al no almacenar nada en el servidor, cualquier instancia puede recibir la solicitud, verificar criptográficamente la firma del token y procesarla, lo que permite un escalado horizontal inmediato.
3. **Protección contra XSS en SPA**: Si bien los JWT pueden enviarse en el header `Authorization: Bearer`, almacenarlos en localStorage en el navegador expone el token a ataques XSS. Al depositar el JWT en una cookie con flag **HttpOnly**, mantenemos las ventajas de estado distribuido del token y al mismo tiempo blindamos el acceso contra JavaScript, ofreciendo un equilibrio óptimo de seguridad para la SPA.
