# ROADMAP - Hoja de Ruta de Desarrollo: Cosmol-Deportes

Este documento define la **planificación estratégica, fases de desarrollo, hitos de entrega y criterios de aceptación** para la construcción del sistema **Cosmol-Deportes (Edición Olimpiadas)**.

Cada fase está diseñada para garantizar un desarrollo ordenado, incremental y alineado con los estándares del proyecto:
- **Compatibilidad estricta con PHP 7.3** (sin sintaxis de PHP 7.4+).
- **Entorno XAMPP** (Apache 2.4 + MariaDB/MySQL).
- **Diseño UI profesional con AdminLTE 3** (Bootstrap 4, FontAwesome 5, jQuery).
- **Patrón Arquitectónico MVC** nativo y desacoplado con **PDO**.
- **Enfoque de Olimpiadas Deportivas Multidisciplinarias** (Medallero, grupos, nóminas y régimen disciplinario).

---

## Diagrama de Fases del Proyecto

```text
+-----------------------------------------------------------------------------------+
| FASE 1: Entorno, Arquitectura Base MVC y Layout AdminLTE 3                        |
+-----------------------------------------------------------------------------------+
                                          │
                                          ▼
+-----------------------------------------------------------------------------------+
| FASE 2: Base de Datos Relacional Completa (Schema, Seeds y Conexión PDO)          |
+-----------------------------------------------------------------------------------+
                                          │
                                          ▼
+-----------------------------------------------------------------------------------+
| FASE 3: Seguridad, Autenticación y Control de Acceso por Roles (ADMIN / USUARIO)  |
+-----------------------------------------------------------------------------------+
                                          │
                                          ▼
+-----------------------------------------------------------------------------------+
| FASE 4: Módulos Maestros (Olimpiadas, Disciplinas, Reglas, Sedes y Delegaciones)  |
+-----------------------------------------------------------------------------------+
                                          │
                                          ▼
+-----------------------------------------------------------------------------------+
| FASE 5: Torneos, Creación de Grupos, Equipos y Nómina de Jugadores                |
+-----------------------------------------------------------------------------------+
                                          │
                                          ▼
+-----------------------------------------------------------------------------------+
| FASE 6: Fixture, Programación de Encuentros y Actas de Partidos                   |
+-----------------------------------------------------------------------------------+
                                          │
                                          ▼
+-----------------------------------------------------------------------------------+
| FASE 7: Régimen Disciplinario (Registro y Levantamiento/Quitar Sanciones)        |
+-----------------------------------------------------------------------------------+
                                          │
                                          ▼
+-----------------------------------------------------------------------------------+
| FASE 8: Cálculo de Posiciones por Deporte y Medallero Olímpico General            |
+-----------------------------------------------------------------------------------+
                                          │
                                          ▼
+-----------------------------------------------------------------------------------+
| FASE 9: Vistas Públicas / Espectador, Optimización Responsive y Cierre            |
+-----------------------------------------------------------------------------------+
```

---

## Detalle de Fases de Desarrollo

---

### FASE 1: Entorno, Arquitectura Base MVC y Layout AdminLTE 3
> **Objetivo:** Establecer el esqueleto del proyecto, el Front Controller, el enrutamiento limpio y la plantilla maestra de AdminLTE 3 lista para renderizar vistas.

- [x] **1.1. Estructura de Directorios:**
  - Crear la jerarquía completa: `app/` (`config`, `controllers`, `models`, `views`, `helpers`), `public/` (`css`, `js`, `adminlte`, `uploads`), `database/` y `Docs/`.
- [x] **1.2. Servidor Web y Reescritura:**
  - Configurar `.htaccess` en la raíz y en `public/` para canalizar solicitudes hacia `public/index.php`.
- [x] **1.3. Enrutador y Front Controller (PHP 7.3):**
  - Implementar un enrutador ligero en `app/helpers/Router.php` que despache peticiones HTTP hacia controladores y acciones (`Controller@metodo`).
- [x] **1.4. Integración de Assets de AdminLTE 3:**
  - Integrar los estilos CSS de AdminLTE 3, Bootstrap 4, FontAwesome 5 y scripts de jQuery en `public/adminlte/`.
- [x] **1.5. Layout Maestro de AdminLTE:**
  - Crear las vistas compartidas en `app/views/layouts/`:
    - `header.php` / `head.php`: Metadatos, fuentes, links CSS.
    - `navbar.php`: Barra superior con perfil y notificaciones.
    - `sidebar.php`: Menú lateral dinámico con enlaces y badges de estado.
    - `footer.php`: Pie de página y scripts JS.
    - `main.php`: Contenedor principal (`wrapper`, `content-wrapper`, `content-header`, `content`).

**Entregables:**
- Proyecto navegable en `http://localhost/cosmol-deportes/public/`.
- Página de bienvenida/dashboard de prueba renderizada con AdminLTE 3.

---

### FASE 2: Base de Datos Relacional Completa
> **Objetivo:** Crear el modelo de datos relacional para MariaDB/MySQL con integridad referencial, índices y datos semilla iniciales.

- [x] **2.1. Script de Estructura (`database/schema.sql`):**
  - Tablas: `usuarios`, `olimpiadas`, `delegaciones`, `disciplinas`, `reglas_puntuacion`, `torneos`, `torneo_grupos`, `equipos`, `jugadores`, `sedes`, `canchas`, `partidos`, `partido_sets`, `partido_incidencias`, `sanciones`, `medallero`.
  - Configuración de claves foráneas con restricciones `ON DELETE CASCADE / RESTRICT` según corresponda.
  - Índices en columnas de búsqueda frecuente (`partido_id`, `torneo_id`, `jugador_id`, `grupo_id`).
- [x] **2.2. Script de Datos Semilla (`database/seeds.sql`):**
  - Usuario Administrador por defecto con clave hasheada (`password_hash`).
  - Disciplinas estándar: Fútbol, Futsal, Básquetbol, Voleibol.
  - Reglas de puntuación iniciales parametrizadas para cada deporte.
- [x] **2.3. Gestor de Conexión PDO (`app/config/database.php`):**
  - Patrón Singleton/Factory para conexión PDO segura, codificación UTF-8 (`utf8mb4`) y manejo de excepciones en modo `ERRMODE_EXCEPTION`.

**Entregables:**
- Scripts ejecutables sin errores desde phpMyAdmin o consola MySQL de XAMPP.
- Clase de conexión PDO lista para ser utilizada por los Modelos.

---

### FASE 3: Seguridad, Autenticación y Control de Acceso
> **Objetivo:** Implementar el sistema de login por nombre de usuario (`username`), control de sesiones, protección CSRF y control de roles (ADMIN vs USUARIO).

- [x] **3.1. Clases Auxiliares de Seguridad:**
  - `Session.php`: Inicio de sesión seguro, regeneración de ID, manejo de mensajes Flash (`success`, `error`, `warning`).
  - `CsrfHelper.php`: Generación y validación de tokens anti-CSRF para formularios `POST`.
  - `AuthHelper.php`: Métodos estáticos de verificación (`isLogged()`, `isAdmin()`, `requireLogin()`, `requireAdmin()`, `username()`).
  - `Validator.php`: Sanitización y validación de entradas de formularios (soporte para regla `alpha_dash` para nombres de usuario).
  - `Env.php` y `.env` / `.env.example`: Gestión segura de variables de entorno y protección en `.gitignore`.
- [x] **3.2. Modelo `Usuario.php`:**
  - Búsqueda por nombre de usuario (`findByUsername`), búsqueda alternativa por email, verificación de credenciales con `password_verify()`, registro de nuevos usuarios con rol `USUARIO` por defecto.
- [x] **3.3. Controlador y Vistas de Autenticación (`AuthController.php`):**
  - Autenticación mediante **Nombre de Usuario (`username`)** y contraseña.
  - Formulario de Registro claro y validado: Nombre completo, Nombre de usuario único (alfanumérico y guiones), Correo electrónico (opcional para contacto), Contraseña y confirmación.
  - Vistas con estilo AdminLTE: `views/auth/login.php` y `views/auth/register.php`.
  - Manejo de inicio de sesión, cierre de sesión y redirección según rol.
- [x] **3.4. Protección de Rutas:**
  - Middleware / filtro de rutas para impedir el acceso no autorizado al panel administrativo.

**Entregables:**
- Flujo de login por nombre de usuario y registro de cuentas completamente funcional, validado y protegido.

---

### FASE 4: Módulos Maestros (Olimpiadas, Disciplinas, Sedes y Delegaciones)
> **Objetivo:** Proveer al Administrador de las interfaces para configurar la base de la Olimpiada y las entidades institucionales.

- [x] **4.1. Módulo de Olimpiadas (`OlimpiadaController.php` + `Olimpiada.php`):**
  - Crear, editar, activar/desactivar la edición de la Olimpiada.
  - Vistas completas con AdminLTE 3 (`index`, `create`, `edit`).
- [x] **4.2. Módulo de Disciplinas y Reglas de Puntuación (`DisciplinaController.php` + `Disciplina.php` + `ReglaPuntuacion.php`):**
  - Listado y creación de disciplinas deportivas con iconos de FontAwesome.
  - Configuración de puntaje: puntos por victoria, empate, derrota, derrota ajustada/presentación, y formato de sets o cuartos.
- [x] **4.3. Módulo de Sedes y Canchas (`SedeController.php` + `Sede.php` + `Cancha.php`):**
  - CRUD de complejos deportivos / sedes, incluyendo coordenadas GPS (`latitud`, `longitud`) para navegación de rutas y cálculo de trayecto.
  - Administración de canchas asociadas por sede (nombre de cancha, tipo de superficie, disponibilidad).
  - Botón "Cómo llegar" en la tabla de sedes para abrir ruta directa desde la ubicación actual.
- [x] **4.4. Módulo de Delegaciones (`DelegacionController.php` + `Delegacion.php`):**
  - CRUD de delegaciones institucionales (áreas o departamentos de la institución).
  - Configuración de colores representativos, alias y delegados responsables.

**Entregables:**
- Pantallas administrativas de gestión maestra integradas con tablas AdminLTE, badges y modales.

---

### FASE 5: Torneos, Creación de Grupos, Equipos y Nómina de Jugadores
> **Objetivo:** Configurar la estructura de competencia de cada disciplina, la distribución de grupos y el padrón de atletas.

- [x] **5.1. Módulo de Torneos por Disciplina (`TorneoController.php` + `Torneo.php`):**
  - Creación de torneo vinculado a la Olimpiada activa, disciplina y rama (Masculino, Femenino, Mixto).
  - Modalidad: Fase de Grupos + Playoffs, Liga directa, Eliminación directa, ida o ida/vuelta.
- [x] **5.2. Módulo de Gestión de Grupos (`GrupoController.php` + `Grupo.php`):**
  - Crear grupos dentro de un torneo (`Grupo A`, `Grupo B`, `Grupo C`, etc.).
  - Asignar y desasignar equipos a cada grupo.
  - Visualización del listado de grupos con los equipos participantes.
- [x] **5.3. Módulo de Equipos (`EquipoController.php` + `Equipo.php`):**
  - Registrar equipo vinculado a una delegación y a un torneo/grupo.
  - Asignación de delegado, color de camiseta y escudo.
- [x] **5.4. Módulo de Nómina de Jugadores (`JugadorController.php` + `Jugador.php`):**
  - CRUD de jugadores por equipo (documento, nombres, apellidos, dorsal, posición, foto).
  - Validación de dorsales únicos por equipo.
  - Control de estado del jugador (`Habilitado`, `Suspendido`, `Inactivo`).

**Entregables:**
- Formularios interactivos para armar grupos de competencia y registrar las plantillas oficiales de jugadores.
- Documento de auditoría y entrega en `Docs/ENTREGA_FASE_5.md`.

---

### FASE 6: Fixture, Programación de Encuentros y Actas de Partidos
> **Objetivo:** Generar el cronograma unificado de partidos y registrar los resultados e incidencias.

- [x] **6.1. Programación y Fixture (`PartidoController.php` + `Partido.php`):**
  - Programación de partidos: selección de Torneo, Grupo/Fase, Local, Visitante, Sede, Cancha, Fecha y Hora.
  - Detección de colisiones de horario en canchas y cruce de partidos para un mismo equipo (ventana de 90 minutos).
  - Visualización del fixture filtrable por torneo, estado, fecha y jornada.
  - **Visualización del Lugar del Partido y Ruta GPS ("Cómo llegar"):**
    - Cada partido muestra claramente el complejo deportivo y la cancha donde se juega.
    - Al hacer clic en el botón "Cómo llegar (GPS)", el sistema obtiene la ubicación actual del usuario (`navigator.geolocation.getCurrentPosition()`) y traza la ruta guiada en Google Maps en tiempo real hacia las coordenadas GPS (`latitud`, `longitud`) de la sede.
- [x] **6.2. Actas de Partidos, Marcadores y Sets (`PartidoSet.php` + `Incidencia.php`):**
  - Registro de resultados y control de cierre (`marcador_local`, `marcador_visitante`).
  - Desglose por sets para voleibol y disciplinas con cómputo automático de sets ganados.
  - Cronología de incidencias: registro de goles, puntos canasta, tarjetas amarillas, rojas y faltas con vinculación a la plantilla de jugadores.
  - Cambio de estados del encuentro (`Programado`, `En_Juego`, `Finalizado`, `Suspendido`, `WO`).

**Entregables:**
- Sistema de calendario deportivo interactivo, actas digitales de partidos y navegación asistida por GPS a las canchas.
- Documento de auditoría y entrega en `Docs/ENTREGA_FASE_6.md`.

---

### FASE 7: Régimen Disciplinario (Registro y Levantamiento de Sanciones)
> **Objetivo:** Administrar las amonestaciones, tarjetas y suspensiones con trazabilidad total.

- [ ] **7.1. Registro de Sanciones (`SancionController.php` + `Sancion.php`):**
  - Registro de tarjetas amarillas y rojas (directa o doble amarilla).
  - Sanciones por fechas de suspensión o expulsión del torneo.
  - Vinculación con el jugador, partido, minuto y observaciones.
- [ ] **7.2. Control de Inhabilitación:**
  - Marcado automático del jugador como inhabilitado al acumular tarjetas rojas o límite de amarillas.
  - Alerta visual en la alineación del partido siguiente si un jugador está suspendido.
- [ ] **7.3. Funcionalidad Clave: Quitar / Levantar Sanciones:**
  - Botón y Modal en AdminLTE para **"Levantar Sanción"**.
  - Opciones de resolución: *Anulación por apelación*, *Error arbitral corregido*, *Indulto / Cumplimiento anticipado*.
  - Registro del motivo de levantamiento, fecha y usuario administrador que autorizó la acción.
  - Restauración inmediata del estado `Habilitado` del jugador.
- [ ] **7.4. Boletín Disciplinario:**
  - Vista del resumen de sanciones activas e históricas para consulta de delegados y administradores.

**Entregables:**
- Módulo disciplinario con flujo completo para aplicar, consultar y anular sanciones con auditoría.

---

### FASE 8: Cálculo de Posiciones por Deporte y Medallero Olímpico
> **Objetivo:** Implementar los motores de cálculo automático para las tablas de posiciones y la clasificación general de la Olimpiada.

- [ ] **8.1. Motor de Tablas de Posiciones (`PosicionesController.php`):**
  - Algoritmo dinámico que lee los partidos `Finalizados`:
    - Fútbol/Futsal: PJ, PG, PE, PP, GF, GC, DG, Pts (3-1-0).
    - Básquetbol: PJ, PG, PP, PF, PC, DP, Pts (2-1-0).
    - Voleibol: PJ, PG, PP, Sets Ganados, Sets Perdidos, Ratio, Pts (3, 2, 1, 0).
  - Cálculo independiente por cada grupo (`Grupo A`, `Grupo B`).
- [ ] **8.2. Módulo de Medallero Olímpico General (`MedalleroController.php`):**
  - Asignación de medallas al culminar cada torneo/categoría:
    - 🥇 **Oro:** 1º Lugar.
    - 🥈 **Plata:** 2º Lugar.
    - 🥉 **Bronce:** 3º Lugar.
  - Cómputo del Medallero por Delegación ordenado por: Oros > Platas > Bronces.
  - Tabla de Puntuación Olímpica Acumulada por delegación para proclamar al *Campeón General*.

**Entregables:**
- Tablas de posiciones por grupos y vista del Medallero Olímpico Oficial en tiempo real.

---

### FASE 9: Vistas Públicas, Optimización Responsive y Cierre
> **Objetivo:** Habilitar el portal público/espectador para los usuarios registrados y culminar la documentación de despliegue.

- [ ] **9.1. Portal para Usuarios y Espectadores (`views/public/`):**
  - Vista pública del Medallero Olímpico interactivo con gráficos o tarjetas visuales.
  - Consulta de calendarios de juego y resultados en tiempo real.
  - Visualización de tablas de posiciones por disciplina y grupo.
  - Asistente de ruta "Cómo llegar a la cancha del partido" accesible con 1 clic para espectadores desde dispositivos móviles.
- [ ] **9.2. Ajustes Responsive y Pulido UI (AdminLTE):**
  - Pruebas de usabilidad en resoluciones móviles y pantallas de escritorio.
  - Optimización de tablas responsivas (`table-responsive`) y modales.
- [ ] **9.3. Auditoría de Compatibilidad PHP 7.3:**
  - Ejecutar verificación de sintaxis (`php -l`) en todos los archivos PHP para certificar que no existe código incompatible con 7.3.
- [ ] **9.4. Guía de Instalación Local (`Docs/INSTALACION.md`):**
  - Paso a paso para importar base de datos en XAMPP, configurar `database.php` y ejecutar el sistema localmente.

**Entregables:**
- Sistema completo, funcional, probado y listo para su uso en producción local bajo XAMPP.

---

## Matriz de Seguimiento y Estado de Fases

| Fase | Descripción | Estado | Prioridad |
| :--- | :--- | :---: | :---: |
| **Fase 1** | Entorno, Arquitectura Base MVC y Layout AdminLTE 3 | ✅ Completada | Alta |
| **Fase 2** | Base de Datos Relacional Completa (Schema y Seeds) | ✅ Completada | Crítica |
| **Fase 3** | Seguridad, Autenticación y Control de Roles | ✅ Completada | Crítica |
| **Fase 4** | Módulos Maestros (Olimpiadas, Deportes, Sedes, Delegaciones) | ✅ Completada | Alta |
| **Fase 5** | Torneos, Creación de Grupos, Equipos y Jugadores | ✅ Completada | Alta |
| **Fase 6** | Fixture, Programación y Actas de Partidos | ⏳ Pendiente | Alta |
| **Fase 7** | Régimen Disciplinario (Anotar y Quitar Sanciones) | ⏳ Pendiente | Alta |
| **Fase 8** | Cálculo de Posiciones y Medallero Olímpico | ⏳ Pendiente | Alta |
| **Fase 9** | Vistas Públicas, Auditoría PHP 7.3 y Documentación | ⏳ Pendiente | Media |

---

## Reglas Clave a Recordar Durante la Ejecución

1. **Sintaxis PHP 7.3:** No usar propiedades tipadas, arrow functions ni operadores incompatibles.
2. **Seguridad PDO:** Toda consulta a la base de datos debe ser con sentencias preparadas.
3. **AdminLTE 3:** Toda vista debe mantener la coherencia estética con las clases y componentes de AdminLTE.
4. **Validación:** Validar siempre los datos en el servidor antes de persistirlos.
