# AGENTS.md - Guía Maestra y Reglas de Desarrollo: Cosmol-Deportes (Edición Olimpiadas Inter-Cooperativas)

Este documento constituye la **especificación técnica oficial, contexto funcional de negocio y conjunto de reglas obligatorias de desarrollo** para cualquier agente de Inteligencia Artificial o desarrollador que trabaje en el proyecto **Cosmol-Deportes**.

---

## 1. Visión General del Proyecto: Sistema de Olimpiadas Deportivas COSMOL

**Cosmol-Deportes** es la plataforma web oficial de la **Cooperativa de Servicios Públicos COSMOL R.L.** para la gestión, difusión y seguimiento de sus **Olimpiadas Deportivas y Recreativas Institucionales e Inter-Cooperativas**. A este evento concurren delegaciones deportivas de distintas cooperativas y departamentos del país (ej. **COSMOL R.L.**, **SAGUAPAC R.L.**, **CRE R.L.**, **COTAS R.L.**, filiales y áreas institucionales).

### Arquitectura Dual del Sistema:
1. **Portal Web Informativo Público (Sin Login):** Cualquier usuario (deportista, familiar, delegado o público en general) puede acceder libremente para conocer el desarrollo de la olimpiada en tiempo real. Dispone de vistas y secciones por disciplina (Fútbol, Futsal, Básquetbol, Voleibol, Billar, Karaoke, Cacho, etc.) con datos de sedes, rutas GPS ("Cómo llegar"), fixture, resultados y tablas de posiciones.
2. **Panel de Gestión y Control Administrativo (AdminLTE 3):** Exclusivo para el comité organizador y administradores (`ADMIN`). Es el único rol que realiza login en el sistema.

### Características Clave de las Olimpiadas:
1. **Macro-Evento Olímpico:** Evento central (*"Olimpiadas Cosmol 2026"*) que engloba simultáneamente múltiples disciplinas deportivas tradicionales y recreativas.
2. **Delegaciones y Cooperativas:** Equipos que representan a las cooperativas e instituciones participantes a lo largo de todas las disciplinas.
3. **Múltiples Disciplinas y Ramas:** Fútbol, Futsal, Básquetbol, Voleibol, Billar, Karaoke, Cacho, etc., en ramas Masculino, Femenino y Mixto.
4. **Medallero General / Clasificación Olímpica Global:** Cálculo en tiempo real de medallas (Oro, Plata, Bronce) y puntaje acumulado por delegación para proclamar al *Campeón Olímpico General*.
5. **Estructura Interna por Disciplina:** Fase de grupos, liguillas y playoffs de eliminación directa con tablas de posiciones automáticas.
6. **Régimen Disciplinario:** Control de amonestaciones, expulsiones y suspensiones, con función administrativa para **anotar y levantar/quitar sanciones** (indulto por confraternización).
7. **Geolocalización de Sedes y Rutas GPS ("Cómo llegar"):** Herramienta interactiva para que las delegaciones visitantes puedan trazar su ruta en tiempo real hacia las canchas y recintos deportivos.

---

## 2. Stack Tecnológico y Restricciones Estrictas de Versión

> [!IMPORTANT]
> **RESTRICCIÓN CRÍTICA DE VERSIÓN: PHP 7.3 Y XAMPP**
> El entorno de ejecución del cliente/servidor está estrictamente basado en **PHP 7.3** sobre **XAMPP** (Apache 2.4 + MariaDB 10.4 / MySQL 5.7).
> Queda **TERMINANTEMENTE PROHIBIDO** utilizar sintaxis de versiones posteriores (PHP 7.4, 8.0, 8.1, 8.2, 8.3).
> 
> **Reglas sintácticas obligatorias para PHP 7.3:**
> - **NO** usar propiedades tipadas en clases (ej. `public int $id;` es ERROR fatal en 7.3; usar `public $id;` o documentar con PHPDoc `/** @var int */`).
> - **NO** usar funciones flecha (ej. `fn($x) => $x` es ERROR fatal en 7.3; usar closures clásicos `function($x) { return $x; }`).
> - **NO** usar expresiones `match` (usar `switch` o `if/else`).
> - **NO** usar el operador nullsafe `?->` (usar validaciones `isset()` o ternarios clásicos `$obj ? $obj->metodo() : null`).
> - **NO** usar argumentos nombrados (`nombreFuncion(param: $valor)` es ERROR fatal).
> - **NO** usar tipos de unión `int|string` ni tipo `mixed` en declaraciones de tipos.
> - **NO** usar promoción de propiedades en constructor (`public function __construct(public $id)` es ERROR fatal).
> - **SÍ** se permite el tipado escalar en parámetros y retornos de funciones/métodos soportados en PHP 7.1-7.3 (`function test(int $a): ?string`).

- **Servidor Local:** XAMPP (Apache 2.4 + MariaDB 10.4 / MySQL 5.7).
- **Backend:** PHP 7.3 nativo, orientado a objetos, estructurado con patrón MVC y persistencia vía PDO.
- **Frontend / UI:** **AdminLTE 3** (basado en **Bootstrap 4**, jQuery y FontAwesome 5). Toda la interfaz administrativa y de usuario debe emplear el layout, temas y componentes de AdminLTE.
- **Base de Datos:** MySQL / MariaDB (motor InnoDB, codificación UTF-8 / utf8mb4, claves foráneas con integridad referencial).
- **Enrutamiento:** Front Controller (`public/index.php`) con reescritura de URLs amigables vía Apache `.htaccess`.
- **Regla PDO Crítica:** Con `PDO::ATTR_EMULATE_PREPARES => false`, **NUNCA** repetir el mismo nombre de parámetro en una consulta SQL (ej. usar `:eq_local` y `:eq_visitante`, nunca `:equipo_id` dos veces).

---

## 3. Arquitectura del Proyecto (Patrón MVC)

```text
cosmol-deportes/
├── app/
│   ├── config/              # Configuraciones de conexión PDO, URLs base y constantes
│   │   ├── config.php
│   │   └── database.php
│   ├── controllers/         # Controladores (procesan peticiones y coordinan modelos/vistas)
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── OlimpiadaController.php
│   │   ├── ReglaPuntuacionController.php
│   │   ├── DisciplinaController.php
│   │   ├── GrupoController.php
│   │   ├── DelegacionController.php
│   │   ├── EquipoController.php
│   │   ├── JugadorController.php
│   │   ├── SancionController.php
│   │   ├── SedeController.php
│   │   ├── CanchaController.php
│   │   ├── FixtureController.php
│   │   ├── PartidoController.php
│   │   ├── MedalleroController.php
│   │   ├── PosicionesController.php
│   │   └── ReporteController.php
│   ├── models/              # Modelos PDO (consultas preparadas, sin HTML)
│   │   ├── Model.php        # Clase base PDO
│   │   ├── Usuario.php
│   │   ├── Olimpiada.php
│   │   ├── Disciplina.php
│   │   ├── ReglaPuntuacion.php
│   │   ├── Torneo.php
│   │   ├── Grupo.php
│   │   ├── Delegacion.php
│   │   ├── Equipo.php
│   │   ├── Jugador.php
│   │   ├── Sancion.php
│   │   ├── Sede.php
│   │   ├── Cancha.php
│   │   ├── Partido.php
│   │   ├── Incidencia.php
│   │   └── Medallero.php
│   ├── views/               # Plantillas HTML integradas con AdminLTE 3
│   │   ├── layouts/         # Layout principal de AdminLTE (head, header/navbar, sidebar, footer)
│   │   │   ├── main.php
│   │   │   ├── navbar.php
│   │   │   ├── sidebar.php
│   │   │   └── footer.php
│   │   ├── auth/            # Login, registro y perfil de delegados
│   │   ├── admin/           # Vistas de gestión para el Comité Organizador (ADMIN)
│   │   │   ├── dashboard/
│   │   │   ├── olimpiadas/
│   │   │   ├── reglas_puntuacion/
│   │   │   ├── disciplinas/
│   │   │   ├── grupos/
│   │   │   ├── delegaciones/
│   │   │   ├── equipos/
│   │   │   ├── jugadores/
│   │   │   ├── sanciones/
│   │   │   ├── sedes/
│   │   │   ├── canchas/
│   │   │   ├── fixture/
│   │   │   ├── partidos/
│   │   │   ├── medallero/
│   │   │   └── reportes/
│   │   └── public/          # Portal público para trabajadores, atletas y familias
│   │       ├── home.php
│   │       ├── medallero.php
│   │       ├── posiciones.php
│   │       ├── calendario.php
│   │       ├── resultados.php
│   │       └── sedes.php
│   └── helpers/             # Utilidades (AuthHelper, Session, Validator, CsrfHelper, Flash)
├── database/                # Scripts SQL de estructura y datos de cooperativas
│   ├── schema.sql
│   └── seeds.sql
├── Docs/                    # Documentación técnica, diagnósticos y entregables por fase
│   ├── ROADMAP.md
│   ├── PLANTEAMIENTO_DISCIPLINAS_RECREATIVAS.md
│   ├── DIAGNOSTICO_INCONSISTENCIAS.md
│   └── GUIA_PRUEBAS_LOCAL.md
├── public/                  # DocumentRoot web accesible por Apache
│   ├── index.php            # Front Controller principal
│   ├── .htaccess            # Reescritura limpia a index.php
│   ├── adminlte/            # Assets de AdminLTE 3 (css, js, plugins, fontawesome)
│   ├── css/                 # Estilos complementarios y de impresión
│   ├── js/                  # Scripts JavaScript (GPS, modales, interactividad)
│   └── uploads/             # Logos de cooperativas, escudos y fotos de atletas
└── AGENTS.md                # Esta guía maestra de desarrollo
```

---

## 4. Roles de Usuario y Control de Acceso

### 4.1. Regla Mandatoria: Solo el Administrador (ADMIN) Inicia Sesión
El sistema aplica una política estricta de autenticación y permisos:
- **SOLO los usuarios con rol `ADMIN` pueden iniciar sesión** en la plataforma.
- Cualquier intento de inicio de sesión por usuarios con rol distinto a `ADMIN` es bloqueado de inmediato con un mensaje explícito de denegación de acceso.
- El formulario de inicio de sesión (`/login`) está adaptado exclusivamente como **Acceso Administrativo** del Comité Organizador.
- El registro público de cuentas (`/registro`) está **deshabilitado**, ya que el público en general no requiere cuenta para consultar la información del campeonato.
- Todas las rutas administrativas (`/dashboard`, `/disciplinas`, `/sedes`, `/partidos`, etc.) exigen rol `ADMIN` mediante `AuthHelper::requireAdmin()`.
- En caso de que un usuario no autenticado intente acceder a una ruta administrativa, es redirigido a `/login` con una alerta informativa.

**Facultades del Rol Administrador:**
- Crear y gestionar la **Olimpiada Activa** (fechas, lema, sedes habilitadas).
- Configurar las **Disciplinas Deportivas y Reglas de Puntuación** (Fútbol, Futsal, Básquetbol, Voleibol, Billar, Karaoke, Cacho, etc.).
- Crear disciplinas/competencias, definir categorías (Masculino/Femenino/Mixto) y modalidad (Liga, Grupos + Llaves, Eliminación directa).
- **Crear grupos** (Grupo A, Grupo B, etc.) y sembrar o sortear a los equipos representativos de cada cooperativa.
- Registrar **Delegaciones (Cooperativas)** y **Equipos**.
- Registrar y administrar la **nómina de Jugadores** por equipo (datos personales, dorsal, posición, foto).
- Gestionar **Sanciones Disciplinarias**:
  - Registrar sanciones (tarjetas amarillas, rojas, fechas de suspensión, faltas graves).
  - **Quitar / Levantar sanciones:** Función especial para anular sanciones o marcar indulto por confraternización con registro de motivo oficial.
- Generar y administrar el **Fixture**: asignación de fechas, horas, sedes y canchas con validación anti-colisión.
- Cargar resultados oficiales (marcadores globales, detalle de sets, puntos de jurado en juegos recreativos).
- Controlar el **Medallero Olímpico** y la tabla acumulada por delegación.

### 4.2. Público General, Deportistas y Delegaciones Departamentales (Libre Acceso - Sin Login)
Cualquier visitante, deportista o delegado de los diferentes departamentos tiene acceso libre, inmediato y sin restricciones al **Portal Informativo de las Olimpiadas COSMOL**:
- No requiere registrarse ni ingresar contraseñas.
- Acceso directo a la página principal (`/`) con diseño optimizado para computadoras y teléfonos móviles.
- Navegación por disciplinas deportivas (Fútbol, Futsal, Básquet, Voleibol, Billar, Karaoke, Cacho, etc.).
- Visualización de Sedes, Canchas y trazado interactivo de rutas GPS (**"Cómo llegar"** con Google Maps desde su ubicación actual).
- Consulta de **tablas de posiciones en vivo**, fixture de partidos, cronograma por fechas y resultados recientes.
- Consulta del **Medallero Olímpico General** y podios por disciplina.
- En la barra de navegación y cabecera de la página principal pública se incluye un botón destacado de **"Ingresar / Iniciar Sesión"** para que el Administrador acceda al panel.

---

## 5. Módulos Funcionales Detallados

### 5.1. Módulo de Macro-Evento y Medallero Olímpico General
- Una Olimpiada agrupa a todas las cooperativas participantes durante un período determinado.
- **Cómputo Oficial de Medallas:**
  - **Oro 🥇 (1º Lugar)**, **Plata 🥈 (2º Lugar)**, **Bronce 🥉 (3º Lugar)** por cada competencia finalizada.
  - Criterio de ordenamiento estándar olímpico: `Oros DESC > Platas DESC > Bronces DESC`.
- **Puntaje Olímpico Acumulado (Copa de Cooperativas):**
  - Puntos asignados por posición (ej. 1º: 10 pts, 2º: 7 pts, 3º: 5 pts, 4º: 3 pts, etc.) para consagrar a la **Cooperativa Campeona Absoluta**.

### 5.2. Módulo de Disciplinas Deportivas Reglamentarias y Puntuación
Cada disciplina cuenta con su lógica matemática de cómputo en la tabla de posiciones:
- **Fútbol / Futsal:**
  - Victoria: 3 pts, Empate: 1 pt, Derrota: 0 pts.
  - Criterios de desempate: Puntos, Diferencia de Goles (DG), Goles a Favor (GF), Goles en Contra (GC), Enfrentamiento directo.
- **Básquetbol:**
  - Victoria: 2 pts, Derrota con presentación: 1 pt, No presentación (W.O.): 0 pts.
  - Sin empates. Criterios: Puntos, Diferencia de Puntos (canastas).
- **Voleibol:**
  - Sistema por Sets:
    - Victoria 3-0 o 3-1: 3 pts para ganador, 0 pts para perdedor.
    - Victoria 3-2: 2 pts para ganador, 1 pt para perdedor.
  - Sin empates. Criterios: Puntos acumulados, Sets a Favor (SF), Sets en Contra (SC), Ratio de sets, Ratio de puntos.

### 5.3. Módulo de Disciplinas Recreativas y Juegos de Salón (Confraternización)
Integración nativa para competencias tradicionales de cooperativas:
- **Karaoke:** Evaluación por jurado/mesa sobre interpretación y afinación. Marcador directo por puntaje acumulado.
- **Billar:** Partidas directas por chicos/mesas ganadas.
- **Cacho:** Juego tradicional de dados (Generala/Alalay) por puntaje de planilla.
- **Actas de Juegos Recreativos:** Carga de resultados directos (sin sets ni tiempos reglamentarios), actualización instantánea de la competencia y adjudicación directa de medallas.

### 5.4. Módulo de Competencias Olímpicas (`torneos`), Grupos y Fases
- Configuración de cada competencia: Olimpiada asociada, disciplina, rama (Varones, Damas, Mixto, Senior), tipo de torneo (Grupos + Playoffs, Liga todos contra todos, Relámpago).
- **Gestión de Grupos:**
  - Creación dinámica de grupos (`Grupo A`, `Grupo B`, `Grupo C`, etc.).
  - Asignación balanceada de las cooperativas participantes.
  - Tabla de posiciones calculada de manera independiente por grupo.
- **Fases Eliminatorias (Playoffs):**
  - Llaves de Cuartos de final, Semifinales, Definición por el 3er Puesto (Bronce) y Gran Final (Oro y Plata).

### 5.5. Módulo de Cooperativas (Delegaciones), Equipos y Atletas
- **Cooperativas (Delegaciones):** Entidades matrices (ej. `COSMOL R.L.`, `SAGUAPAC R.L.`, `CRE R.L.`). Contienen datos de contacto del delegado, colores y escudo oficial.
- **Equipos:** El representativo que la cooperativa inscribe en una competencia específica (ej. *COSMOL - Fútbol Varones*).
- **Atletas / Jugadores:**
  - Nómina oficial de trabajadores/socios: Documento de identidad, nombres, apellidos, fecha de nacimiento, dorsal, posición y fotografía.
  - Validación anti-duplicados de documento y dorsal en el mismo representativo.

### 5.6. Módulo de Sedes, Canchas y Geolocalización GPS ("Cómo llegar")
- **Asignación del Escenario:** Cada partido se asocia a un recinto deportivo (**Sede**) y a un campo específico (**Cancha**).
- **Ruta Interactiva GPS:**
  - Al hacer clic en la sede o cancha, el sistema invoca la API de geolocalización (`navigator.geolocation.getCurrentPosition()`) y abre la ruta optimizada (Google Maps / Waze / OpenStreetMap) hacia las coordenadas exactas (`latitud`, `longitud`), facilitando la llegada de los trabajadores y sus familias.
- **Detección de Colisiones:** Alerta automática que impide asignar dos encuentros a la misma cancha en horarios solapados o programar a una misma cooperativa simultáneamente.

### 5.7. Módulo de Fixtures y Programación
- Generación automática o manual de jornadas (Round Robin todos contra todos o eliminación directa).
- Asignación de fecha, hora y escenario con validación en tiempo real.
- Estados de partido: `Programado`, `En Calentamiento`, `En Juego`, `Finalizado`, `Suspendido`, `WO`.

### 5.8. Módulo de Actas Oficiales y Resultados
- Planilla interactiva de control para mesa de control y planilleros:
  - Carga de marcador global.
  - Carga detallada de sets (en vóley/básquet) o puntuación de jurado (en karaoke/recreativas).
  - Registro de incidencias (goles, canastas, puntos, tarjetas amarillas/rojas, faltas graves).
- El cierre del acta en estado `Finalizado` actualiza en una sola transacción las tablas de posiciones y los expedientes disciplinarios.

### 5.9. Módulo de Régimen Disciplinario y Levantamiento de Sanciones
- **Registro de Tarjetas y Faltas:**
  - Tarjetas amarillas, rojas directas, doble amarilla, fechas de suspensión o faltas administrativas.
- **Inhabilitación Automática:** Los jugadores suspendidos aparecen claramente bloqueados/inhabilitados para la alineación de las siguientes jornadas.
- **Levantamiento / Quita de Sanciones (Indulto Olímpico):**
  - Función administrativa exclusiva para anular o conmutar sanciones por: `Anulada por apelación`, `Error arbitral corregido`, `Cumplimiento anticipado/Indulto de Confraternización`.
  - Deja registro inmutable de auditoría: quién la levantó, fecha y motivo oficial.

### 5.8. Módulo de Portal Informativo Web de las Olimpiadas COSMOL (Público)
Diseñado específicamente para las **delegaciones de otros departamentos, deportistas y espectadores**:
- **Página Principal Informativa (`/`):**
  - **Botón Destacado de "Ingresar / Iniciar Sesión":** Ubicado en la barra superior y pie de página para que el Administrador acceda al panel de control (`/login`).
  - Bienvenida institucional a las delegaciones visitantes de los distintos departamentos.
  - Resumen estadístico: Total disciplinas, delegaciones participantes, sedes habilitadas y partidos programados.
  - Sección interactiva de **Disciplinas Deportivas** (Fútbol, Futsal, Básquetbol, Voleibol, Billar, Karaoke, Cacho, etc.).
  - Sección de **Medallero Olímpico General** en vivo (Oro, Plata, Bronce y Puntos).
  - Sección de **Sedes y Canchas** con botón directo de navegación GPS.
  - Bloque de próximos partidos y marcadores recientes en tiempo real.
  - Mosaico de **Delegaciones Departamentales**.
- **Vistas y Secciones Dedicadas por Disciplina Deportiva (`/disciplina/{id}`):**
  - Ficha técnica de la disciplina y sus reglas de puntuación oficiales.
  - Sedes y canchas específicas donde se disputa la disciplina, con botón **"¿Cómo llegar? (GPS)"**.
  - **Tabla de Posiciones Oficial** por rama/categoría (Masculino, Femenino, Mixto) y grupos.
  - **Fixture y Calendario de Encuentros** de la disciplina con estados (Programado, En Juego, Finalizado con marcador).
- **Herramienta Interactiva de Geolocalización GPS ("Cómo llegar"):**
  - Mediante la API de geolocalización del navegador del usuario (`navigator.geolocation.getCurrentPosition()`), traza la ruta óptima en Google Maps desde la posición geográfica actual del visitante hasta las coordenadas de la sede o cancha seleccionada.

---

## 6. Modelo de Base de Datos Relacional

Estructura de tablas normalizadas en MySQL / MariaDB (InnoDB, UTF-8):

1. `usuarios` (id, nombre, username, email, password, rol: 'ADMIN'/'USUARIO', estado, created_at, updated_at)
2. `olimpiadas` (id, nombre, anio, lema, fecha_inicio, fecha_fin, estado: 'Planificada'/'En Curso'/'Finalizada')
3. `delegaciones` (id, nombre, alias, color_representativo, logo_url, responsable, telefono)
4. `disciplinas` (id, nombre, descripcion, icono, estado)
5. `reglas_puntuacion` (id, disciplina_id, pts_victoria, pts_empate, pts_derrota, pts_derrota_ajustada, permite_empate, formato_sets, sets_para_ganar)
6. `torneos` (id, olimpiada_id, disciplina_id, regla_puntuacion_id, nombre, rama: 'Masculino'/'Femenino'/'Mixto', tipo_torneo: 'Grupos_Playoff'/'Liga'/'Eliminatoria', estado, fecha_inicio, fecha_fin)
7. `torneo_grupos` (id, torneo_id, nombre_grupo: 'Grupo A'/'Grupo B', orden)
8. `equipos` (id, delegacion_id, torneo_id, grupo_id, nombre, nombre_corto, logo_url, delegado_contacto)
9. `jugadores` (id, equipo_id, documento, nombres, apellidos, dorsal, posicion, foto_url, estado: 'Habilitado'/'Suspendido'/'Inactivo')
10. `sedes` (id, nombre, direccion, telefono, latitud, longitud, estado, created_at)
11. `canchas` (id, sede_id, nombre_cancha, tipo_superficie, estado, created_at)
12. `partidos` (id, torneo_id, grupo_id, fase: 'Grupos'/'Cuartos'/'Semifinal'/'Tercer_Puesto'/'Final', jornada, equipo_local_id, equipo_visitante_id, cancha_id, fecha_partido, hora_partido, marcador_local, marcador_visitante, estado: 'Programado'/'En_Juego'/'Finalizado'/'Suspendido'/'WO', observaciones, created_at)
13. `partido_sets` (id, partido_id, numero_set, puntos_local, puntos_visitante)
14. `partido_incidencias` (id, partido_id, equipo_id, jugador_id, tipo: 'GOL'/'PUNTO'/'AMARILLA'/'ROJA'/'FALTA', minuto_tiempo, observaciones)
15. `sanciones` (id, jugador_id, partido_id, torneo_id, tipo_sancion: 'AMARILLA'/'ROJA'/'PARTIDOS_SUSPENSION'/'EXPULSION', partidos_suspension, partidos_cumplidos, motivo, estado: 'Activa'/'Cumplida'/'Levantada', motivo_levantamiento, fecha_levantamiento, created_at)
16. `medallero` (id, olimpiada_id, delegacion_id, torneo_id, tipo_medalla: 'ORO'/'PLATA'/'BRONCE', puntos_olimpicos)

---

## 7. Reglas de Codificación y Diseño UI (AdminLTE 3)

### 7.1. Compatibilidad Estricta PHP 7.3
- Toda clase y método debe ser compatible al 100% con PHP 7.3 nativo.
- Todas las consultas preparadas PDO deben utilizar parámetros únicos (`:eq_local`, `:eq_visitante`, etc.).
- Saneamiento obligatorio en vistas con `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')` o el helper `e($val)`.
- Tokens CSRF obligatorios en todos los formularios de acción `POST`.
- Contraseñas almacenadas con `password_hash($pass, PASSWORD_BCRYPT)`.

### 7.2. Frontend con AdminLTE 3
Toda la interfaz debe seguir el estándar visual de **AdminLTE 3**:
1. **Layout Estándar:**
   - Barra superior (Navbar): perfil de usuario, alertas, botón colapsable de sidebar.
   - Barra lateral (Sidebar): navegación categorizada con iconos temáticos de FontAwesome 5 (`nav-sidebar`, `nav-item`, `nav-link`).
   - Contenedor de contenido (`content-wrapper`, `content-header`, `content`).
   - Pie de página (`main-footer`).
2. **Componentes AdminLTE Clave:**
   - **Small Boxes / Info Boxes:** Para indicadores clave en el Dashboard (Total disciplinas, equipos inscritos, partidos de hoy, líderes del medallero).
   - **Cards (`card`, `card-header`, `card-tools`, `card-body`):** Para agrupar tablas, formularios y gráficos de posiciones.
   - **Badges (`badge badge-success`, `badge-danger`, `badge-warning`, `badge-info`):** Para estados de partidos, grupos y tarjetas disciplinarias.
   - **Modales (`modal modal-danger`, etc.):** Para confirmaciones de borrado, registro rápido de incidencias y **levantamiento de sanciones**.
   - **Tablas Responsivas (`table table-hover table-striped table-responsive`):** Diseñadas para visualizarse correctamente en móviles y pantallas grandes con badges legibles y botones de acción rápida.
3. **Mensajes Flash:**
   - Uso de alertas (`alert alert-success alert-dismissible`, `alert alert-danger`) para confirmar operaciones al usuario.

### 7.3. Principios de Controladores y Modelos (Arquitectura MVC Limpia)
- **Controladores Delgados:** Se limitan a validar la entrada, invocar métodos del modelo y cargar la vista con los datos requeridos.
- **Modelos Robustos:** Concentran la lógica de consultas SQL, sentencias preparadas PDO con parámetros únicos, transacciones atómicas (`beginTransaction`, `commit`, `rollBack`) y reglas de negocio.
- **Vistas Limpias:** Únicamente renderizan HTML y variables inyectadas por el controlador, sin consultas SQL directas ni lógica pesada de persistencia.

---

## 8. Guía de Ejecución Modular y Roadmap del Proyecto

El desarrollo del sistema sigue una secuencia ordenada y progresiva:

### Fases Completadas (Infraestructura y Gestión Administrativa):
1. **Fase 1: Configuración Base y Base de Datos** *(Completada)*
   - Conexión PDO en `app/config/` y arquitectura base.
   - Script SQL `database/schema.sql` y datos semilla `database/seeds.sql` con soporte para olimpiadas, disciplinas tradicionales y recreativas, sedes, grupos, jugadores y sanciones.
2. **Fase 2: Arquitectura MVC, Layout AdminLTE 3 y Control de Roles Estricto** *(Completada)*
   - Front Controller `public/index.php`, router limpio y `.htaccess`.
   - Layout AdminLTE 3 (`head`, `navbar`, `sidebar`, `footer`).
   - **Regla Estricta de Acceso:** **SOLO los administradores (`ADMIN`) pueden hacer login.**
   - Registro público deshabilitado. Redirección automática de visitantes no autenticados al login administrativo o al portal público.
3. **Fase 3: Módulos de Gestión Olímpica, Equipos y Jugadores** *(Completada)*
   - CRUD de Olimpiadas, Disciplinas (Competencias y Reglas de Puntuación) y Sedes/Canchas con coordenadas geográficas.
   - Creación de Grupos y siembra de equipos representativos por cooperativa.
   - CRUD de Delegaciones, Equipos y **registro de Jugadores** con dorsales y fotografías.
4. **Fase 4: Fixture Inteligente, Partidos y Régimen Disciplinario** *(Completada)*
   - Fixture interactivo con algoritmo anti-colisión por grupos y fases eliminatorias.
   - Actas de partidos, sets, marcadores e incidencias para deportes reglamentarios y juegos recreativos (Karaoke, Billar, Cacho).
   - **Control Disciplinario:** Registro de amonestaciones y función para **quitar/levantar sanciones (indulto)** con auditoría.
5. **Fase 5: Tablas de Posiciones y Medallero Olímpico Administrativo** *(Completada)*
   - Motor de cómputo dinámico de posiciones por disciplina (Fútbol, Futsal, Básquetbol, Voleibol, etc.).
   - Panel administrativo del Medallero Olímpico Oficial y asignación de podios.

---

### Fase 6: Portal Informativo Público de Olimpiadas COSMOL
Esta fase se desarrolla para garantizar que los deportistas y visitantes cuenten con la mejor experiencia informativa de libre acceso:

- **Paso 6.1: Roles y Permisos (Solo Admin Login) y Enrutamiento Base** *(Completado)*
  - `AuthController` valida estrictamente `user['rol'] === 'ADMIN'`.
  - Bloqueo de inicio de sesión a usuarios comunes con mensaje explicativo.
  - Formulario de login adaptado como **Acceso Administrativo**.
  - Botón visible y elegante de "Iniciar Sesión" (Acceso Admin) en la cabecera del portal público.
  - La ruta raíz `/` sirve directamente el Portal Informativo público (`PortalController@index`).
  - El panel administrativo queda protegido bajo `/dashboard` mediante `AuthHelper::requireAdmin()`.

- **Paso 6.2: Portada Informativa Principal (`/`)** *(Completado)*
  - Hero banner con lema y bienvenida a las delegaciones participantes.
  - Resumen estadístico (disciplinas, delegaciones, sedes, encuentros).
  - Parrilla interactiva de disciplinas deportivas con accesos a su información respectiva.
  - Medallero olímpico general en vivo.
  - Resumen de sedes y mapa de ruta GPS.
  - Próximos partidos y resultados recientes.

- **Paso 6.3: Vistas Dedicadas por Disciplina Deportiva (`/disciplina/{id}`)** *(Completado)*
  - Secciones individuales para cada disciplina: Fútbol, Futsal, Básquetbol, Voleibol, Billar, Karaoke, Cacho, etc.
  - Información de sedes y canchas específicas con botón GPS **"Cómo llegar"**.
  - Tablas de posiciones por grupos de la disciplina.
  - Fixture y calendario de partidos de la disciplina con marcadores en tiempo real.
  - Reglas de puntuación aplicadas.

- **Paso 6.4: Módulo de Sedes y Navegación GPS ("Cómo llegar")** *(Completado)*
  - Vista general de sedes (`/portal/sedes`).
  - Activación de `navigator.geolocation.getCurrentPosition()` para abrir la ruta en Google Maps desde la posición actual del usuario hasta la cancha correspondiente.

- **Paso 6.5: Vistas Públicas de Tablas de Posiciones, Fixture y Medallero** *(Completado)*
  - Tablas de Posiciones por grupo (`/portal/posiciones`).
  - Fixture general con filtros por disciplina, estado y fecha (`/portal/fixture`).
  - Medallero Olímpico General y podios por disciplina (`/portal/medallero`).

- **Paso 6.6: Cierre, Reportes Impresos y Auditoría Final** *(En Curso / Siguientes Pasos)*
  - Reportes ejecutivos e impresiones en formato carta (`print.css`): Boletín del Medallero y Planillas de Delegación.
  - Verificación sintáctica con `php -l` para asegurar 100% compatibilidad con PHP 7.3 sobre XAMPP.
  - Manual de instalación y puesta en marcha del proyecto.
