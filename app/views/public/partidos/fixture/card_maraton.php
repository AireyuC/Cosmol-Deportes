<?php
/**
 * Submódulo: Tarjeta Oficial de la Disciplina Maratón en Fixture
 * Muestra el circuito oficial de 5.0 km, puntos de largada/meta y accesos GPS/Modal
 * Compatible con PHP 7.3
 */
if ($mostrarMaraton): ?>
<!-- Card de Disciplina: MARATÓN ANESAPA (Misma estética y estructura de Card de disciplina) -->
<div class="fixture-discipline-card maraton-fixture-card mb-4" data-discipline-name="Maratón ANESAPA">
    <div class="fixture-card-header">
        <div class="d-flex align-items-center">
            <div class="discipline-icon-badge mr-2" style="background: #FEF3C7; color: #D97706;">
                <i class="fas fa-running"></i>
            </div>
            <div>
                <h5 class="fixture-discipline-title mb-0">MARATÓN ANESAPA</h5>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <span class="discipline-count-badge">
                <i class="fas fa-route mr-1 text-primary"></i> 5.0 km
            </span>
        </div>
    </div>

    <div class="fixture-match-list">
        <div class="fixture-match-row" style="cursor: default;">
            <!-- Columna 1: Fecha & Hora -->
            <div class="fixture-col-date">
                <div class="fixture-date-val">
                    Sáb, 24 Oct 2026
                </div>
                <div class="fixture-time-val">
                    <i class="far fa-clock mr-1"></i> 07:30 AM
                    <span class="fixture-phase-badge">Largada Oficial</span>
                </div>
            </div>

            <!-- Columna 2: Evento & Circuito -->
            <div class="fixture-col-teams">
                <div class="w-100">
                    <div class="fixture-team-name text-dark">
                        Maratón ANESAPA 2026 • Circuito Urb. Celina Muyurina
                    </div>
                </div>
            </div>

            <!-- Columna 3: Sede & Acciones GPS / Modal -->
            <div class="fixture-col-venue">
                <div class="d-flex align-items-center justify-content-end flex-wrap">
                    <button type="button" class="btn btn-sm btn-mapa-maraton mr-2 my-1 shadow-sm" data-toggle="modal" data-target="#modalMapaMaraton">
                        <i class="fas fa-map-marked-alt mr-1"></i> Ver Mapa del Circuito
                    </button>
                    <button type="button" class="btn-gps-flat mr-1 my-1" onclick="abrirRutaGps(-17.359770, -63.258670, 'Partida Maratón ANESAPA')">
                        <i class="fas fa-play mr-1"></i> GPS Partida
                    </button>
                    <button type="button" class="btn-gps-flat my-1" onclick="abrirRutaGps(-17.361681, -63.244658, 'Meta Maratón ANESAPA')">
                        <i class="fas fa-flag-checkered mr-1"></i> GPS Meta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
