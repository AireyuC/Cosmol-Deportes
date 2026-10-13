# Diagnóstico de Inconsistencias y Pendientes de Corrección
## Proyecto: Cosmol-Deportes (Edición Olimpiadas)

**Fecha del Diagnóstico:** Octubre 2026  
**Rama Base:** `devChichico` (con merge de `devFabian`)  
**Objetivo del Documento:** Registro formal de inconsistencias técnicas detectadas en el código durante la auditoría integral (Fases 1 a 6) para su resolución una vez concluidas las fases activas de desarrollo.

---

## 📌 Resumen de Hallazgos

| # | Módulo / Archivo | Nivel | Tipo | Descripción Breve |
| :-: | :--- | :-: | :-: | :--- |
| **1** | `app/models/Partido.php` | 🔴 **Crítico** | Error SQL PDO | Parámetro con nombre duplicado (`:equipo_id`) en detección de colisión. Provoca excepción `HY093`. |
| **2** | `app/views/layouts/navbar.php` | 🟡 **Medio** | Interfaz / UX | Falta el botón disparador `dropdown-toggle` para abrir el menú de usuario y cerrar sesión en el Navbar. |
| **3** | `app/views/admin/partidos/acta.php` | 🟢 **Menor** | Lógica Visual | Falta sentencia `break;` en `case 'Finalizado'`, haciendo que la insignia se muestre roja (danger). |
| **4** | `app/models/Torneo.php` | 🟢 **Alineado** | Persistencia DB | Fallback de `regla_puntuacion_id` al editar torneos para evitar que se anule a `NULL`. *(Listo para confirmar)* |

---

## 🛠️ Detalle Técnico de Inconsistencias y Soluciones

---

### 1. Error SQL en Detección de Colisión de Equipos
* **Ubicación:** `app/models/Partido.php` &rarr; método `verificarColisionEquipo()` (Líneas 251-280)
* **Causa Raíz:**
  En la consulta SQL preparada se utilizó dos veces el mismo placeholder con nombre (`:equipo_id`):
  ```sql
  WHERE (p.equipo_local_id = :equipo_id OR p.equipo_visitante_id = :equipo_id)
  ```
  y en el array de parámetros solo se asoció una única vez:
  ```php
  $params = [
      'equipo_id' => (int)$equipoId,
      'fecha'     => $fecha,
      'hora'      => $hora
  ];
  ```
  Dado que en `app/config/database.php` está configurado `PDO::ATTR_EMULATE_PREPARES => false`, el motor MySQL/MariaDB no permite reutilizar el mismo nombre de parámetro múltiples veces en una consulta preparada nativa.
* **Impacto:**
  Cada vez que un administrador intenta programar o reprogramar un partido con fecha y hora asignadas, el sistema lanza la excepción:
  > `SQLSTATE[HY093]: Invalid parameter number`
* **Solución Técnica:**
  Separar los marcadores en dos parámetros independientes (`:eq_local` y `:eq_visitante`), tal como se implementó correctamente en `Usuario.php` (`:ident1` e `:ident2`):
  ```diff
  --- a/app/models/Partido.php
  +++ b/app/models/Partido.php
  @@ -254,9 +254,10 @@ class Partido extends Model {
           }
   
           $params = [
  -            'equipo_id' => (int)$equipoId,
  -            'fecha'     => $fecha,
  -            'hora'      => $hora
  +            'eq_local'     => (int)$equipoId,
  +            'eq_visitante' => (int)$equipoId,
  +            'fecha'        => $fecha,
  +            'hora'         => $hora
           ];
   
           $excludeSql = "";
  @@ -269,7 +270,7 @@ class Partido extends Model {
                   FROM partidos p
                   INNER JOIN equipos el ON p.equipo_local_id = el.id
                   INNER JOIN equipos ev ON p.equipo_visitante_id = ev.id
  -                WHERE (p.equipo_local_id = :equipo_id OR p.equipo_visitante_id = :equipo_id)
  +                WHERE (p.equipo_local_id = :eq_local OR p.equipo_visitante_id = :eq_visitante)
                     AND p.fecha_partido = :fecha
                     AND p.estado NOT IN ('Finalizado', 'Suspendido', 'WO')
                     AND ABS(TIME_TO_SEC(TIMEDIFF(p.hora_partido, :hora))) < 5400
  ```

---

### 2. Disparador del Menú de Usuario Ausente en Navbar
* **Ubicación:** `app/views/layouts/navbar.php` (Líneas 14-32)
* **Causa Raíz:**
  En el commit `3aa6446` ("*correccion de texto en dashboard*"), al retirar elementos de la barra superior, se eliminó accidentalmente el enlace `<a>` con la clase `dropdown-toggle` y el atributo `data-toggle="dropdown"`:
  ```html
  <!-- Menú de Usuario -->
  <?php if (AuthHelper::isLogged()): ?>
      <li class="nav-item dropdown user-menu">
          <!-- FALTA EL ENLACE DISPARADOR AQUÍ -->
          <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow-sm border-0">
  ```
* **Impacto:**
  El usuario con sesión iniciada no tiene ningún elemento interactivo en la barra superior donde hacer clic para desplegar el menú que contiene el enlace **"Cerrar Sesión"**.
* **Solución Técnica:**
  Restaurar el botón/enlace de usuario respetando la paleta sobria de Fabián:
  ```diff
  --- a/app/views/layouts/navbar.php
  +++ b/app/views/layouts/navbar.php
  @@ -13,6 +13,12 @@
           <!-- Menú de Usuario -->
           <?php if (AuthHelper::isLogged()): ?>
               <li class="nav-item dropdown user-menu">
  +                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
  +                    <span class="d-none d-md-inline font-weight-bold text-dark mr-1"><?php echo e(AuthHelper::nombre()); ?></span>
  +                    <span class="badge badge-primary font-weight-normal" style="background-color: #0066B2;">
  +                        <?php echo e(AuthHelper::rol()); ?>
  +                    </span>
  +                </a>
                   <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow-sm border-0">
                       <!-- User header -->
                       <li class="user-header" style="background-color: #0066B2; color: #FFFFFF;">
  ```

---

### 3. Salto (`fallthrough`) por falta de `break;` en Acta Digital
* **Ubicación:** `app/views/admin/partidos/acta.php` (Líneas 76-80)
* **Causa Raíz:**
  En la evaluación del estado del partido para asignar el color de la insignia (*badge*):
  ```php
  case 'Finalizado':
      $badgeClass = 'success';
  case 'Suspendido':
      $badgeClass = 'danger';
      break;
  ```
  Falta la instrucción `break;` tras asignar `'success'`.
* **Impacto:**
  Al renderizar un partido con estado **Finalizado**, el flujo continúa inmediatamente a la siguiente condición (`case 'Suspendido'`), sobreescribiendo `$badgeClass` con `'danger'`. El partido terminado se visualiza con badge rojo en lugar de verde.
* **Solución Técnica:**
  Agregar el `break;` correspondiente:
  ```diff
  --- a/app/views/admin/partidos/acta.php
  +++ b/app/views/admin/partidos/acta.php
  @@ -75,6 +75,7 @@
                                   break;
                               case 'Finalizado':
                                   $badgeClass = 'success';
  +                                break;
                               case 'Suspendido':
                                   $badgeClass = 'danger';
                                   break;
  ```

---

### 4. Regla de Puntuación al Actualizar Torneo
* **Ubicación:** `app/models/Torneo.php` &rarr; método `updateTorneo()` (Líneas 95-120)
* **Causa Raíz:**
  El formulario de edición de torneo no incluye un selector para `regla_puntuacion_id`. Al ejecutar la actualización, el valor quedaba como `null` en la base de datos, desvinculando la regla del torneo.
* **Solución Técnica:**
  Se implementó el fallback automático que consulta la regla por defecto de la `disciplina_id` seleccionada:
  ```php
  $reglaId = !empty($data['regla_puntuacion_id']) ? (int)$data['regla_puntuacion_id'] : null;
  if (!$reglaId && !empty($data['disciplina_id'])) {
      $reglaModel = new ReglaPuntuacion();
      $regla = $reglaModel->getByDisciplinaId((int)$data['disciplina_id']);
      if ($regla) {
          $reglaId = (int)$regla['id'];
      }
  }
  ```
* **Estado:** Verificado en `app/models/Torneo.php`, listo para ser incluido formalmente al finalizar la etapa.

---

## ✅ Aspectos Auditados y Verificados con Éxito

Durante la auditoría exhaustiva de las Fases 1 a la 6 se comprobó la plena consistencia de los siguientes apartados:

1. **Sintaxis y Compatibilidad PHP 7.3:**
   - 100% de los archivos analizados con `php -l` sin fallos.
   - Cero propiedades tipadas en clases (`public int $x`).
   - Cero funciones flecha (`fn()`).
   - Cero expresiones `match`.
   - Cero operadores nullsafe (`?->`).
2. **Integridad del Enrutador (`public/index.php`):**
   - Las 79 rutas activas tienen controladores y métodos existentes y válidos.
   - Rutas para Fases 7 y 8 (`/sanciones`, `/medallero`, `/posiciones`) enlazadas a `DashboardController@index` de manera ordenada.
3. **Seguridad y CSRF:**
   - Todos los formularios `POST` del sistema cuentan con `CsrfHelper::field()`.
   - Todos los controladores receptores validan `CsrfHelper::validate()`.
4. **Base de Datos y Modelos (PDO):**
   - Las 16 tablas del esquema relacional (`database/schema.sql`) y sus columnas coinciden de forma exacta con los mapeos en los 15 modelos.
   - Todas las consultas a la base de datos se ejecutan con `PDO::prepare()` parametrizado.
5. **Estilo Visual AdminLTE 3:**
   - Línea sobria y corporativa alineada a los estándares de Fabián: paleta `#0066B2`, botones institucionales, ausencia total de emojis y tablas limpias.

---

## 📌 Estado de Correcciones Aplicadas

Todas las correcciones documentadas han sido implementadas y validadas al 100%:

- [x] 1. **`app/models/Partido.php`:** Corregido (parámetros separados en `:eq_local` y `:eq_visitante`, eliminando la excepción `HY093`).
- [x] 2. **`app/views/layouts/navbar.php`:** Corregido (restaurado enlace disparador con `data-toggle="dropdown"` para cerrar sesión).
- [x] 3. **`app/views/admin/partidos/acta.php`:** Corregido (agregada sentencia `break;` en `case 'Finalizado'`, restaurando insignia verde).
- [x] 4. **`app/models/Torneo.php`:** Corregido (fallback automático de regla de puntuación mediante `disciplina_id`).
- [x] 5. **Prueba funcional:** Ejecutada satisfactoriamente sin errores de sintaxis ni excepciones de PDO.




implementar 
karoke 
billar 
cacho