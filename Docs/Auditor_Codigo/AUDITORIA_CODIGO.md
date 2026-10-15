# Informe de Auditoría Integral de Código: Cosmol-Deportes

**Proyecto:** Cosmol-Deportes (Edición Olimpiadas Inter-Cooperativas)  
**Rol del Auditor:** Auditor de Código Senior (Backend & Frontend)  
**Fecha:** Octubre 2026  
**Entorno de Ejecución:** PHP 7.3 / Apache 2.4 / MariaDB 10.4 (XAMPP)  
**Alcance Auditado:** Arquitectura MVC, seguridad, persistencia PDO, reglas de negocio, frontend AdminLTE 3 y coherencia con el documento maestro [AGENTS.md](../AGENTS.md).

---

## 1. Resumen Ejecutivo

### 1.1. Stack Tecnológico Detectado
* **Backend:** PHP 7.3 nativo bajo arquitectura MVC sin frameworks pesados, persistencia con PDO (`PDO::ATTR_EMULATE_PREPARES => false`), ruteo mediante Front Controller (`public/index.php` + `Router.php`).
* **Frontend:** AdminLTE 3.2.0 (Bootstrap 4.6.2, jQuery 3.6.0, FontAwesome 5.15.4), estilos corporativos personalizados (`public/css/custom.css`).
* **Base de Datos:** MySQL / MariaDB (motor InnoDB, codificación `utf8mb4`, 16 tablas relacionales con restricciones e integridad referencial).
* **Entorno Servidor Local:** Apache 2.4 + MariaDB (XAMPP para Windows).

### 1.2. Estado General del Proyecto
El proyecto presenta una estructura ordenada, modular y coherente con el patrón MVC clásico. Se respetan rigurosamente las restricciones sintácticas de **PHP 7.3** (sin propiedades tipadas, sin funciones flecha, sin operador nullsafe). La parametrización SQL con PDO es limpia en la gran mayoría de los modelos y existe protección CSRF generalizada en los formularios.

Sin embargo, se detectaron **fallas críticas de seguridad (un bypass de autenticación/backdoor)**, un **error fatal de ejecución que rompe la gestión del Medallero Olímpico**, inconsistencias entre la arquitectura estándar y módulos específicos, y problemas de rendimiento por consultas N+1.

### 1.3. Los 3 Problemas Más Críticos
1. **Puerta trasera (Backdoor) en el sistema de autenticación:** `Usuario::verifyPassword` valida con éxito cualquier cuenta si se ingresa `admin123` o `password`, permitiendo la toma de control de cuentas de administrador.
2. **Error Fatal en el Medallero (`Call to undefined method CsrfHelper::validateToken`):** En `MedalleroController`, se invocan métodos que no existen en el helper CSRF, lo que produce una pantalla en blanco o error 500 al guardar podios o eliminar medallas.
3. **Ausencia del Portal Público y Desfase con el Modelo Inter-Cooperativas:** El sistema aún conserva en semillas y mock data el modelo de departamentos internos ("Gerencia Comercial", "Planta Operaciones") en lugar del macro-evento de Cooperativas ("COSMOL R.L.", "SAGUAPAC R.L."), y la carpeta `app/views/public/` estipulada en la Fase 9 no ha sido creada.

---

## 2. Hallazgos de Auditoría

### 2.1. Backend

---

#### 🔴 Hallazgo B-01: Bypass de Autenticación / Backdoor en Verificación de Contraseñas
* **Severidad:** Crítica
* **Ubicación:** `app/models/Usuario.php` (Líneas 90-95)
* **Descripción:** En el método `verifyPassword`, existe una condición de "compatibilidad para pruebas" que valida como verdadera cualquier contraseña igual a `admin123` o `password` si el hash almacenado inicia con `$2y$`. Dado que **todas** las contraseñas generadas con `PASSWORD_BCRYPT` inician con el prefijo `$2y$`, un atacante que conozca el nombre de usuario de cualquier cuenta (incluyendo el Administrador con contraseña segura) puede iniciar sesión escribiendo simplemente `admin123`.
* **Evidencia de Código:**
  ```php
  // Compatibilidad para credenciales de prueba de semillas
  if (($plainPassword === 'admin123' || $plainPassword === 'password') && strpos($hash, '$2y$') === 0) {
      return true;
  }
  ```
* **Recomendación:** Eliminar de inmediato las líneas 90-95. La autenticación debe depender exclusivamente de la función criptográfica nativa `password_verify($plainPassword, $hash)`. Las credenciales de prueba deben generarse con un hash real en `database/seeds.sql`.

---

#### 🔴 Hallazgo B-02: Error Fatal por Invocación a Método Inexistente `CsrfHelper::validateToken()`
* **Severidad:** Crítica
* **Ubicación:** `app/controllers/MedalleroController.php` (Líneas 172 y 246)
* **Descripción:** En los métodos `guardarPodio()` y `eliminarMedalla()`, se invoca `CsrfHelper::validateToken($_POST['csrf_token'] ?? '')`. La clase `app/helpers/CsrfHelper.php` no posee ningún método llamado `validateToken()`, únicamente `validate($token = null)`. Al ejecutar estas acciones, PHP genera un error fatal:
  `Fatal error: Uncaught Error: Call to undefined method CsrfHelper::validateToken()`
* **Evidencia de Código:**
  ```php
  public function guardarPodio($torneoId) {
      AuthHelper::requireAdmin();
      CsrfHelper::validateToken($_POST['csrf_token'] ?? '');
  ```
* **Recomendación:** Sustituir la llamada por el patrón estándar del resto de controladores:
  ```php
  if (!CsrfHelper::validate()) {
      Session::setFlash('error', 'Token de seguridad inválido.');
      $this->redirect('/medallero/gestion');
  }
  ```

---

#### 🟠 Hallazgo B-03: Exposición de Detalles Internos en Errores de Base de Datos
* **Severidad:** Alta
* **Ubicación:** `app/config/database.php` (Línea 103)
* **Descripción:** Si la conexión a MariaDB/MySQL falla, el método `handleConnectionError()` imprime en pantalla el mensaje crudo `$e->getMessage()`. Esto ocurre sin verificar la constante `APP_DEBUG`, exponiendo en entornos de producción nombres de usuario de DB, IPs locales, nombres de bases de datos y rutas del servidor.
* **Evidencia de Código:**
  ```php
  echo "<div class='alert alert-warning'><strong>Detalle técnico:</strong> " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
  ```
* **Recomendación:** Condicionar el despliegue del detalle técnico a `if (defined('APP_DEBUG') && APP_DEBUG)`. En caso de estar en producción, mostrar un mensaje genérico al usuario y registrar la excepción en el log de errores del servidor (`error_log`).

---

#### 🟡 Hallazgo B-04: Problema de Rendimiento N+1 en Cálculo de Posiciones de Voleibol
* **Severidad:** Media
* **Ubicación:** `app/models/Posicion.php` (Líneas 231-236)
* **Descripción:** Dentro del método `procesarPartidosVoleibol()`, se itera sobre la lista de partidos finalizados (`foreach ($partidos as $p)`) y en cada iteración se ejecuta una consulta individual a la tabla `partido_sets`. Si una olimpiada tiene decenas de encuentros de voleibol, se disparan decenas de consultas SQL innecesarias en cada carga de la tabla de posiciones.
* **Evidencia de Código:**
  ```php
  foreach ($partidos as $p) {
      ...
      // Obtener los sets del partido
      $sqlSets = "SELECT puntos_local, puntos_visitante
                  FROM partido_sets
                  WHERE partido_id = :partido_id
                  ORDER BY numero_set ASC";
      $sets = $this->fetchAll($sqlSets, ['partido_id' => $partidoId]);
  ```
* **Recomendación:** Cargar todos los sets de todos los partidos del torneo en una sola consulta previa antes del bucle (`WHERE partido_id IN (...)`), agruparlos en memoria por `partido_id` e indexarlos en un array asociativo.

---

#### 🟡 Hallazgo B-05: Inconsistencia en la Gestión de Sesión y Mensajes Flash en Medallero
* **Severidad:** Media
* **Ubicación:** `app/controllers/MedalleroController.php` (Líneas 100-102, 135-136, 184-186, 228-233, 258-263)
* **Descripción:** `MedalleroController` se desvía de la arquitectura del proyecto: en lugar de utilizar el helper centralizado `Session::setFlash('tipo', 'mensaje')` (que alimenta el layout `main.php`), manipula directamente las variables superglobales `$_SESSION['flash_msg']` y `$_SESSION['flash_type']`.
* **Evidencia de Código:**
  ```php
  $_SESSION['flash_msg']  = 'Torneo no encontrado.';
  $_SESSION['flash_type'] = 'danger';
  $this->redirect('/medallero/gestion');
  ```
* **Recomendación:** Refactorizar `MedalleroController` para utilizar `Session::setFlash('success'|'error', '...')`, alineándolo con los demás controladores y con el layout `app/views/layouts/main.php`.

---

#### 🔵 Hallazgo B-06: Duplicidad Sintáctica en Docblock de `Router.php`
* **Severidad:** Baja
* **Ubicación:** `app/helpers/Router.php` (Líneas 54-55)
* **Descripción:** Existe una apertura doble de bloque de documentación (`/** /**`). Aunque no rompe el parser de PHP, es código sucio.
* **Evidencia de Código:**
  ```php
      /**
      /**
       * Obtiene la URI limpia de la solicitud actual
  ```
* **Recomendación:** Eliminar la línea duplicada `/**`.

---

### 2.2. Frontend

---

#### 🟡 Hallazgo F-01: Pérdida Silenciosa de Mensajes Flash de Tipo 'info'
* **Severidad:** Media
* **Ubicación:** `app/views/layouts/main.php` (Líneas 19-48) y `app/views/auth/login.php` (Líneas 90-106)
* **Descripción:** En `app/controllers/AuthController.php` (Línea 186), al cerrar sesión se ejecuta:
  `Session::setFlash('info', 'Sesión cerrada correctamente.');`
  Sin embargo, ni `main.php` ni la vista de `login.php` cuentan con un bloque para capturar y renderizar mensajes flash del tipo `'info'` (solo contemplan `success`, `error` y `warning`). Por ende, el mensaje de cierre de sesión se pierde en silencio.
* **Evidencia de Código:**
  ```php
  // main.php solo incluye:
  <?php if (Session::hasFlash('success')): ?> ...
  <?php if (Session::hasFlash('error')): ?> ...
  <?php if (Session::hasFlash('warning')): ?> ...
  // Falta la verificación de 'info'
  ```
* **Recomendación:** Agregar el bloque correspondiente en `main.php` y `login.php`:
  ```php
  <?php if (Session::hasFlash('info')): ?>
      <div class="alert alert-info alert-dismissible fade show" role="alert">
          <i class="fas fa-info-circle mr-2"></i>
          <?php echo e(Session::getFlash('info')); ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
  <?php endif; ?>
  ```

---

#### 🟡 Hallazgo F-02: Credenciales de Prueba Hardcodeadas en la Vista de Login
* **Severidad:** Media
* **Ubicación:** `app/views/auth/login.php` (Líneas 140-146)
* **Descripción:** En la pantalla de login se muestra un cuadro informativo con las credenciales demo (`admin / admin123` y `usuario / usuario123`). Esto se renderiza siempre de manera estática, sin verificar si el sistema está en `APP_ENV === 'local'`.
* **Evidencia de Código:**
  ```html
  <div class="mt-4 pt-3 border-top text-center">
      <span class="text-xs text-muted d-block mb-1">Credenciales disponibles:</span>
      <div class="p-2 rounded" style="...">
          <div><strong>Admin:</strong> <code>admin</code> / <code>admin123</code></div>
          <div><strong>Usuario:</strong> <code>usuario</code> / <code>usuario123</code></div>
      </div>
  </div>
  ```
* **Recomendación:** Envolver este bloque en una condición PHP: `<?php if (defined('APP_ENV') && APP_ENV === 'local'): ?> ... <?php endif; ?>`.

---

#### 🔵 Hallazgo F-03: Menú Lateral (Sidebar) sin Iconos FontAwesome Oficiales
* **Severidad:** Baja
* **Ubicación:** `app/views/layouts/sidebar.php` (Líneas 30-120)
* **Descripción:** En la plantilla de navegación lateral de AdminLTE 3, los elementos `nav-link` carecen de las etiquetas `<i class="nav-icon fas fa-..."></i>`. Esto afecta la presentación visual y no cumple con la directriz de interfaz enriquecida de AdminLTE.
* **Evidencia de Código:**
  ```html
  <li class="nav-item">
      <a href="<?php echo BASE_URL; ?>/olimpiadas" class="nav-link ...">
          <p>Olimpiadas</p>
      </a>
  </li>
  ```
* **Recomendación:** Incorporar a cada ítem su icono semántico (`fa-tachometer-alt`, `fa-medal`, `fa-trophy`, `fa-map-marked-alt`, `fa-users`, `fa-futbol`, `fa-gavel`, `fa-list-ol`).

---

### 2.3. Transversal

---

#### 🟠 Hallazgo T-01: Discrepancia del Modelo de Negocio (Departamentos Internos vs. Cooperativas)
* **Severidad:** Alta
* **Ubicación:** `app/controllers/DashboardController.php` (Líneas 133-138) y `database/seeds.sql` (Líneas 32-37)
* **Descripción:** En `AGENTS.md` se establece formalmente que el torneo es una **Olimpiada Inter-Cooperativas** (donde las delegaciones son `COSMOL R.L.`, `SAGUAPAC R.L.`, `CRE R.L.`, `COTAS R.L.`). Sin embargo:
  1. `database/seeds.sql` inserta delegaciones departamentales antiguas (`Gerencia Comercial`, `Planta Operaciones`, `Tecnología & Redes`, `Administración & Finanzas`).
  2. En `DashboardController.php`, cuando el medallero está vacío, se inyecta un array estático con esas delegaciones ficticias y medallas inventadas, lo cual altera los datos reales del cliente.
* **Evidencia de Código:**
  ```php
  if (empty($medalleroTop)) {
      $medalleroTop = [
          ['pos' => 1, 'delegacion' => 'Gerencia Comercial', 'color' => '#007bff', 'oro' => 1, 'plata' => 0, 'bronce' => 0, 'total' => 1],
          ['pos' => 2, 'delegacion' => 'Planta Operaciones', 'color' => '#28a745', 'oro' => 0, 'plata' => 1, 'bronce' => 0, 'total' => 1],
  ```
* **Recomendación:** Actualizar los datos semilla para que coincidan con las cooperativas matrices reales y eliminar el mock data hardcodeado del controlador. Si el medallero está vacío, la vista debe mostrar un estado vacío informativo (*Empty State*).

---

#### 🟠 Hallazgo T-02: Módulo de Portal Público No Implementado (Fase 9 Pendiente)
* **Severidad:** Alta
* **Ubicación:** Directorio `app/views/public/` (inexistente) y `public/index.php`
* **Descripción:** La documentación maestra `AGENTS.md` y el Roadmap contemplan un **Portal Público** para espectadores, atletas y familias sin necesidad de autenticación (`/public/home`, `/public/medallero`, `/public/posiciones`, `/public/calendario`, `/public/sedes`). En el estado actual del repositorio, no existen rutas públicas ni la carpeta `app/views/public/`. Todos los endpoints actuales exigen `AuthHelper::requireLogin()`.
* **Evidencia:** El directorio `app/views/` contiene únicamente `admin/`, `auth/`, `errors/` y `layouts/`.
* **Recomendación:** Desarrollar las vistas públicas de la Fase 9 con layout para público general y registrar las rutas abiertas en `public/index.php`.

---

#### 🟡 Hallazgo T-03: Ausencia de Pruebas Automatizadas
* **Severidad:** Media
* **Ubicación:** Raíz del proyecto
* **Descripción:** El repositorio carece por completo de pruebas unitarias o de integración (PHPUnit). El aseguramiento de calidad depende exclusivamente de validaciones manuales vía navegador según `Docs/GUIA_PRUEBAS_LOCAL.md`, lo que eleva el riesgo de regresiones en cálculos matemáticos de tablas y sanciones.
* **Recomendación:** Configurar una suite básica de PHPUnit para validar reglas de puntuación (`app/models/Posicion.php`), cómputo de medallas y el sistema de sanciones.

---

## 3. Inconsistencias Detectadas entre Capas y Convenciones

| # | Inconsistencia | Capa Afectada | Detalle |
| :-: | :--- | :--- | :--- |
| **1** | Convención de Flash Messages | `MedalleroController` vs. Resto de controladores | `MedalleroController` usa `$_SESSION['flash_msg']`; todos los demás usan `Session::setFlash()`. |
| **2** | Validación CSRF | `MedalleroController` vs. `CsrfHelper` | `MedalleroController` usa `CsrfHelper::validateToken()` (inexistente); el resto usa `CsrfHelper::validate()`. |
| **3** | Formato de Respuestas JSON | `PosicionesController` vs. `Controller` | `PosicionesController::apiTabla` usa `echo json_encode(...)`; el controlador base provee `$this->json(...)`. |
| **4** | Exposición de Errores | Base de Datos & API vs. Seguridad | En `database.php` y en `PosicionesController::apiTabla` se expone `$e->getMessage()` directamente al cliente. |

---

## 4. Buenas Prácticas Observadas

* **Compatibilidad Estricta PHP 7.3:** No se identificaron usos incompatibles de versiones superiores (cero propiedades tipadas, cero closures `fn()`, cero operadores nullsafe `?->`).
* **Protección CSRF Generalizada:** Todos los formularios `POST` del sistema implementan `CsrfHelper::field()` y se valida el token antes de procesar inserciones o modificaciones.
* **Mitigación contra Inyecciones SQL:** Los modelos emplean consultas preparadas con PDO sin interpolar variables directas en el cuerpo del SQL.
* **Transacciones Atómicas:** Operaciones críticas como la anulación/levantamiento de sanciones y el registro de tarjetas ejecutan `beginTransaction()`, `commit()` y `rollBack()` con consistencia.
* **Separación de Responsabilidades:** Los controladores se mantienen delgados y la persistencia está encapsulada en sus respectivos modelos.
* **Manejo de Errores 404:** El enrutador redirige adecuadamente a una vista personalizada `errors/404.php`.

---

## 5. Plan de Acción Sugerido (Priorizado)

### Prioridad 1: Inmediata (Bloqueantes y Seguridad)
1. **Eliminar el backdoor en `app/models/Usuario.php` (Líneas 90-95):** Retirar la validación que permite entrar con `admin123` o `password` a cualquier cuenta.
2. **Corregir el Fatal Error en `app/controllers/MedalleroController.php` (Líneas 172 y 246):** Sustituir `validateToken()` por `CsrfHelper::validate()`.
3. **Proteger mensajes de error en `app/config/database.php` (Línea 103):** Ocultar `$e->getMessage()` cuando `APP_DEBUG` sea falso.

### Prioridad 2: Alta (Estabilidad y Limpieza)
4. **Homogeneizar flash messages en `app/controllers/MedalleroController.php`:** Migrar a `Session::setFlash()`.
5. **Optimizar consulta N+1 en `app/models/Posicion.php`:** Precargar todos los sets de vóley en una sola consulta agrupada.
6. **Habilitar visualización de alertas `'info'`:** En `app/views/layouts/main.php` y `app/views/auth/login.php` para no perder mensajes del sistema como el de cierre de sesión.
7. **Normalizar endpoints JSON:** Utilizar `$this->json(...)` en `app/controllers/PosicionesController.php`.

### Prioridad 3: Media (Funcionalidad y Acabado)
8. **Alinear datos con el modelo de Cooperativas:** Limpiar datos demo ficticios en `app/controllers/DashboardController.php` y actualizar `database/seeds.sql`.
9. **Desarrollar el Portal Público (Fase 9):** Crear `app/views/public/` para la consulta abierta de resultados y medallero.
10. **Ajuste visual de AdminLTE:** Agregar iconos de FontAwesome en `app/views/layouts/sidebar.php`.
