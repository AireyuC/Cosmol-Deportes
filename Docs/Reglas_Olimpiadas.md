# Especificación Técnica y Plan de Implementación de Reglas Oficiales: COSMOL 2026 (ANESAPA)

> **Documento:** Especificación Funcional, Modelo de Datos y Plan de Desarrollo del Sistema  
> **Sistema:** Cosmol-Deportes (Plataforma Oficial de Gestión de Olimpiadas Inter-Cooperativas)  
> **Ubicación del Archivo:** `Docs/Reglas_Olimpiadas.md`  
> **Compatibilidad Tecnológica:** PHP 7.3 nativo &bull; MariaDB 10.4 / MySQL 5.7 &bull; AdminLTE 3 (Bootstrap 4) &bull; JavaScript nativo  
> **Estado:** Documentación Técnica de Requerimientos e Implementación (Pendiente de Auditoría)  

---

## 1. Contexto Institucional y Marco Reglamentario

La Cooperativa de Servicios Públicos Montero R.L. (**COSMOL R.L.**), en su calidad de entidad anfitriona y organizadora, junto con la Asociación Nacional de Empresas e Instituciones de Servicios de Agua Potable y Alcantarillado (**ANESAPA**), celebran las **XXXIII Jornadas Deportivas Nacionales ANESAPA (COSMOL 2026)** en la ciudad de Montero, Santa Cruz, Bolivia.

En este certamen compiten delegaciones de Empresas Públicas Sociales de Agua y Saneamiento (EPSA), Cooperativas de Servicios Públicos e instituciones afines de los nueve departamentos del país (ej. **COSMOL R.L.**, **SAGUAPAC R.L.**, **SEMAPA**, **EMAPYC**, **EPSAS**, entre otras).

### 1.1. Rol del Sistema Cosmol-Deportes
La plataforma web **Cosmol-Deportes** es el sistema central encargado de:
1. Administrar el calendario maestro de encuentros (fixture) y escenarios deportivos.
2. Controlar la elegibilidad, edad, género y requisitos laborales de los deportistas en cancha.
3. Digitalizar y auditar las actas oficiales de partido mediante el respaldo fotográfico de la planilla física firmada.
4. Administrar el régimen disciplinario y la facultad de indulto olímpico del Comité Organizador.
5. Computar en tiempo real el **Medallero Olímpico Ponderado** y proclamar a la **Cooperativa Campeona General de las Olimpiadas**.
6. Brindar a las delegaciones visitantes y público acceso libre e inmediato a la información deportiva y a la navegación GPS ("Cómo llegar") hacia cada sede deportiva en Montero.

```
       ┌────────────────────────────────────────────────────────┐
       │   XXXIII JORNADAS DEPORTIVAS NACIONALES ANESAPA 2026   │
       │              COSMOL R.L. - Montero, Santa Cruz         │
       └──────────────────────────┬─────────────────────────────┘
                                  │
         ┌────────────────────────┴─────────────────────────┐
         ▼                                                  ▼
┌───────────────────────────────┐          ┌────────────────────────────────┐
│   PORTAL PÚBLICO INFORMATIVO  │          │   PANEL ADMINISTRATIVO         │
│   (Sin login - Libre acceso)  │          │   (AdminLTE 3 - Rol ADMIN)     │
├───────────────────────────────┤          ├────────────────────────────────┤
│ • Medallero y Puntaje General │          │ • Creación de Fixtures y Sedes │
│ • Fixture por disciplina      │          │ • Validación de Actas Oficiales│
│ • Tablas de posiciones        │          │ • Carga y Zoom Foto Planilla   │
│ • Rutas GPS ("Cómo llegar")   │          │ • Régimen e Indultos Olímpicos │
│ • Modal de Planilla Firmada   │          │ • Proclamación de Podios       │
└───────────────────────────────┘          └────────────────────────────────┘
```

---

## 2. Diagnóstico de Requerimientos y Acciones de Implementación (Matriz de Brecha)

| Exigencia Reglamentaria (Convocatoria ANESAPA) | Estado Actual en el Código | Qué se debe hacer en el Sistema para implementarlo |
|---|---|---|
| **Catálogo de 11 Disciplinas**<br>Fútbol 11, Futsal, Vóley mixto, Básquet mixto, Frontón, 5K, Pruebas Técnicas, Cacho, Billar, Karaoke, Juegos Tradicionales. | El sistema contaba inicialmente con 7 disciplinas. Se crearon las 4 restantes en BD (`disciplinas` y `reglas_puntuacion`). | Consolidar en `seeds.sql` la parametrización de las 11 disciplinas con sus reglas de puntuación específicas (sets, empates, victorias). |
| **Básquetbol en Rama Mixto**<br>La convocatoria estipula explícitamente modalidad mixta con damas y veteranos. | Anteriormente figuraba como Masculino. | Parametrizar en `torneos` con rama `Mixto` y aplicar las reglas de conformación en cancha. |
| **Control de Edad en Cancha (Art. 4)**<br>Fútbol 11: $\ge 3$ jugadores de $\ge 45$ años.<br>Futsal: $\ge 1$ jugador de $\ge 45$ años.<br>Vóley y Básquet: $\ge 1$ jugador de $\ge 50$ años. | La tabla `jugadores` almacena `fecha_nacimiento`, pero no existía lógica de validación ni advertencia en la confección de alineaciones o actas. | Crear el helper de elegibilidad (`ElegibilidadHelper.php`), calcular la edad exacta a la fecha del partido y mostrar badges/alertas en las vistas de alineación y acta. |
| **Cuota de Género Femenino en Cancha (Art. 4)**<br>Voleibol Mixto: mínimo 2 damas obligatorias en cancha.<br>Básquetbol Mixto: mínimo 1 dama obligatoria en cancha. | La tabla `jugadores` carecía de columna de género (`genero`/`sexo`). | Ejecutar migración SQL para agregar la columna `genero` (ENUM `'M','F'`) en la tabla `jugadores` e incorporar la validación de cupos en el acta. |
| **Elegibilidad Laboral (Art. 3 y 6)**<br>Nómina con número de ítem oficial, antigüedad $\ge 3$ meses y distinción entre personal operativo, administrativo y directivo. | `jugadores` no contaba con los campos de ítem, cargo/perfil ni fecha de ingreso a la empresa. | Agregar columnas `item`, `tipo_personal` y `fecha_ingreso` en la tabla `jugadores` y actualizar el formulario de registro y edición. |
| **Medallero Ponderado Oficial (Art. 15)**<br>Puntos diferenciados por disciplina (Fútbol 45/30/15, Futsal 40/30/15, Cancha 30/20/10, Técnicas 20/15/10, Salón 15/10/5, Tradicionales 5 c/u). | El modelo `Medallero.php` tenía un cálculo estático tipo `CASE` de 10/7/5 puntos fijos. | Modificar `Medallero.php` para totalizar `SUM(m.puntos_olimpicos)` y automatizar la asignación del puntaje oficial según la disciplina al registrar podios. |
| **Respaldo de Planilla Oficial (`foto_planilla`)**<br>Documento supremo firmado por árbitros y capitanes para dirimir reclamos e impugnaciones (Art. 11 y 12). | Columna `foto_planilla` creada en `partidos` y lógica de subida en `PartidoController`. | Implementar la previsualización modal con zoom tanto en el formulario de acta administrativa como en el fixture del portal público. |
| **Régimen Disciplinario e Indulto (Art. 5)**<br>Inhabilitación automática de sancionados y facultad de conmutar/levantar sanciones por confraternización deportiva. | La tabla `sanciones` admite estados `'Activa'`, `'Cumplida'`, `'Levantada'`. | Validar en tiempo real que los deportistas suspendidos aparezcan bloqueados para alineación y proveer el flujo de levantamiento con auditoría inmutable. |

---

## 3. Implementación de las 11 Disciplinas Oficiales y Formatos de Juego

Para dar estricto cumplimiento al reglamento oficial, el sistema debe configurar y operar cada una de las **11 disciplinas** bajo los siguientes parámetros de juego y puntuación:

```
┌────────────────────────────────────────────────────────────────────────────────────────┐
│                              LAS 11 DISCIPLINAS OFICIALES                              │
├─────────────────────────┬───────────────────────┬──────────────────────────────────────┤
│ 1. Deportes de Cancha   │ 2. Raqueta y Fondo    │ 3. Salón, Técnicas y Tradicionales   │
├─────────────────────────┼───────────────────────┼──────────────────────────────────────┤
│ • Fútbol 11 (Masc)      │ • Raqueta Frontón     │ • Comp. Técnicas (Operarios agua)    │
│ • Futsal 5 (Masc)       │ • Media Maratón (5K)  │ • Cacho Alalay (Parejas directivos)  │
│ • Voleibol Mixto        │                       │ • Billar (Parejas directivos)        │
│ • Básquetbol Mixto      │                       │ • Karaoke (Noche talentos)           │
│                         │                       │ • Juegos Populares (3 pruebas x 5 pt)│
└─────────────────────────┴───────────────────────┴──────────────────────────────────────┘
```

### 3.1. Especificación Detallada por Disciplina

#### 1. Fútbol Once (11)
- **Rama:** Masculino.
- **Escenario:** Cancha reglamentaria de césped natural.
- **Reglamento deportivo:** 2 tiempos de 45 minutos. Se aplican las reglas FIFA / FBF.
- **Puntuación en tabla de posiciones:** Victoria = 3 pts, Empate = 1 pt, Derrota = 0 pts. Permite empate (`permite_empate = 1`, `formato_sets = 0`).
- **Exigencia en Cancha (Art. 4):** **Mínimo 3 jugadores en cancha de 45 años cumplidos o más durante todo el encuentro.**
- **Puntaje al Medallero (Art. 15):** Oro = 45 pts, Plata = 30 pts, Bronce = 15 pts.

#### 2. Fútbol de Salón (Futsal 5)
- **Rama:** Masculino.
- **Escenario:** Coliseo techado (parquet o cemento pulido).
- **Reglamento deportivo:** 2 tiempos de 20 minutos cronometrados. Se aplican reglas AMF / FIFUSA.
- **Puntuación en tabla de posiciones:** Victoria = 3 pts, Empate = 1 pt, Derrota = 0 pts (`permite_empate = 1`, `formato_sets = 0`).
- **Exigencia en Cancha (Art. 4):** **Mínimo 1 jugador en cancha de 45 años cumplidos o más.**
- **Puntaje al Medallero (Art. 15):** Oro = 40 pts, Plata = 30 pts, Bronce = 15 pts.

#### 3. Voleibol Mixto
- **Rama:** Mixto.
- **Escenario:** Coliseo techado.
- **Reglamento deportivo:** Sets a 25 puntos con ventaja de 2 (al mejor de 3 o 5 sets). `formato_sets = 1`, `sets_para_ganar = 2` o `3`.
- **Puntuación en tabla de posiciones:**
  - Victoria 3-0 o 3-1: 3 pts para el ganador, 0 pts para el perdedor.
  - Victoria 3-2: 2 pts para el ganador, 1 pt para el perdedor (`pts_derrota_ajustada = 1`).
  - Sin empates (`permite_empate = 0`).
- **Exigencias en Cancha (Art. 4):**
  - **Mínimo 1 jugador(a) en cancha de 50 años cumplidos o más.**
  - **Obligatoriamente mínimo 2 damas permanentemente en cancha.**
- **Puntaje al Medallero (Art. 15):** Oro = 30 pts, Plata = 20 pts, Bronce = 10 pts.

#### 4. Básquetbol Mixto
- **Rama:** Mixto.
- **Escenario:** Coliseo techado.
- **Reglamento deportivo:** 4 cuartos de 10 minutos. Canastas acumuladas. `formato_sets = 0`.
- **Puntuación en tabla de posiciones:** Victoria = 2 pts, Derrota con presentación = 1 pt, Derrota por no presentación (W.O.) = 0 pts (`permite_empate = 0`).
- **Exigencias en Cancha (Art. 4):**
  - **Mínimo 1 jugador(a) en cancha de 50 años cumplidos o más.**
  - **Obligatoriamente mínimo 1 dama permanentemente en cancha.**
- **Puntaje al Medallero (Art. 15):** Oro = 30 pts, Plata = 20 pts, Bronce = 10 pts.

#### 5. Raqueta Frontón
- **Rama:** Parejas (2 jugadores por equipo).
- **Escenario:** Frontón reglamentario de tres paredes o frontón abierto.
- **Reglamento deportivo:** Sets a 15 puntos (al mejor de 3 sets). `formato_sets = 1`, `sets_para_ganar = 2`.
- **Puntuación en tabla:** Victoria = 2 pts, Derrota = 0 pts (`permite_empate = 0`).
- **Puntaje al Medallero (Art. 15):** Oro = 30 pts, Plata = 20 pts, Bronce = 10 pts.

#### 6. Media Maratón (5K Libre)
- **Rama:** Libre (hasta 2 representantes masculinos/femeninos por EPSA).
- **Escenario:** Circuito urbano trazado en la ciudad de Montero.
- **Modalidad en el sistema:** Carrera pedestre única. El orden de llegada cronometrado adjudica directamente los puestos de podio.
- **Puntaje al Medallero (Art. 15):** Oro = 30 pts, Plata = 20 pts, Bronce = 10 pts.

#### 7. Competencias Técnicas Deportivas
- **Rama:** Trabajadores Operativos de EPSA.
- **Escenario:** Polígono técnico / taller exterior habilitado por COSMOL R.L.
- **Prueba reglamentaria:** Ejecución práctica de **una acometida/conexión nueva de agua potable desde la red matriz hasta la llave de paso domiciliaria**. Es calificada por un jurado técnico evaluando: tiempo de ejecución, cumplimiento de normas de fontanería y prueba hidrostática de estanqueidad (cero fugas).
- **Exigencia en Cancha (Art. 4):** **Obligatorio 3 trabajadores operativos acreditados por delegación.**
- **Puntaje al Medallero (Art. 15):** Oro = 20 pts, Plata = 15 pts, Bronce = 10 pts.

#### 8. Cacho Alalay
- **Rama:** Parejas (Directivos / Ejecutivos).
- **Escenario:** Salón social de confraternización COSMOL.
- **Modalidad en el sistema:** Planilla de anotación oficial Alalay / Generala (chances, escaleras, fulls, pókers, senas, grandes). Carga directa de puntaje de planilla.
- **Exigencia en Cancha (Art. 4):** **Presencia de al menos 1 directivo o ejecutivo acreditado en la pareja.**
- **Puntaje al Medallero (Art. 15):** Oro = 15 pts, Plata = 10 pts, Bronce = 5 pts.

#### 9. Billar
- **Rama:** Parejas (Directivos / Ejecutivos, hasta 2 parejas por institución).
- **Escenario:** Salón de billar.
- **Modalidad en el sistema:** Marcador por chicos ganados. Carga directa de resultado de mesa.
- **Exigencia en Cancha (Art. 4):** **Presencia de al menos 1 ejecutivo o director acreditado.**
- **Puntaje al Medallero (Art. 15):** Oro = 15 pts, Plata = 10 pts, Bronce = 5 pts.

#### 10. Karaoke (Noche de Talentos)
- **Rama:** Libre / Confraternización ejecutiva (hasta 2 representantes por EPSA).
- **Escenario:** Salón de actos cultural.
- **Modalidad en el sistema:** Evaluación por mesa de jurados (afinación, interpretación y desenvolvimiento escénico). Carga de puntaje acumulado.
- **Puntaje al Medallero (Art. 15):** Oro = 15 pts, Plata = 10 pts, Bronce = 5 pts.

#### 11. Juegos Populares Tradicionales
- **Pruebas independientes:**
  1. Carrera de embolsao
  2. Carrera de cuchara y huevo
  3. Carrera de carretilla
- **Modalidad en el sistema:** Pruebas de confraternización rápida. Cada prueba otorga **5 puntos directos** a la delegación ganadora, los cuales se suman al puntaje olímpico acumulado en la tabla general.

---

## 4. Requerimientos de Base de Datos y Script de Migración SQL

Para dar soporte a los atributos de edad, género, cargo laboral y número de ítem de los deportistas, se debe aplicar el siguiente script de migración estructurado en MariaDB/MySQL:

```sql
-- ============================================================================
-- MIGRACIÓN: Soporte de Reglas Convocatoria ANESAPA 2026
-- Motor: MariaDB 10.4 / MySQL 5.7 (InnoDB, UTF-8)
-- Archivo: database/migrations/2026_10_add_anesapa_rules.sql
-- ============================================================================

-- 1. Ampliación de campos de elegibilidad en la tabla `jugadores`
ALTER TABLE `jugadores`
ADD COLUMN `genero` ENUM('M', 'F') NOT NULL DEFAULT 'M' AFTER `apellidos`,
ADD COLUMN `item` VARCHAR(50) NULL AFTER `documento`,
ADD COLUMN `tipo_personal` ENUM('Operativo', 'Ejecutivo', 'Administrativo') NOT NULL DEFAULT 'Administrativo' AFTER `posicion`,
ADD COLUMN `fecha_ingreso` DATE NULL AFTER `fecha_nacimiento`;

-- 2. Asegurar la presencia de las 11 disciplinas en `disciplinas`
INSERT IGNORE INTO `disciplinas` (`id`, `nombre`, `descripcion`, `icono`, `estado`) VALUES
(1, 'Fútbol', 'Fútbol once reglamentario en cancha de césped', 'futbol', 'Activa'),
(2, 'Futsal', 'Fútbol de salón en coliseo cerrado', 'futbol', 'Activa'),
(3, 'Básquetbol', 'Básquetbol mixto en coliseo cerrado', 'basketball-ball', 'Activa'),
(4, 'Voleibol', 'Voleibol mixto por sets en coliseo cerrado', 'volleyball-ball', 'Activa'),
(5, 'Cacho', 'Juego de mesa de dados en modalidad Alalay por parejas', 'dice', 'Activa'),
(6, 'Billar', 'Juego de salón de billar por parejas', 'dot-circle', 'Activa'),
(7, 'Karaoke', 'Noche de talentos con jurado calificador', 'microphone-alt', 'Activa'),
(8, 'Raqueta Frontón', 'Torneo de frontón en modalidad parejas a 15 puntos por set', 'table-tennis', 'Activa'),
(9, 'Media Maratón', 'Carrera pedestre libre de 5 kilómetros circuito urbano Montero', 'running', 'Activa'),
(10, 'Competencias Técnicas Deportivas', 'Prueba técnica de acometida matriz a llave de paso para operarios', 'tools', 'Activa'),
(11, 'Juegos Tradicionales', 'Juegos populares: carrera de embolsao, cuchara y huevo, carretilla', 'smile', 'Activa');

-- 3. Configuración de parámetros de puntuación en `reglas_puntuacion`
INSERT IGNORE INTO `reglas_puntuacion` (`disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`) VALUES
(1, 3, 1, 0, 0, 1, 0, 0), -- Fútbol: 3 vic, 1 emp, 0 der
(2, 3, 1, 0, 0, 1, 0, 0), -- Futsal: 3 vic, 1 emp, 0 der
(3, 2, 0, 1, 0, 0, 0, 0), -- Básquetbol: 2 vic, 1 der
(4, 3, 0, 0, 1, 0, 1, 2), -- Voleibol: sets a 25 pts, gana el primero a 2 o 3 sets
(5, 1, 0, 0, 0, 0, 0, 0), -- Cacho: marcador directo
(6, 1, 0, 0, 0, 0, 0, 0), -- Billar: marcador directo
(7, 1, 0, 0, 0, 0, 0, 0), -- Karaoke: marcador directo
(8, 2, 0, 0, 0, 0, 1, 2), -- Raqueta Frontón: sets a 15 pts, gana con 2 sets
(9, 30, 0, 0, 0, 0, 0, 0), -- Media maratón: cómputo directo por cronometraje
(10, 20, 0, 0, 0, 0, 0, 0), -- Competencias técnicas: calificación jurado
(11, 5, 0, 0, 0, 0, 0, 0);  -- Juegos tradicionales: 5 puntos fijos por prueba
```

---

## 5. Implementación de la Lógica de Negocio en Backend (PHP 7.3)

Para garantizar la máxima compatibilidad con el entorno de ejecución XAMPP (PHP 7.3), se debe implementar la lógica de validación respetando las siguientes directrices:
- Prohibido el uso de propiedades tipadas en clases (`public int $x`).
- Prohibido el uso de funciones flecha (`fn()`).
- Prohibido el operador nullsafe (`?->`).
- Prohibidas las expresiones `match`.

### 5.1. Helper de Validación de Elegibilidad: `app/helpers/ElegibilidadHelper.php`

Este helper encapsula el cálculo de edad y la verificación de las cuotas obligatorias en cancha estipuladas en el Artículo 4 de la Convocatoria:

```php
<?php
/**
 * Helper de Validación de Elegibilidad y Reglas en Cancha (ANESAPA 2026)
 * Ubicación: app/helpers/ElegibilidadHelper.php
 * Compatible con PHP 7.3
 */

class ElegibilidadHelper {

    /**
     * Calcula la edad exacta de un deportista respecto a la fecha del partido
     *
     * @param string|null $fechaNacimiento Formato YYYY-MM-DD
     * @param string|null $fechaPartido Formato YYYY-MM-DD
     * @return int
     */
    public static function calcularEdad($fechaNacimiento, $fechaPartido = null) {
        if (empty($fechaNacimiento)) {
            return 0;
        }

        $nacimiento = date_create($fechaNacimiento);
        $referencia = $fechaPartido ? date_create($fechaPartido) : date_create('now');

        if (!$nacimiento || !$referencia) {
            return 0;
        }

        $diff = date_diff($nacimiento, $referencia);
        return (int)$diff->y;
    }

    /**
     * Verifica la antigüedad laboral mínima de 3 meses (90 días) exigida en el Art. 6
     *
     * @param string|null $fechaIngreso Formato YYYY-MM-DD
     * @param string|null $fechaReferencia Formato YYYY-MM-DD
     * @return bool
     */
    public static function cumpleAntiguedad($fechaIngreso, $fechaReferencia = null) {
        if (empty($fechaIngreso)) {
            return true; // No bloquea si no está cargada en el registro preliminar
        }

        $ingreso = date_create($fechaIngreso);
        $referencia = $fechaReferencia ? date_create($fechaReferencia) : date_create('now');

        if (!$ingreso || !$referencia) {
            return true;
        }

        $diff = date_diff($ingreso, $referencia);
        $dias = (int)$diff->format('%r%a');
        return $dias >= 90;
    }

    /**
     * Evalúa si una alineación de jugadores titulares en cancha cumple con el Art. 4
     *
     * @param string $disciplinaNombre Nombre de la disciplina
     * @param array $jugadoresEnCancha Lista de jugadores con keys: ['fecha_nacimiento', 'genero', 'tipo_personal']
     * @param string|null $fechaPartido
     * @return array ['valido' => bool, 'errores' => array, 'alertas' => array]
     */
    public static function validarAlineacionEnCancha($disciplinaNombre, $jugadoresEnCancha, $fechaPartido = null) {
        $resultado = [
            'valido'  => true,
            'errores' => [],
            'alertas' => []
        ];

        $disciplinaNorm = mb_strtolower(trim($disciplinaNombre), 'UTF-8');

        $mayores45 = 0;
        $mayores50 = 0;
        $damas = 0;
        $varones = 0;
        $operativos = 0;
        $ejecutivos = 0;

        foreach ($jugadoresEnCancha as $j) {
            $edad = self::calcularEdad(isset($j['fecha_nacimiento']) ? $j['fecha_nacimiento'] : null, $fechaPartido);
            $genero = isset($j['genero']) ? strtoupper($j['genero']) : 'M';
            $tipoPersonal = isset($j['tipo_personal']) ? $j['tipo_personal'] : 'Administrativo';

            if ($edad >= 45) {
                $mayores45++;
            }
            if ($edad >= 50) {
                $mayores50++;
            }
            if ($genero === 'F') {
                $damas++;
            } else {
                $varones++;
            }
            if ($tipoPersonal === 'Operativo') {
                $operativos++;
            }
            if ($tipoPersonal === 'Ejecutivo') {
                $ejecutivos++;
            }
        }

        // 1. Regla Fútbol 11: Mínimo 3 jugadores de 45 años cumplidos o más
        if (strpos($disciplinaNorm, 'fútbol once') !== false || $disciplinaNorm === 'fútbol') {
            if ($mayores45 < 3) {
                $resultado['valido'] = false;
                $resultado['errores'][] = "Fútbol 11: Se exigen mínimo 3 jugadores de 45 años cumplidos o más en cancha (detectados: {$mayores45}).";
            }
        }

        // 2. Regla Futsal 5: Mínimo 1 jugador de 45 años o más
        if (strpos($disciplinaNorm, 'futsal') !== false || strpos($disciplinaNorm, 'salón') !== false) {
            if ($mayores45 < 1) {
                $resultado['valido'] = false;
                $resultado['errores'][] = "Futsal: Se exige mínimo 1 jugador de 45 años cumplidos o más en cancha (detectados: {$mayores45}).";
            }
        }

        // 3. Regla Voleibol Mixto: Mínimo 1 jugador(a) >= 50 años y 2 damas obligatorias
        if (strpos($disciplinaNorm, 'voleibol') !== false || strpos($disciplinaNorm, 'vóley') !== false) {
            if ($mayores50 < 1) {
                $resultado['valido'] = false;
                $resultado['errores'][] = "Voleibol Mixto: Se exige mínimo 1 jugador(a) de 50 años cumplidos o más en cancha (detectados: {$mayores50}).";
            }
            if ($damas < 2) {
                $resultado['valido'] = false;
                $resultado['errores'][] = "Voleibol Mixto: Es obligatorio tener mínimo 2 damas permanentemente en cancha (detectadas: {$damas}).";
            }
        }

        // 4. Regla Básquetbol Mixto: Mínimo 1 jugador(a) >= 50 años y 1 dama obligatoria
        if (strpos($disciplinaNorm, 'básquetbol') !== false || strpos($disciplinaNorm, 'baloncesto') !== false) {
            if ($mayores50 < 1) {
                $resultado['valido'] = false;
                $resultado['errores'][] = "Básquetbol Mixto: Se exige mínimo 1 jugador(a) de 50 años cumplidos o más en cancha (detectados: {$mayores50}).";
            }
            if ($damas < 1) {
                $resultado['valido'] = false;
                $resultado['errores'][] = "Básquetbol Mixto: Es obligatoria mínimo 1 dama en cancha (detectadas: {$damas}).";
            }
        }

        // 5. Competencias Técnicas: 3 trabajadores operativos
        if (strpos($disciplinaNorm, 'técnica') !== false) {
            if ($operativos < 3) {
                $resultado['alertas'][] = "Competencias Técnicas: Se exige la participación de 3 trabajadores operativos acreditados.";
            }
        }

        // 6. Disciplinas de Salón: Al menos 1 ejecutivo o director
        if (strpos($disciplinaNorm, 'cacho') !== false || strpos($disciplinaNorm, 'billar') !== false || strpos($disciplinaNorm, 'karaoke') !== false) {
            if ($ejecutivos < 1) {
                $resultado['alertas'][] = "Disciplinas de Salón: Se recomienda la participación de al menos 1 directivo o ejecutivo acreditado.";
            }
        }

        return $resultado;
    }
}
```

---

## 6. Implementación del Medallero Olímpico Ponderado (Art. 15 Convocatoria)

El cálculo del campeonato general no se basa en una simple suma de medallas, sino en la **acumulación de Puntos Olímpicos Ponderados** según la importancia y esfuerzo logístico de cada disciplina deportiva.

### 6.1. Escala Oficial de Puntuación al Medallero

```
┌───────────────────────────────────────┬──────────────┬──────────────┬──────────────┐
│ DISCIPLINA / COMPETENCIA              │ ORO (1° LUG) │ PLATA (2° L) │ BRONCE (3° L)│
├───────────────────────────────────────┼──────────────┼──────────────┼──────────────┤
│ Fútbol once (11)                      │    45 pts    │    30 pts    │    15 pts    │
│ Fútbol de salón (5)                   │    40 pts    │    30 pts    │    15 pts    │
│ Voleibol mixto                        │    30 pts    │    20 pts    │    10 pts    │
│ Básquetbol mixto                      │    30 pts    │    20 pts    │    10 pts    │
│ Raqueta frontón (parejas)             │    30 pts    │    20 pts    │    10 pts    │
│ Media maratón (5 km libre)            │    30 pts    │    20 pts    │    10 pts    │
│ Competencias técnicas deportivas      │    20 pts    │    15 pts    │    10 pts    │
│ Cacho (parejas - ejecutivos)          │    15 pts    │    10 pts    │     5 pts    │
│ Billar (parejas - ejecutivos)         │    15 pts    │    10 pts    │     5 pts    │
│ Karaoke (noche de talentos)           │    15 pts    │    10 pts    │     5 pts    │
│ Juegos tradicionales (embolsao)       │     5 pts    │      -       │      -       │
│ Juegos tradicionales (cuchara/huevo)  │     5 pts    │      -       │      -       │
│ Juegos tradicionales (carretilla)     │     5 pts    │      -       │      -       │
└───────────────────────────────────────┴──────────────┴──────────────┴──────────────┘
```

### 6.2. Automatización del Puntaje en `Medallero.php`

En `app/models/Medallero.php`, se debe implementar la función que mapea el nombre de la disciplina con el tipo de medalla para calcular los puntos oficiales:

```php
/**
 * Retorna los puntos olímpicos oficiales que otorga una disciplina según el puesto
 *
 * @param string $disciplinaNombre
 * @param string $tipoMedalla ORO, PLATA, BRONCE
 * @return int
 */
public static function getPuntosOficiales($disciplinaNombre, $tipoMedalla) {
    $medalla = strtoupper(trim($tipoMedalla));
    $disc = mb_strtolower(trim($disciplinaNombre), 'UTF-8');

    // 1. Fútbol 11
    if (strpos($disc, 'fútbol once') !== false || $disc === 'fútbol') {
        switch ($medalla) {
            case 'ORO':    return 45;
            case 'PLATA':  return 30;
            case 'BRONCE': return 15;
            default: return 0;
        }
    }

    // 2. Futsal 5
    if (strpos($disc, 'futsal') !== false || strpos($disc, 'salón') !== false) {
        switch ($medalla) {
            case 'ORO':    return 40;
            case 'PLATA':  return 30;
            case 'BRONCE': return 15;
            default: return 0;
        }
    }

    // 3. Voleibol, Básquetbol, Frontón, Media Maratón
    if (strpos($disc, 'voleibol') !== false || strpos($disc, 'básquetbol') !== false || 
        strpos($disc, 'frontón') !== false  || strpos($disc, 'maratón') !== false) {
        switch ($medalla) {
            case 'ORO':    return 30;
            case 'PLATA':  return 20;
            case 'BRONCE': return 10;
            default: return 0;
        }
    }

    // 4. Competencias Técnicas
    if (strpos($disc, 'técnica') !== false) {
        switch ($medalla) {
            case 'ORO':    return 20;
            case 'PLATA':  return 15;
            case 'BRONCE': return 10;
            default: return 0;
        }
    }

    // 5. Cacho, Billar, Karaoke
    if (strpos($disc, 'cacho') !== false || strpos($disc, 'billar') !== false || strpos($disc, 'karaoke') !== false) {
        switch ($medalla) {
            case 'ORO':    return 15;
            case 'PLATA':  return 10;
            case 'BRONCE': return 5;
            default: return 0;
        }
    }

    // 6. Juegos Tradicionales (5 puntos por cada prueba ganada)
    if (strpos($disc, 'tradicional') !== false || strpos($disc, 'popular') !== false) {
        return ($medalla === 'ORO') ? 5 : 0;
    }

    // Valor estándar por defecto
    switch ($medalla) {
        case 'ORO':    return 10;
        case 'PLATA':  return 7;
        case 'BRONCE': return 5;
        default: return 0;
    }
}
```

### 6.3. Criterio de Ordenamiento de la Tabla Acumulada
En `Medallero::getResumenPorOlimpiada()`, la consulta SQL totaliza `SUM(m.puntos_olimpicos)` y ordena a las delegaciones bajo el estándar olímpico oficial de ANESAPA:

1. **Mayor cantidad de Puntos Olímpicos Acumulados** (`puntos_olimpicos DESC`)
2. **Mayor cantidad de Medallas de Oro** (`oro DESC`)
3. **Mayor cantidad de Medallas de Plata** (`plata DESC`)
4. **Mayor cantidad de Medallas de Bronce** (`bronce DESC`)
5. **Orden alfabético institucional** (`d.nombre ASC`)

---

## 7. Implementación de Actas Digitales y Foto de Planilla Oficial (`foto_planilla`)

En los congresillos y asambleas de delegados (Art. 8 al 12), las resoluciones arbitrales no admiten modificaciones salvo respaldo demostrable con la planilla física oficial firmada por la terna arbitral y los capitanes.

```
 ┌──────────────────────┐      ┌──────────────────────┐      ┌──────────────────────┐
 │ 1. Mesa de Control   │      │ 2. Cierre y Firmas   │      │ 3. Carga Digital     │
 │ Registro de goles,   │ ───► │ Juez, árbitro y      │ ───► │ Foto nítida en       │
 │ sets, faltas y       │      │ capitanes firman el  │      │ sistema:             │
 │ amonestaciones.      │      │ acta física oficial. │      │ partidos.foto_planilla│
 └──────────────────────┘      └──────────────────────┘      └──────────┬───────────┘
                                                                        │
                               ┌────────────────────────────────────────┘
                               ▼
 ┌──────────────────────────────────────────────────────────────────────────────────┐
 │ 4. Verificación y Auditoría Pública                                              │
 │ • Estado cambia a 'Finalizado'. Se actualizan posiciones y medallero.           │
 │ • Modal con visor de alta definición disponible en Admin y Portal Público.       │
 └──────────────────────────────────────────────────────────────────────────────────┘
```

### 7.1. Acciones a Realizar en el Sistema
1. **Validación de Carga de Archivos (`PartidoController::actualizarActa`):**
   - Extensiones permitidas: `JPG`, `JPEG`, `PNG`, `WEBP`.
   - Tamaño máximo: 5 MB.
   - Nomenclatura del archivo en servidor: `planilla_p{id}_{timestamp}_{hash}.{ext}` dentro de `public/uploads/planillas/`.
2. **Previsualización en el Panel de Administración:**
   - Dentro de `/partidos/{id}/acta`, un contenedor visual muestra la miniatura de la planilla actual con botón para ampliarla en un modal con zoom.
3. **Acceso Abierto en el Portal Público:**
   - En `/portal/fixture`, cada partido finalizado con planilla adjunta despliega un icono destacado de cámara/documento que abre el modal de consulta, garantizando transparencia absoluta ante todas las cooperativas.

---

## 8. Implementación del Régimen Disciplinario e Indultos Olímpicos

De acuerdo con el Artículo 5 de la Convocatoria, los deportistas sancionados no pueden participar en los encuentros subsiguientes. No obstante, para fomentar la confraternización deportiva, el Comité Organizador tiene la facultad de **levantar o conmutar sanciones**.

### 8.1. Qué debe hacer el Sistema
1. **Control de Inhabilitación Automática:**
   - El modelo `Jugador.php` evalúa la existencia de registros en `sanciones` con `estado = 'Activa'`.
   - En caso afirmativo, el jugador aparece con el badge `Suspendido` y el sistema impide marcarlo como titular o suplente en el acta de juego.
2. **Procedimiento de Levantamiento con Auditoría Inmutable:**
   - Módulo en `admin/sanciones/index.php` con botón *"Levantar Sanción"*.
   - Requiere ingresar obligatoriamente el **Motivo Formal del Levantamiento** (ej. "Error arbitral rectificado en acta", "Indulto de confraternización por pase a semifinales").
   - El controlador `SancionController::levantar()` ejecuta:
     ```sql
     UPDATE sanciones 
     SET estado = 'Levantada', 
         motivo_levantamiento = :motivo, 
         levantado_por_usuario_id = :usuario_id, 
         fecha_levantamiento = NOW() 
     WHERE id = :id;
     ```
   - El atleta vuelve de inmediato a estado `Habilitado` y queda constancia histórica de quién autorizó el indulto y en qué fecha.

---

## 9. Geolocalización GPS de Sedes y Canchas en Montero ("Cómo llegar")

Las delegaciones deportivas de otros departamentos y sus familias requieren orientación precisa para llegar a los distintos escenarios deportivos en Montero (Sede Central COSMOL, Coliseo Plifach, Cancha Gilberto Parada, Complejos de Frontón, etc.).

### 9.1. Qué debe hacer el Sistema
1. **Persistencia de Coordenadas:**
   - Asegurar que la tabla `sedes` contenga los valores de `latitud` y `longitud` en formato `DECIMAL(10,8)`.
2. **Integración JavaScript de Ruta Óptima (`app/views/public/sedes.php`):**
   ```javascript
   function comoLlegar(latDestino, lngDestino) {
       if (navigator.geolocation) {
           navigator.geolocation.getCurrentPosition(function(position) {
               var latOrigen = position.coords.latitude;
               var lngOrigen = position.coords.longitude;
               var url = 'https://www.google.com/maps/dir/?api=1&origin=' + latOrigen + ',' + lngOrigen + '&destination=' + latDestino + ',' + lngDestino + '&travelmode=driving';
               window.open(url, '_blank');
           }, function(error) {
               // Si el navegador deniega el GPS, abre la ubicación del destino
               var urlFallback = 'https://www.google.com/maps/search/?api=1&query=' + latDestino + ',' + lngDestino;
               window.open(urlFallback, '_blank');
           });
       } else {
           window.open('https://www.google.com/maps/search/?api=1&query=' + latDestino + ',' + lngDestino, '_blank');
       }
   }
   ```
3. **Validación Anti-Colisión:**
   - En `PartidoController::create()`, validar que una misma cancha no tenga asignados dos partidos con margen menor a 90 minutos (fútbol) o 60 minutos (otras disciplinas), ni que una delegación deba jugar dos disciplinas simultáneas.

---

## 10. Plan de Trabajo Técnico y Tareas Específicas de Desarrollo

Para llevar a producción estas exigencias de manera ordenada, el plan de trabajo se organiza en las siguientes fases técnicas:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    PLAN DE TRABAJO TÉCNICO DE IMPLEMENTACIÓN                │
├─────────┬───────────────────────────────────────────────────────────────────┤
│ Fase 1  │ Base de Datos: Ejecutar migración de campos en `jugadores`        │
│         │ (`genero`, `item`, `tipo_personal`, `fecha_ingreso`) y seeds.     │
├─────────┼───────────────────────────────────────────────────────────────────┤
│ Fase 2  │ Backend: Crear `app/helpers/ElegibilidadHelper.php` con PHP 7.3  │
│         │ para edad $\ge 45$/$50$, cupos de damas y operarios.              │
├─────────┼───────────────────────────────────────────────────────────────────┤
│ Fase 3  │ Modelos y Controladores: Actualizar `Jugador.php` (CRUD) y        │
│         │ `Medallero.php` (método `getPuntosOficiales`).                   │
├─────────┼───────────────────────────────────────────────────────────────────┤
│ Fase 4  │ Actas de Juego: Integrar subida y modal con zoom de               │
│         │ `foto_planilla` en `admin/partidos/acta.php`.                     │
├─────────┼───────────────────────────────────────────────────────────────────┤
│ Fase 5  │ Portal Público: Desplegar modal de planilla y botón GPS           │
│         │ "Cómo llegar" en `public/calendario.php` y `sedes.php`.           │
├─────────┼───────────────────────────────────────────────────────────────────┤
│ Fase 6  │ Pruebas Integrales: Ejecutar simulación de fixture, actas y       │
│         │ cómputo acumulado de la Copa de Cooperativas.                     │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 11. Criterios de Aceptación y Checklist de Verificación (Para Auditoría)

Antes de considerar concluida la implementación reglamentaria, se deben validar los siguientes 10 puntos de control:

- [ ] **1. Catálogo Completo:** Las 11 disciplinas están activas y disponibles para la programación de torneos.
- [ ] **2. Modalidad Mixta en Básquetbol:** El torneo de básquetbol está creado en rama `Mixto` con su respectiva regla.
- [ ] **3. Validación de Edad en Fútbol y Futsal:** El sistema detecta y alerta si no se cumple el mínimo de 3 jugadores $\ge 45$ años en Fútbol o 1 jugador $\ge 45$ años en Futsal.
- [ ] **4. Validación de Damas en Vóley y Básquet:** El sistema detecta y alerta si no se registran mínimo 2 damas en cancha en Voleibol o 1 dama en Básquetbol.
- [ ] **5. Acreditación de Operarios y Ejecutivos:** La nómina de atletas permite clasificar a trabajadores como Operativos, Ejecutivos o Administrativos.
- [ ] **6. Puntos Ponderados del Medallero:** Asignar podios en Fútbol suma 45/30/15 pts, Futsal 40/30/15 pts, Vóley/Básquet/Frontón/Maratón 30/20/10 pts, Técnicas 20/15/10 pts, Salón 15/10/5 pts y Juegos Tradicionales 5 pts cada uno.
- [ ] **7. Ordenamiento del Medallero:** La delegación con más puntos olímpicos acumulados encabeza la tabla general como potencial Cooperativa Campeona.
- [ ] **8. Subida y Visualización de Foto de Planilla:** La imagen del acta se sube sin errores y se previsualiza en alta resolución tanto en el panel administrativo como en el fixture público.
- [ ] **9. Levantamiento de Sanciones (Indulto):** Al levantar una sanción, el jugador pasa a `Habilitado` y se almacena el motivo formal y el usuario que autorizó el indulto.
- [ ] **10. Compatibilidad Estricta PHP 7.3:** Todos los controladores, modelos y helpers ejecutan limpiamente en PHP 7.3 bajo XAMPP sin arrojar advertencias ni errores fatales.
