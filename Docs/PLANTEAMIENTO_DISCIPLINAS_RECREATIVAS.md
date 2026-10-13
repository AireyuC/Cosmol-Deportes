# Planteamiento Simplificado: Disciplinas Recreativas
## Karaoke, Billar y Cacho — Olimpiadas Cosmol (Modelo Directo: Ganador y Puntaje)

> **Documento:** Guía de Especificación y Auditoría  
> **Ubicación:** `Docs/PLANTEAMIENTO_DISCIPLINAS_RECREATIVAS.md`  
> **Criterio de Diseño:** Máxima simplicidad (KISS) — Registro directo de ganador y puntaje final, sin sobrecarga técnica.

---

## 1. Enfoque y Filosofía del Modelo Simplificado

A solicitud de la coordinación e ingeniería de Cosmol, la integración de **Karaoke**, **Billar** y **Cacho** se ejecutará bajo el **formato más simple y ágil posible**:
1. **Sin planillas complejas ni sets obligatorios:** No se desglosarán los 11 casilleros de dados de cacho, ni frame a frame de billar, ni matrices de 4 subcriterios de jurado en karaoke.
2. **Registro directo de acta:** El planillero o administrador únicamente carga el **Puntaje del Competidor Local** y el **Puntaje del Competidor Visitante** (ej. 92 vs 85, 2 vs 1, o 140 vs 110) y marca **Finalizado**.
3. **Cero impacto destructivo en el sistema:** Se aprovecha el 100% del motor actual de torneos, fixtures, tablas de posiciones y medallero sin alterar la base de datos relacional.

---

## 2. Detalle de Reglas por Disciplina (Formato Directo)

### 2.1 🎤 Karaoke

* **Naturaleza:** Competencia vocal/artística individual o en dúo por delegación.
* **Modalidad recomendada:** `Eliminatoria` (Llaves directas) o `Grupos_Playoff`.
* **Cómo se juega y registra el resultado:**
  * En cada duelo (o presentación de ronda), el participante local y el visitante cantan su tema.
  * El jurado asigna una nota final global (ej. escala de 1 a 100).
  * **Marcador Local:** Puntaje del participante A (ej. **88**).
  * **Marcador Visitante:** Puntaje del participante B (ej. **95**).
  * **Ganador:** Quien obtiene mayor puntaje.
* **Regla de Puntuación en Base de Datos:**
  * `pts_victoria`: **3** pts (o pase de ronda).
  * `pts_empate`: **0** pts (no hay empate; si empatan en puntos, el jurado define el desempate en vivo).
  * `pts_derrota`: **0** pts.
  * `permite_empate`: **0** (No).
  * `formato_sets`: **0** (No requiere sets).

---

### 2.2 🎱 Billar

* **Naturaleza:** Competencia de precisión individual (o parejas) en mesa de billar (Pool 8 bolas / Carambola).
* **Modalidad recomendada:** `Grupos_Playoff` o `Eliminatoria`.
* **Cómo se juega y registra el resultado:**
  * Se pacta el encuentro a partidas/chicos ganados (ej. al mejor de 3 o chico único).
  * **Marcador Local:** Mesas/chicos ganados por el jugador A (ej. **2**).
  * **Marcador Visitante:** Mesas/chicos ganados por el jugador B (ej. **1**).
  * **Ganador:** Quien logre más mesas ganadas.
* **Regla de Puntuación en Base de Datos:**
  * `pts_victoria`: **2** pts (o 3 según reglamento).
  * `pts_empate`: **0** pts (no hay empate en billar).
  * `pts_derrota`: **1** pt por presentación (o **0** pts si es eliminación simple).
  * `permite_empate`: **0** (No).
  * `formato_sets`: **0** (No requiere sets).

---

### 2.3 🎲 Cacho

* **Naturaleza:** Juego tradicional de dados (Generala / Cacho Alalay).
* **Modalidad recomendada:** `Grupos_Playoff` o `Liga`.
* **Cómo se juega y registra el resultado:**
  * Ambos jugadores lanzan sus dados en mesa y completan su partida.
  * Al terminar, se anota el puntaje global acumulado (o 1 - 0 si juegan por partidas ganadas).
  * **Marcador Local:** Puntos totales del jugador A (ej. **135**).
  * **Marcador Visitante:** Puntos totales del jugador B (ej. **110**).
  * **Ganador:** Quien sume más puntos en la partida.
* **Regla de Puntuación en Base de Datos:**
  * `pts_victoria`: **3** pts.
  * `pts_empate`: **1** pt (en caso de empate de puntos).
  * `pts_derrota`: **0** pts.
  * `permite_empate`: **1** (Sí).
  * `formato_sets`: **0** (No requiere sets).

---

## 3. Diagnóstico de Compatibilidad con el Sistema Actual

| Componente | ¿Requiere cambios en código? | Explicación |
|---|:---:|---|
| **Base de Datos (`schema.sql`)** | **NO (0 cambios)** | El esquema soporta cualquier disciplina. No se toca ninguna tabla ni columna. |
| **Modelos PDO (`Partido`, `Torneo`, `Disciplina`)** | **NO (0 cambios)** | Ya soportan partidos con `marcador_local` y `marcador_visitante` directos. |
| **Controlador de Actas (`PartidoController@acta`)** | **NO (0 cambios)** | El método `actualizarResultado` ya guarda los marcadores y finaliza el partido. |
| **Vista del Acta (`partidos/acta.php`)** | **NO (0 cambios)** | Al tener `formato_sets = 0`, la vista muestra automáticamente el formulario directo de marcador global. |
| **Fixture y Programación** | **NO (0 cambios)** | Se programan fecha, hora y sede/mesa exactamente igual que los demás deportes. |
| **Tabla de Posiciones y Medallero** | **NO (0 cambios)** | Se calculan automáticamente: PJ (Jugados), PG (Ganados), PE (Empatados), PP (Perdidos), PF (Puntos a Favor), PC (Puntos en Contra), Dif, y Puntos. |

---

## 4. Script de Semillas (Seeds) para Activación Inmediata

Para dar de alta las 3 disciplinas en el sistema con sus reglas simplificadas, solo se ejecutan los siguientes registros en la base de datos:

```sql
-- 1. Insertar las 3 nuevas disciplinas
INSERT INTO `disciplinas` (`nombre`, `descripcion`, `icono`, `estado`) VALUES
('Karaoke', 'Competencia de canto individual/parejas con calificación global del jurado', 'microphone', 'Activa'),
('Billar',  'Torneo de billar en partidas directas por mesas ganadas',                    'circle',     'Activa'),
('Cacho',   'Juego tradicional de dados por puntaje final acumulado',                    'dice',       'Activa');

-- 2. Insertar sus reglas de puntuación directas (formato_sets = 0)
-- Nota: Asociadas mediante subconsulta para garantizar precisión de IDs
INSERT INTO `reglas_puntuacion` (`disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`)
SELECT id, 3, 0, 0, 0, 0, 0, 0 FROM `disciplinas` WHERE `nombre` = 'Karaoke';

INSERT INTO `reglas_puntuacion` (`disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`)
SELECT id, 2, 0, 1, 0, 0, 0, 0 FROM `disciplinas` WHERE `nombre` = 'Billar';

INSERT INTO `reglas_puntuacion` (`disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`)
SELECT id, 3, 1, 0, 0, 1, 0, 0 FROM `disciplinas` WHERE `nombre` = 'Cacho';
```

---

## 5. Resumen del Flujo de Uso para el Usuario / Planillero

1. **Creación del Torneo:** En el menú *Torneos*, crear ej. `"Torneo de Cacho 2026"`, disciplina: Cacho, rama: Mixto/Libre.
2. **Inscripción de Equipos/Participantes:** Cada delegación inscribe a su representante (ej. *"Comercial Cacho"* o nombre del participante).
3. **Fixture:** Se generan los cruces o se programan los partidos con fecha, hora y mesa/recinto.
4. **Carga de Resultados (Acta Rápida):**
   * El planillero ingresa al partido.
   * Digita: `Local [ 140 ]  -  Visitante [ 115 ]`.
   * Clic en **Guardar y Finalizar**.
5. **Listo:** La tabla de posiciones se actualiza al instante, otorga los puntos correspondientes y el sistema define los clasificados a semifinales/finales y medallero.

---

## 6. Conclusión y Visto Bueno

Este esquema es:
* **100% Robusto:** No inventa dependencias complejas ni genera puntos de falla.
* **100% Intuitivo:** Cualquier usuario o delegado entiende de inmediato cómo cargar los resultados.
* **100% Compatible:** Respeta PHP 7.3, AdminLTE 3 y toda la arquitectura MVC construida hasta la Fase 7.
