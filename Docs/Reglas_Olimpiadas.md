# Manual Oficial de Reglas Olímpicas y Especificación Operativa del Sistema
## XXXIII Jornadas Deportivas y Recreativas Nacionales ANESAPA – COSMOL 2026 (Montero, Santa Cruz)
### Plataforma Oficial: Cosmol-Deportes

---

## 1. Visión General y Contexto Institucional

El presente documento constituye el **Manual Oficial de Reglas de Competencia y Especificación Operativa** de la plataforma **Cosmol-Deportes**, integrando las disposiciones de la **Convocatoria Oficial de las XXXIII Jornadas Deportivas Nacionales ANESAPA (COSMOL 2026)** con la arquitectura de software, base de datos y flujos de trabajo del sistema.

### 1.1. Entidades Involucradas
- **Entidad Anfitriona y Organizadora:** Cooperativa de Servicios Públicos Montero R.L. (**COSMOL R.L.**), Montero, Santa Cruz, Bolivia.
- **Entidad Matriz Reguladora:** Asociación Nacional de Empresas e Instituciones de Servicios de Agua Potable y Alcantarillado (**ANESAPA**).
- **Delegaciones Participantes:** Empresas Públicas Sociales de Agua y Saneamiento (EPSA), Cooperativas de Servicios Públicos e instituciones invitadas de los nueve departamentos del país (ej. **COSMOL R.L.**, **SAGUAPAC R.L.**, **SEMAPA**, **EMAPYC**, **EPSAS**, entre otras).

### 1.2. Misión y Alcance del Sistema Cosmol-Deportes
La plataforma **Cosmol-Deportes** automatiza la programación de encuentros, el control de elegibilidad de deportistas, el registro digital de actas arbitrales, el control disciplinario y el cálculo matemático instantáneo del **Medallero Olímpico General y la Copa de Cooperativas**, garantizando total transparencia e inmediatez de la información.

```
       ┌────────────────────────────────────────────────────────┐
       │   XXXIII JORNADAS DEPORTIVAS NACIONALES ANESAPA 2026   │
       │              COSMOL R.L. - Montero, Santa Cruz         │
       └──────────────────────────┬─────────────────────────────┘
                                  │
         ┌────────────────────────┴─────────────────────────┐
         ▼                                                  ▼
┌───────────────────────────────┐          ┌────────────────────────────────┐
│   PORTAL PÚBLICO EN VIVO      │          │   PANEL ADMINISTRATIVO         │
│   (Sin login - Todo público)  │          │   (AdminLTE 3 - Rol ADMIN)     │
├───────────────────────────────┤          ├────────────────────────────────┤
│ • Medallero y Puntaje General │          │ • Creación de Fixtures y Sedes │
│ • Fixture por disciplina      │          │ • Validación de Actas Oficiales│
│ • Tablas de posiciones        │          │ • Carga de Foto de Planilla    │
│ • Rutas GPS ("Cómo llegar")   │          │ • Régimen e Indultos Olímpicos │
│ • Visualización de planillas  │          │ • Proclamación de Podios       │
└───────────────────────────────┘          └────────────────────────────────┘
```

---

## 2. Catálogo Oficial de Disciplinas y Configuración en el Sistema

El campeonato comprende un total de **11 disciplinas puntuables**, agrupadas en cuatro modalidades competitivas y recreativas:

### 2.1. Clasificación de Disciplinas

| N° | Disciplina | Modalidad / Rama | Escenario Principal | Formato en Sistema | Puntos al Medallero (1°/2°/3°) |
|:--:|---|:---:|---|:---:|:---:|
| **1** | **Fútbol 11** | Masculino | Cancha de césped reglamentaria | Goles reglamentarios (90 min) | **45 / 30 / 15** |
| **2** | **Fútbol de Salón (Futsal)** | Masculino | Coliseo techado (parquet/cemento) | Goles reglamentarios (40 min) | **40 / 30 / 15** |
| **3** | **Voleibol Mixto** | Mixto | Coliseo cerrado | Sets a 25 puntos (al mejor de 3 o 5) | **30 / 20 / 10** |
| **4** | **Básquetbol Mixto** | Mixto | Coliseo cerrado | Canastas acumuladas (4 cuartos) | **30 / 20 / 10** |
| **5** | **Raqueta Frontón** | Parejas | Cancha de frontón reglamentaria | Sets a 15 puntos (al mejor de 3) | **30 / 20 / 10** |
| **6** | **Media Maratón (5K)** | Libre | Circuito urbano Montero | Posición por tiempo de cronometraje | **30 / 20 / 10** |
| **7** | **Competencias Técnicas Deportivas** | Operativos | Área técnica / Polígono demostrativo | Jurado técnico / Tiempo y estanqueidad | **20 / 15 / 10** |
| **8** | **Cacho Alalay** | Parejas (Ejecutivos) | Salón social de confraternización | Planilla Alalay / Marcador directo | **15 / 10 / 5** |
| **9** | **Billar** | Parejas (Ejecutivos) | Salón de billar | Chicos ganados (marcador directo) | **15 / 10 / 5** |
| **10** | **Karaoke (Noche de Talentos)** | Libre (Ejecutivos) | Salón de actos / Escenario cultural | Calificación jurado (puntuación directa) | **15 / 10 / 5** |
| **11** | **Juegos Tradicionales** | Mixto / Libre | Canchas auxiliares y explanadas | Puntos fijos directos por prueba | **5 pts por prueba** |

> [!NOTE]
> Los **Juegos Populares Tradicionales** comprenden tres pruebas independientes: **Carrera de embolsao** (5 pts), **Carrera de cuchara y huevo** (5 pts) y **Carrera de carretilla** (5 pts). Cada prueba ganada suma 5 puntos olímpicos directos a la delegación vencedora.

### 2.2. Parámetros Técnicos en Base de Datos

Cada disciplina está registrada en la tabla `disciplinas` y configurada en `reglas_puntuacion` con los siguientes parámetros:

```sql
-- Estructura de reglas en base de datos:
-- disciplinas (id, nombre, descripcion, icono, estado)
-- reglas_puntuacion (id, disciplina_id, pts_victoria, pts_empate, pts_derrota, pts_derrota_ajustada, permite_empate, formato_sets, sets_para_ganar)
```

- **Fútbol 11 y Futsal:** `permite_empate = 1`, `pts_victoria = 3`, `pts_empate = 1`, `pts_derrota = 0`, `formato_sets = 0`.
- **Voleibol Mixto:** `permite_empate = 0`, `pts_victoria = 3` (3-0 o 3-1) / `2` (3-2), `pts_derrota_ajustada = 1` (2-3), `pts_derrota = 0` (0-3 o 1-3), `formato_sets = 1`, `sets_para_ganar = 2` o `3`.
- **Básquetbol Mixto:** `permite_empate = 0`, `pts_victoria = 2`, `pts_derrota = 1`, `pts_derrota_ajustada = 0` (W.O. = 0 pts), `formato_sets = 0`.
- **Raqueta Frontón:** `permite_empate = 0`, `pts_victoria = 2`, `pts_derrota = 0`, `formato_sets = 1`, `sets_para_ganar = 2`.
- **Juegos de Salón y Recreativos (Cacho, Billar, Karaoke, Pruebas Técnicas, Juegos Populares):** `permite_empate = 0`, carga directa de resultados en planilla sin prórroga ni sets.

---

## 3. Reglamento de Elegibilidad y Reglas en Cancha (Art. 4 Convocatoria)

Para preservar el espíritu de camaradería, confraternización institucional y equidad de género, la Convocatoria establece **reglas estrictas de conformación obligatoria en cancha**, las cuales deben ser verificadas por las mesas técnicas antes de rubricar las actas:

### 3.1. Cuotas Obligatorias en Cancha

```
┌─────────────────────────┬────────────────────────────────────────────────────────┐
│ DISCIPLINA              │ EXIGENCIA OBLIGATORIA EN CANCHA                        │
├─────────────────────────┼────────────────────────────────────────────────────────┤
│ Fútbol 11               │ Mínimo 3 jugadores de 45 años cumplidos o más.         │
│ Fútbol de Salón (5)     │ Mínimo 1 jugador de 45 años cumplidos o más.           │
│ Voleibol Mixto          │ Mínimo 1 jugador(a) de 50 años cumplidos o más         │
│                         │ y OBLIGATORIAMENTE 2 damas permanentemente en cancha.  │
│ Básquetbol Mixto        │ Mínimo 1 jugador(a) de 50 años cumplidos o más         │
│                         │ y OBLIGATORIAMENTE 1 dama permanentemente en cancha.   │
│ Pruebas Técnicas        │ Obligatorio 3 trabajadores operativos por delegación.  │
│ Disciplinas Recreativas │ Participación de al menos 1 ejecutivo o director       │
│ (Cacho/Billar/Karaoke)  │ acreditado por delegación.                             │
└─────────────────────────┴────────────────────────────────────────────────────────┘
```

### 3.2. Criterios de Inscripción y Validación en el Sistema
- **Nómina Oficial (`jugadores`):** Todo participante debe estar registrado en el sistema con su **Cédula de Identidad**, **Nombres y Apellidos**, **Fecha de Nacimiento**, **Número de Ítem** y fotografía.
- **Antigüedad Laboral:** Se exige un mínimo de **3 meses de antigüedad continua** en la institución o cooperativa, respaldada por la planilla salarial oficial.
- **Control de Edad Automatizado:** La fecha de nacimiento registrada en el sistema permite verificar automáticamente si el deportista cumple con el umbral etario (&ge;45 años o &ge;50 años) al momento de la disputa del torneo.
- **Inhabilitaciones Previas:** El sistema bloquea automáticamente la inscripción de atletas que arrastren sanciones vigentes de campeonatos anteriores.

---

## 4. Medallero Olímpico y Sistema de Puntuación Acumulada (Art. 15 Convocatoria)

El sistema **Cosmol-Deportes** implementa un modelo de puntuación ponderada para determinar a la **Cooperativa / Delegación Campeona Absoluta** de las Jornadas.

### 4.1. Escala Oficial de Puntos Olímpicos Ponderados

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

### 4.2. Algoritmo de Cálculo en el Sistema (`app/models/Medallero.php`)

La proclamación de la institución campeona general se ejecuta mediante el cómputo del **Puntaje Olímpico Acumulado**:

$$\text{Puntaje Acumulado} = \sum (\text{Puntos de Medallas Obtenidas}) + \sum (\text{Puntos de Juegos Populares Ganados})$$

En la base de datos, cada medalla se almacena en la tabla `medallero` con su correspondiente asignación de puntos en la columna `puntos_olimpicos`. La consulta oficial ejecutada por `Medallero::getTablaAcumulada()` ordena el medallero bajo el siguiente criterio estricto:

1. **Puntaje Olímpico Acumulado** (`puntos_olimpicos DESC`)
2. **Mayor cantidad de Medallas de Oro** (`oros DESC`)
3. **Mayor cantidad de Medallas de Plata** (`platas DESC`)
4. **Mayor cantidad de Medallas de Bronce** (`bronces DESC`)

```sql
SELECT 
    d.id AS delegacion_id,
    d.nombre AS delegacion_nombre,
    d.alias,
    d.color_representativo,
    d.logo_url,
    COUNT(CASE WHEN m.tipo_medalla = 'Oro' THEN 1 END) AS oros,
    COUNT(CASE WHEN m.tipo_medalla = 'Plata' THEN 1 END) AS platas,
    COUNT(CASE WHEN m.tipo_medalla = 'Bronce' THEN 1 END) AS bronces,
    COUNT(m.id) AS total_medallas,
    COALESCE(SUM(m.puntos_olimpicos), 0) AS puntos_olimpicos
FROM delegaciones d
LEFT JOIN medallero m ON d.id = m.delegacion_id AND m.olimpiada_id = :olimpiada_id
GROUP BY d.id, d.nombre, d.alias, d.color_representativo, d.logo_url
ORDER BY puntos_olimpicos DESC, oros DESC, platas DESC, bronces DESC, d.nombre ASC;
```

---

## 5. Protocolo de Planillas Oficiales y Verificación Digital (`foto_planilla`)

Para garantizar la inalterabilidad de los resultados y brindar respaldo ante cualquier impugnación de delegados (Art. 11 y 12), el sistema incorpora el flujo de **Digitalización Obligatoria de Planillas de Juego**:

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

### 5.1. Reglas Técnicas de la Carga de Planilla
- **Formatos Permitidos:** Archivos de imagen `JPG`, `JPEG`, `PNG` y `WEBP`.
- **Tamaño Máximo:** 5 MB por archivo.
- **Ruta de Almacenamiento:** `/public/uploads/planillas/{olimpiada_id}_{partido_id}_{timestamp}.ext`.
- **Transparencia Pública:** Cualquier usuario, deportista o delegado de otra cooperativa puede inspeccionar la fotografía de la planilla oficial haciendo clic en el icono de planilla en el fixture del portal público (`/portal/fixture`).

---

## 6. Régimen Disciplinario y Levantamiento de Sanciones (Espíritu de Confraternización)

El sistema administra con rigor las amonestaciones y expulsiones ocurridas en los encuentros, integrando al mismo tiempo una herramienta administrativa de **Indulto de Confraternización**:

### 6.1. Tipos de Sanciones y Efectos Automáticos
- **Tarjeta Amarilla (`AMARILLA`):** Amonestación preventiva acumulable en el historial del deportista.
- **Tarjeta Roja / Expulsión (`ROJA` / `EXPULSION`):** Inhabilitación automática para el siguiente partido de la misma disciplina.
- **Suspensión por Fechas (`PARTIDOS_SUSPENSION`):** Número determinado de jornadas de suspensión impuestas por el Tribunal de Disciplina o Comité Organizador.
- **Bloqueo en Nómina:** Un jugador con sanción en estado `'Activa'` aparece bloqueado y no puede ser alineado en el acta digital del siguiente partido.

### 6.2. Procedimiento de Levantamiento de Sanciones (Indulto Olímpico)
De acuerdo con las facultades del Comité Organizador y la directiva de ANESAPA, un Administrador (`ADMIN`) puede anular o levantar una sanción por causas reglamentarias:
1. **Error Arbitral Corregido:** Rectificación demostrada mediante la fotografía de la planilla o informe ampliatorio del juez.
2. **Apelación Aceptada:** Resolución favorable emitida por el Directorio de ANESAPA (Art. 12).
3. **Indulto de Confraternización:** Conmutación de sanción deportiva para fomentar el espíritu de hermandad inter-cooperativa en las jornadas finales.

> [!IMPORTANT]
> **Trazabilidad Inmutable:** Al levantar una sanción, el sistema exige ingresar obligatoriamente un **Motivo Formal de Levantamiento** y registra automáticamente el usuario administrador responsable y la marca de tiempo, garantizando auditoría total.

---

## 7. Módulo de Sedes, Canchas y Geolocalización GPS en Montero

Para orientar a las delegaciones visitantes de otros departamentos, el sistema dispone de un módulo geográfico interactivo:

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                      MAPA Y GUÍA DE SEDES EN MONTERO                            │
│  Sede Central: Oficinas y Salón COSMOL R.L. (Calle Isaías Parada N° 219)        │
│  Complejos Deportivos: Canchas de césped, Coliseos cerrados y Polígonos ANESAPA │
└─────────────────────────────────────────────────────────────────────────────────┘
```

- **Coordenadas Satelitales:** Cada sede cuenta con `latitud` y `longitud` registradas con precisión en la base de datos (`sedes`).
- **Botón "Cómo llegar" (GPS):** Utiliza la API nativa del navegador del usuario (`navigator.geolocation.getCurrentPosition()`) y genera un enlace dinámico hacia Google Maps:
  `https://www.google.com/maps/dir/?api=1&origin={lat_usuario},{lng_usuario}&destination={lat_sede},{lng_sede}`
- **Prevención de Colisiones de Fixture:** El programador de partidos del sistema valida en tiempo real que no se asigne la misma cancha en franjas horarias superpuestas, ni que una misma delegación dispute dos encuentros simultáneos.

---

## 8. Matriz de Trazabilidad Técnica: Convocatoria vs. Código Fuente

| Artículo / Regla Convocatoria | Elemento en Base de Datos | Modelo PHP | Controlador | Vista de Usuario / Admin |
|---|---|---|---|---|
| **Macro-Evento y Sedes** | Tablas `olimpiadas`, `sedes`, `canchas` | `Olimpiada.php`, `Sede.php`, `Cancha.php` | `OlimpiadaController.php`, `SedeController.php` | `admin/sedes/`, `public/sedes.php` |
| **11 Disciplinas y Reglas** | Tablas `disciplinas`, `reglas_puntuacion` | `Disciplina.php`, `ReglaPuntuacion.php` | `DisciplinaController.php`, `ReglaPuntuacionController.php` | `admin/disciplinas/`, `public/disciplina.php` |
| **Delegaciones y Jugadores (Art. 4-6)** | Tablas `delegaciones`, `equipos`, `jugadores` | `Delegacion.php`, `Equipo.php`, `Jugador.php` | `DelegacionController.php`, `EquipoController.php`, `JugadorController.php` | `admin/equipos/`, `admin/jugadores/`, `public/delegaciones.php` |
| **Fixture y Programación (Art. 8-11)** | Tablas `torneos`, `torneo_grupos`, `partidos` | `Torneo.php`, `Grupo.php`, `Partido.php` | `FixtureController.php`, `PartidoController.php` | `admin/partidos/`, `public/calendario.php` |
| **Actas y Foto de Planilla** | Columnas `partidos.foto_planilla`, tabla `partido_sets` | `Partido.php` | `PartidoController.php` (métodos `updateMarcador`, `uploadFotoPlanilla`) | `admin/partidos/show.php`, modal en `public/calendario.php` |
| **Régimen Disciplinario e Indultos** | Tabla `sanciones` | `Sancion.php` | `SancionController.php` (método `levantar`) | `admin/sanciones/index.php` |
| **Medallero y Puntos (Art. 15)** | Tabla `medallero` (`puntos_olimpicos`) | `Medallero.php` | `MedalleroController.php` | `admin/medallero/index.php`, `public/medallero.php` |

---

## 9. Autoridades y Comité Organizador Oficial

- **Sr. Mario Moreno** – Comité Organizador, COSMOL R.L.
- **Lic. Johnny Gonzales Q.** – Comité Organizador, COSMOL R.L.
- **Ing. Romer Parada B.** – Gerente General, COSMOL R.L.
- **Directorio Nacional ANESAPA** – Entidad Fiscalizadora y Tribunal de Alzada.
