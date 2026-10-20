<?php
/**
 * Submódulo: Modal Interactivo del Mapa Oficial de la Maratón (Olimpiada 33)
 * Integra el visor satelital Leaflet, puntos WGS84 y controles de partida y meta
 * Compatible con PHP 7.3
 */
?>
<!-- MODAL INTERACTIVO: MAPA OFICIAL DE LA MARATÓN (OLIMPIADA 33) -->
<div class="modal fade" id="modalMapaMaraton" tabindex="-1" role="dialog" aria-labelledby="modalMapaMaratonLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <!-- Header del Modal -->
            <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #002D52 0%, #0066B2 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3" style="font-size: 1.6rem; color: #F59E0B;">
                        <i class="fas fa-running"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalMapaMaratonLabel">
                            Circuito Oficial: Maratón 5.0 km (Olimpiada 33)
                        </h5>
                        <small class="text-light" style="opacity: 0.9;">
                            Urb. Celina Muyurina (Montero) • 11 Puntos de Giro Oficiales WGS84
                        </small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Barra de Métricas y Controles Rápidos -->
            <div class="bg-light px-4 py-2 border-bottom d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex flex-wrap align-items-center my-1">
                    <span class="badge badge-primary px-3 py-1 mr-2 mb-1" style="background-color: var(--cosmol-azul);">
                        <i class="fas fa-route mr-1"></i> Distancia: ~5.0 km
                    </span>
                    <span class="badge badge-success px-3 py-1 mr-2 mb-1">
                        <i class="fas fa-play mr-1"></i> Partida: Recta SE
                    </span>
                    <span class="badge badge-danger px-3 py-1 mr-2 mb-1">
                        <i class="fas fa-flag-checkered mr-1"></i> Meta: Sector NE
                    </span>
                    <span class="badge badge-warning text-dark font-weight-bold px-3 py-1 mr-2 mb-1">
                        <i class="fas fa-map-pin mr-1"></i> 11 Hitos de Giro
                    </span>
                </div>
                <div class="btn-group btn-group-sm my-1" role="group">
                    <button type="button" class="btn btn-outline-primary" id="btn-modal-fit-map" title="Ver todo el circuito">
                        <i class="fas fa-expand-arrows-alt mr-1"></i> Ver Todo
                    </button>
                    <button type="button" class="btn btn-outline-success" id="btn-modal-goto-start" title="Enfocar en la largada">
                        <i class="fas fa-play mr-1"></i> Partida
                    </button>
                    <button type="button" class="btn btn-outline-danger" id="btn-modal-goto-finish" title="Enfocar en la meta">
                        <i class="fas fa-flag-checkered mr-1"></i> Meta
                    </button>
                </div>
            </div>

            <!-- Cuerpo del Modal: Mapa Interactivo -->
            <div class="modal-body p-0 position-relative">
                <div id="mapa-maraton-modal" style="height: 440px; width: 100%; background-color: #E2E8F0;"></div>
                <!-- Indicador flotante en el mapa -->
                <div class="position-absolute" style="bottom: 15px; left: 15px; z-index: 400; background: rgba(255, 255, 255, 0.94); backdrop-filter: blur(4px); padding: 6px 12px; border-radius: 8px; border: 1px solid #CBD5E1; font-size: 0.78rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <span class="d-inline-block mr-2" style="width: 12px; height: 4px; background: #0066B2; vertical-align: middle; border-radius: 2px;"></span>
                    <strong style="color: #0F172A;">Trazado Oficial 5.0 km • Urb. Celina</strong>
                </div>
            </div>

            <!-- Footer del Modal -->
            <div class="modal-footer bg-light px-4 py-3 d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <a href="<?php echo BASE_URL; ?>/data/ruta-maraton.geojson" download="ruta-maraton-olimpiadas-cosmol.geojson" class="btn btn-sm btn-outline-primary font-weight-bold mr-1">
                        <i class="fas fa-download mr-1"></i> GeoJSON
                    </a>
                    <a href="<?php echo BASE_URL; ?>/#maraton" class="btn btn-sm btn-link text-primary font-weight-bold">
                        <i class="fas fa-info-circle mr-1"></i> Ficha en Portada
                    </a>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-success font-weight-bold mr-1" onclick="abrirRutaGps(-17.360332, -63.258289, 'Partida Maratón Olimpiada 33')">
                        <i class="fas fa-location-arrow mr-1"></i> GPS Partida
                    </button>
                    <button type="button" class="btn btn-sm btn-danger font-weight-bold mr-2" onclick="abrirRutaGps(-17.361665, -63.244581, 'Meta Maratón Olimpiada 33')">
                        <i class="fas fa-location-arrow mr-1"></i> GPS Meta
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
