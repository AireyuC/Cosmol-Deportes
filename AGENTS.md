# AGENTS.md - Guía Maestra y Reglas de Desarrollo: Cosmol-Deportes (Edición Olimpiadas)

Este documento constituye la **especificación técnica, contexto funcional y conjunto de reglas obligatorias** para cualquier agente de Inteligencia Artificial o desarrollador que trabaje en el proyecto **Cosmol-Deportes**.

---

## 1. Visión General del Proyecto: Sistema de Olimpiadas Deportivas

**Cosmol-Deportes** es una plataforma web integral diseñada para la administración, programación, control disciplinario y seguimiento de **torneos deportivos multidisciplinarios bajo formato de Olimpiadas** (juegos deportivos institucionales, empresariales o comunitarios).

### Enfoque Tipo "Olimpiadas":
A diferencia de un torneo de un solo deporte, una Olimpiada deportiva se caracteriza por:
1. **Macro-Evento Olímpico:** Un evento central (ej. *"Olimpiadas Cosmol 2026"*) que engloba simultáneamente múltiples disciplinas deportivas.
2. **Múltiples Disciplinas y Ramas/Categorías:** Fútbol, Futsal, Básquetbol, Voleibol, etc., divididos en ramas (Masculino, Femenino, Mixto, Senior/Libre).
3. **Delegaciones / Equipos Institucionales:** Los equipos representan delegaciones (áreas, departamentos, sucursales o sindicatos) que compiten a lo largo de varias o todas las disciplinas.
4. **Medallero General / Clasificación Olímpica Global:** Además de la tabla de posiciones interna de cada deporte, el sistema calcula el **Medallero Olímpico** (Oro, Plata, Bronce) y/o una **Tabla General Acumulada de Puntos** para proclamar al *Campeón General de las Olimpiadas*.
5. **Estructura Interna por Disciplina:** Cada disciplina puede disputarse mediante **Fase de Grupos** (Grupo A, Grupo B, etc.) con posterior pase a fases de eliminación directa (cuartos de final, semifinales, 3er puesto y final) o formato de liga.
6. **Nómina de Jugadores y Régimen Disciplinario:** Registro detallado de jugadores por equipo, control estricto de sanciones (tarjetas amarillas/rojas, suspensiones por fechas) y la capacidad administrativa de **levantar o quitar sanciones** (por apelación, error arbitral o cumplimiento de fechas).
7. **Cronograma Maestro Unificado:** Fixture coordinado para evitar solapamiento de canchas y sedes entre distintos deportes.

---

## 2. Stack Tecnológico y Restricciones Estrictas de Versión

> [!IMPORTANT]
> **RESTRICCIÓN CRÍTICA DE VERSIÓN: PHP 7.3 Y XAMPP**
> El entorno de ejecución está basado en **PHP 7.3** sobre **XAMPP**.
> Queda **TERMINANTEMENTE PROHIBIDO** utilizar sintaxis de versiones posteriores (PHP 7.4, 8.0, 8.1, 8.2, 8.3).
> 
> **Reglas sintácticas obligatorias para PHP 7.3:**
> - **NO** usar propiedades tipadas en clases (ej. `public int $id;` es ERROR en 7.3; usar `public $id;` o documentar con PHPDoc `/** @var int */`).
> - **NO** usar funciones flecha (ej. `fn($x) => $x` es ERROR en 7.3; usar closures estándar `function($x) { return $x; }`).
> - **NO** usar expresiones `match` (usar `switch` o `if/else`).
> - **NO** usar el operador nullsafe `?->` (usar validaciones `isset()` o ternarios clásicos `$obj ? $obj->metodo() : null`).
> - **NO** usar argumentos nombrados (`nombreFuncion(param: $valor)` es ERROR).
> - **NO** usar tipos de unión `int|string` ni tipo `mixed` en declaraciones de tipos.
> - **NO** usar promoción de propiedades en constructor (`public function __construct(public $id)` es ERROR).
> - **SÍ** se permite el tipado escalar en parámetros y retornos de funciones/métodos soportados en PHP 7.1-7.3 (`function test(int $a): ?string`).

- **Servidor Local:** XAMPP (Apache 2.4 + MariaDB 10.4 / MySQL 5.7).
- **Backend:** PHP 7.3 nativo, orientado a objetos, estructurado con patrón MVC y persistencia vía PDO.
- **Frontend / UI:** **AdminLTE 3** (basado en **Bootstrap 4**, jQuery y FontAwesome 5). Toda la interfaz administrativa y de usuario debe emplear el layout, temas y componentes de AdminLTE.
- **Base de Datos:** MySQL / MariaDB (motor InnoDB, codificación UTF-8 / utf8mb4, claves foráneas con integridad referencial).
- **Enrutamiento:** Front Controller (`public/index.php`) con reescritura de URLs vía Apache `.htaccess`.

---

## 3. Arquitectura del Proyecto (Patrón MVC)

La estructura del directorio del proyecto debe organizarse bajo una estricta separación de responsabilidades:

```text
cosmol-deportes/
├── app/
│   ├── config/              # Configuraciones de conexión, URLs base y constantes
│   │   ├── config.php
│   │   └── database.php
│   ├── controllers/         # Controladores (procesan peticiones y coordinan modelos/vistas)
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── OlimpiadaController.php
│   │   ├── DisciplinaController.php
│   │   ├── TorneoController.php
│   │   ├── GrupoController.php
│   │   ├── DelegacionController.php
│   │   ├── EquipoController.php
│   │   ├── JugadorController.php
│   │   ├── SancionController.php
│   │   ├── SedeController.php
│   │   ├── FixtureController.php
│   │   ├── PartidoController.php
│   │   ├── MedalleroController.php
│   │   └── PosicionesController.php
│   ├── models/              # Modelos PDO (consultas preparadas, sin HTML)
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
│   │   ├── layouts/         # Layout principal de AdminLTE (head, header/navbar, sidebar, footer, scripts)
│   │   │   ├── main.php
│   │   │   ├── navbar.php
│   │   │   ├── sidebar.php
│   │   │   └── footer.php
│   │   ├── auth/            # Login, registro y perfil
│   │   ├── admin/           # Vistas de gestión para el rol Administrador
│   │   │   ├── dashboard/
│   │   │   ├── olimpiadas/
│   │   │   ├── torneos/
│   │   │   ├── grupos/
│   │   │   ├── equipos/
│   │   │   ├── jugadores/
│   │   │   ├── sanciones/
│   │   │   ├── sedes/
│   │   │   ├── fixture/
│   │   │   └── partidos/
│   │   └── public/          # Vistas para usuarios y público general
│   │       ├── medallero.php
│   │       ├── posiciones.php
│   │       ├── calendario.php
│   │       └── resultados.php
│   └── helpers/             # Clases auxiliares (AuthHelper, Session, Validator, CsrfHelper)
├── database/                # Scripts SQL de esquema y datos semilla
│   ├── schema.sql
│   └── seeds.sql
├── public/                  # DocumentRoot web accesible por Apache
│   ├── index.php            # Front Controller principal
│   ├── .htaccess            # Reescritura a index.php
│   ├── adminlte/            # Assets de AdminLTE 3 (css, js, plugins, fontawesome)
│   ├── css/                 # Estilos complementarios
│   ├── js/                  # Scripts JavaScript personalizados
│   └── uploads/             # Logos, escudos de equipos, fotos de jugadores
└── AGENTS.md                # Este documento de contexto y reglas
```

---

## 4. Roles de Usuario y Control de Acceso

### 4.1. Rol Administrador (ADMIN)
Control total sobre la olimpiada y sus torneos:
- Crear y gestionar la **Olimpiada Activa** (fechas, lema, sedes habilitadas).
- Configurar las **Disciplinas y Reglas de Puntuación** (fútbol, futsal, básquetbol, voleibol, etc.).
- Crear torneos por disciplina, definir categorías (Masculino/Femenino/Mixto) y modalidad (Liga, Grupos + Llaves, Eliminación directa).
- **Crear grupos** (Grupo A, Grupo B, etc.) y sortear o asignar equipos a cada grupo.
- Registrar **Delegaciones** (áreas de la institución) y **Equipos**.
- Registrar y administrar la **nómina de Jugadores** por equipo (datos personales, dorsal, posición, foto).
- Gestionar **Sanciones Disciplinarias**:
  - Registrar sanciones (tarjetas amarillas, rojas, fechas de suspensión, faltas graves).
  - **Quitar / Levantar sanciones:** Función especial para anular sanciones o marcar indulto/cumplimiento con registro de motivo.
- Generar y administrar el **Fixture**: asignación de fechas, horas, sedes y canchas.
- Cargar resultados de partidos (marcadores, sets, tiempos, incidencias).
- Controlar el **Medallero Olímpico** y la tabla acumulada por delegación.

### 4.2. Rol Usuario General / Espectador / Delegado (USUARIO)
- Registro y autenticación mediante **Nombre de Usuario** (`username`) y contraseña.
- Visualización interactiva del **Medallero Olímpico** en tiempo real.
- Consulta de **tablas de posiciones por grupos** y fases de cada disciplina.
- Consulta del **cronograma general / fixture** (partidos por jugar, resultados en vivo y finalizados).
- Visualización del **lugar del partido (sede y cancha)** con función interactiva para obtener la **ruta de navegación GPS ("Cómo llegar")** desde la ubicación actual del usuario.
- Visualización de la plantilla de jugadores por equipo y tabla de goleadores/figuras.
- Consulta del boletín de sanciones disciplinarias activas.

---

## 5. Módulos Funcionales Detallados

### 5.1. Módulo de Olimpiada y Medallero General
- Una Olimpiada agrupa todas las competencias durante un período.
- **Medallero:** Cálculo automático o manual de medallas:
  - **Oro (1º Lugar)**, **Plata (2º Lugar)**, **Bronce (3º Lugar)** por cada torneo/disciplina y categoría.
  - Ordenamiento estándar olímpico: número de Oros > número de Platas > número de Bronces.
- **Puntaje Olímpico Acumulado (opcional):** Puntos por posición para premiar a la delegación campeona general (ej. 1º: 10 pts, 2º: 7 pts, 3º: 5 pts, 4º: 3 pts, etc.).

### 5.2. Módulo de Disciplinas y Reglas de Puntuación
Cada disciplina posee su propia lógica de cómputo para la tabla de posiciones:
- **Fútbol / Futsal:**
  - Victoria: 3 pts, Empate: 1 pt, Derrota: 0 pts.
  - Criterios: Puntos, Diferencia de Goles (DG), Goles a Favor (GF), Goles en Contra (GC), Enfrentamiento directo.
- **Básquetbol:**
  - Victoria: 2 pts, Derrota con presentación: 1 pt, No presentación (W.O.): 0 pts.
  - No admite empates. Criterios: Puntos, Diferencia de Puntos (canastas).
- **Voleibol:**
  - Sistema por Sets:
    - Victoria 3-0 o 3-1: 3 pts para ganador, 0 pts para perdedor.
    - Victoria 3-2: 2 pts para ganador, 1 pt para perdedor.
  - No admite empates. Criterios: Puntos acumulados, Sets a Favor (SF), Sets en Contra (SC), Ratio de sets, Ratio de puntos.
- **Parametrización flexible:** Las reglas deben ser configurables por torneo o disciplina desde la base de datos.

### 5.3. Módulo de Torneos, Grupos y Fases
- Configuración de cada torneo: Olimpiada asociada, disciplina, rama (Varones, Damas, Mixto), tipo de torneo (Grupos + Playoffs, Liga todos contra todos, Relámpago eliminación directa), ida o ida/vuelta.
- **Gestión de Grupos:**
  - Capacidad de crear múltiples grupos por torneo (`Grupo A`, `Grupo B`, `Grupo C`, etc.).
  - Asignar y desasignar equipos a cada grupo.
  - Cálculo de tabla de posiciones independiente por cada grupo.
- **Fases Eliminatorias (Playoffs):**
  - Configuración de llaves: Cuartos de final, Semifinales, Partido por el 3er Puesto (Bronce), Gran Final (Oro y Plata).
  - Estado del torneo: `Borrador`, `Inscripciones`, `Fase de Grupos`, `Playoffs`, `Finalizado`.

### 5.4. Módulo de Delegaciones, Equipos y Jugadores
- **Delegaciones:** Representan las áreas de la institución (ej. *"Gerencia Comercial"*, *"Planta Operaciones"*, *"Tecnología"*).
- **Equipos:** Vinculados a una delegación y a un torneo específico (color de camiseta, escudo/logo, delegado responsable y teléfono).
- **Jugadores:**
  - Documento de identidad, nombres, apellidos, fecha de nacimiento, dorsal, posición, fotografía.
  - Asociación de jugadores a equipos.
  - Validación para evitar dorsales duplicados en un mismo equipo.

### 5.5. Módulo de Sanciones Disciplinarias (Anotar y Quitar Sanciones)
El sistema debe llevar un estricto expediente disciplinario:
- **Registro de Sanciones:**
  - Tipo de sanción: Tarjeta Amarilla, Tarjeta Roja (Directa o Doble Amarilla), Suspensión por Fechas (1 fecha, 2 fechas, etc.), Expulsión del Torneo, Multa económica/administrativa.
  - Datos: Jugador, partido (o resolución de mesa), torneo, minuto/tiempo, motivo/observación.
- **Control de Inhabilitación:**
  - Si un jugador tiene tarjeta roja o acumulación de amarillas (según reglamento), el sistema debe mostrarlo como **Inhabilitado / Suspendido** en las alineaciones del siguiente partido.
- **Acción Crucial: Quitar / Levantar Sanciones:**
  - El Administrador debe tener la funcionalidad en la interfaz para **quitar o anular una sanción**.
  - Opciones de levantamiento: `Anulada por apelación`, `Error arbitral corregido`, `Cumplimiento anticipado/Indulto`.
  - La sanción queda marcada como `Inactiva / Levantada`, restaurando inmediatamente la habilitación del jugador para jugar, dejando trazabilidad de quién y por qué la levantó.

### 5.6. Módulo de Fixtures, Programación y Geolocalización de Canchas
- **Asignación de Encuentros:** Asignación de fecha, hora, sede y cancha específica para cada encuentro.
- **Visualización del Lugar del Partido:**
  - Cada partido programado debe mostrar explícitamente el recinto deportivo (**Sede**) y el campo de juego (**Cancha**) donde se disputará el encuentro.
- **Ruta Interactiva desde Ubicación Actual ("Cómo llegar"):**
  - Al hacer clic en la cancha o sede del partido, el sistema activará la geolocalización del navegador del usuario (`navigator.geolocation.getCurrentPosition()`) y abrirá el trazado de la ruta (a través de Google Maps / Waze / OpenStreetMap) hacia las coordenadas geográficas (`latitud`, `longitud`) o la dirección exacta de la sede/cancha, permitiendo al espectador, delegado o deportista saber de forma precisa cómo llegar desde el lugar donde se encuentra en ese instante.
- Detección de colisiones de horario (alertar si una cancha ya está ocupada o si un equipo tiene partidos superpuestos).
- Estados de partido: `Programado`, `En Calentamiento`, `En Juego`, `Finalizado`, `Suspendido`, `W.O.`.

### 5.7. Módulo de Actas de Partidos y Resultados
- Carga interactiva de resultados:
  - Marcador global.
  - Detalle de sets o cuartos/tiempos según la disciplina.
- Registro de incidencias durante el partido (goles, puntos, tarjetas amarillas/rojas, faltas).
- Al marcar el partido como `Finalizado`, se actualizan automáticamente las tablas de posiciones y el estado de sanciones.

---

## 6. Modelo de Base de Datos Relacional

Estructura esencial de tablas para MySQL/MariaDB (InnoDB):

1. `usuarios` (id, nombre, username, email, password, rol: 'ADMIN'/'USUARIO', estado, created_at, updated_at)
2. `olimpiadas` (id, nombre, anio, lema, fecha_inicio, fecha_fin, estado: 'Planificada'/'En Curso'/'Finalizada')
3. `delegaciones` (id, nombre, alias, color_representativo, logo_url, responsable)
4. `disciplinas` (id, nombre, descripcion, icono, estado)
5. `reglas_puntuacion` (id, disciplina_id, pts_victoria, pts_empate, pts_derrota, pts_derrota_ajustada, permite_empate, formato_sets)
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
- Usar sintaxis POO compatible al 100% con PHP 7.3.
- Documentar tipos en bloques PHPDoc (`/** @var string */`, `/** @param int $id */`).
- Todas las consultas a la base de datos deben utilizar `PDO::prepare()` y `PDOStatement::execute()`. Prohibida cualquier concatenación directa de variables en SQL.
- Saneamiento de salidas con `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')` o mediante una función helper `e($val)`.
- Uso obligatorio de tokens CSRF en todos los formularios con métodos `POST`.
- Almacenamiento seguro de contraseñas usando `password_hash($pass, PASSWORD_BCRYPT)`.

### 7.2. Frontend con AdminLTE 3
Toda la interfaz debe seguir el estándar visual de **AdminLTE 3**:
1. **Layout Estándar:**
   - Barra superior (Navbar): perfil de usuario, alertas, botón colapsable de sidebar.
   - Barra lateral (Sidebar): navegación categorizada con iconos de FontAwesome (`nav-sidebar`, `nav-item`, `nav-link`).
   - Contenedor de contenido (`content-wrapper`, `content-header`, `content`).
   - Pie de página (`main-footer`).
2. **Componentes AdminLTE Clave:**
   - **Small Boxes / Info Boxes:** Para indicadores clave en el Dashboard (Total disciplinas, equipos inscritos, partidos de hoy, líderes del medallero).
   - **Cards (`card`, `card-header`, `card-tools`, `card-body`):** Para agrupar tablas, formularios y gráficos.
   - **Badges (`badge badge-success`, `badge-danger`, `badge-warning`, `badge-info`):** Para estados de partidos, grupos y tarjetas disciplinarias.
   - **Modales (`modal modal-danger`, etc.):** Para confirmaciones de borrado, registro rápido de incidencias y **levantamiento de sanciones**.
   - **Tablas Responsivas (`table table-hover table-striped table-responsive`):** Diseñadas para visualizarse correctamente en móviles y pantallas grandes.
3. **Mensajes Flash:**
   - Uso de alertas (`alert alert-success alert-dismissible`, `alert alert-danger`) para confirmar operaciones al usuario.

### 7.3. Principios de Controladores y Modelos
- **Controladores Delgados:** Se limitan a validar la entrada, invocar métodos del modelo y cargar la vista con los datos.
- **Modelos Robustos:** Concentran la lógica de consultas SQL, transacciones (`beginTransaction`, `commit`, `rollBack`) y reglas de datos.
- **Vistas Limpias:** Únicamente renderizan HTML y variables inyectadas por el controlador, sin consultas SQL directas ni lógica de negocio pesada.

---

## 8. Guía de Ejecución Modular para el Agente

Cuando se desarrollen los módulos, seguir esta secuencia ordenada:

1. **Fase 1: Configuración Base y Base de Datos**
   - Configuración de conexión PDO en `app/config/`.
   - Script SQL completo `database/schema.sql` con soporte para olimpiadas, grupos, jugadores y sanciones.
   - Script `database/seeds.sql` con datos iniciales (administrador por defecto, deportes base: Fútbol, Futsal, Básquetbol, Voleibol y reglas de puntuación).
2. **Fase 2: Arquitectura Base y Layout AdminLTE**
   - Front Controller `public/index.php`, router básico y `.htaccess`.
   - Plantilla base de AdminLTE 3 en `app/views/layouts/`.
   - Autenticación, control de sesiones y helpers de seguridad (CSRF, sanitización).
3. **Fase 3: Módulos de Gestión Olímpica y Equipos**
   - CRUD de Olimpiadas, Disciplinas y Sedes/Canchas.
   - CRUD de Torneos y **creación de Grupos**.
   - CRUD de Delegaciones, Equipos y **registro de Jugadores**.
4. **Fase 4: Fixture, Partidos y Sanciones Disciplinarias**
   - Generación y programación del fixture por grupos y fases.
   - Registro de actas de partidos, sets y marcadores.
   - **Módulo de Sanciones:** Registro de tarjetas/castigos y funcionalidad para **quitar/levantar sanciones**.
5. **Fase 5: Tablas de Posiciones, Medallero y Vistas Públicas**
   - Cálculo dinámico de posiciones por deporte y grupo.
   - Vista pública del Medallero Olímpico y calendario de encuentros.