<?php
/**
 * Sección Oficial: Ruta Maratón Olimpiada 33 (Circuito Urb. Celina Muyurina - 5.0 km)
 * Portal Informativo COSMOL Deportes
 * Compatible con PHP 7.3
 */
?>
<!-- SECCIÓN OFICIAL: RUTA DE LA MARATÓN -->
<section id="maraton" class="py-5" style="background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%);">
    <div class="container">
        <!-- Encabezado de la Sección -->
        <div class="text-center mb-4">
            <h2 class="font-weight-bold mt-1" style="color: var(--cosmol-azul-deep); font-size: 2.1rem;">
                Ruta Oficial: Maratón 5.0 km
            </h2>
        </div>

        <!-- Tarjetas de Información / Métricas Rápidas -->
        <div class="row mb-4">
            <div class="col-6 col-md-4 mb-3">
                <div class="card border shadow-sm text-center p-3 h-100" style="border-radius: 12px; background: #FFFFFF; border-color: #E2E8F0 !important;">
                    <div class="text-muted text-xs text-uppercase font-weight-bold">Distancia Oficial</div>
                    <div class="font-weight-bold my-1" style="font-size: 1.6rem; color: var(--cosmol-azul-deep);">~5.0 km</div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-3">
                <div class="card border shadow-sm text-center p-3 h-100" style="border-radius: 12px; background: #FFFFFF; border-color: #E2E8F0 !important;">
                    <div class="text-muted text-xs text-uppercase font-weight-bold">Punto de Largada</div>
                    <div class="font-weight-bold my-1" style="font-size: 1.25rem; color: var(--cosmol-azul-deep)">Punto 1 (Inicio)</div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-3">
                <div class="card border shadow-sm text-center p-3 h-100" style="border-radius: 12px; background: #FFFFFF; border-color: #E2E8F0 !important;">
                    <div class="text-muted text-xs text-uppercase font-weight-bold">Punto de Llegada</div>
                    <div class="font-weight-bold my-1" style="font-size: 1.25rem; color: var(--cosmol-azul-deep);">Punto 12 (Meta)</div>
                </div>
            </div>
        </div>

        <!-- Contenedor Principal: Mapa Interactivo a Pantalla Completa -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card border shadow-sm h-100" style="border-radius: 16px; overflow: hidden; border-color: #CBD5E1 !important;">
                    <!-- Barra Superior de Control del Mapa -->
                    <div class="card-header bg-white py-3 px-3 d-flex flex-wrap justify-content-between align-items-center border-bottom">
                        <div class="d-flex align-items-center mb-2 mb-sm-0">
                            <span class="badge badge-pill badge-primary px-3 py-1 font-weight-bold mr-2" style="background-color: var(--cosmol-azul);">
                                Trazado Oficial 5.0 km
                            </span>
                        </div>
                        <!-- Botones Rápidos del Mapa -->
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary font-weight-bold" id="btn-fit-map" title="Ver todo el circuito">
                                <i class="fas fa-expand-arrows-alt mr-1"></i> Ver Todo
                            </button>
                            <button type="button" class="btn btn-outline-success font-weight-bold" id="btn-goto-start" title="Enfocar en la largada">
                                <i class="fas fa-play mr-1"></i> Partida
                            </button>
                            <button type="button" class="btn btn-outline-danger font-weight-bold" id="btn-goto-finish" title="Enfocar en la meta">
                                <i class="fas fa-flag-checkered mr-1"></i> Meta
                            </button>
                        </div>
                    </div>

                    <!-- Div del Mapa Leaflet -->
                    <div class="position-relative">
                        <div id="mapa-maraton" style="height: 400px; width: 100%; background-color: #E2E8F0; z-index: 1;"></div>
                        <!-- Indicador flotante en la esquina del mapa -->
                        <div class="position-absolute" style="bottom: 15px; left: 15px; z-index: 400; background: rgba(255, 255, 255, 0.94); backdrop-filter: blur(4px); padding: 6px 12px; border-radius: 8px; border: 1px solid #CBD5E1; font-size: 0.78rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                            <span class="d-inline-block mr-2" style="width: 12px; height: 4px; background: #0066B2; vertical-align: middle; border-radius: 2px;"></span>
                            <strong style="color: #0F172A;">Ruta 5.0 km • Urb. Celina Muyurina</strong>
                        </div>
                    </div>

                    <!-- Footer del Mapa con Atajos GPS y Descarga GeoJSON -->
                    <div class="card-footer bg-light p-3">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-2 mb-md-0 text-center text-md-left">
                                <small class="text-muted d-block d-sm-inline mt-1 mt-sm-0">
                                    <i class="fas fa-info-circle mr-1 text-primary"></i> Toque cualquier marcador para ver coordenadas y tramos.
                                </small>
                            </div>
                            <div class="col-md-6 text-center text-md-right">
                                <button type="button" class="btn btn-sm btn-outline-success font-weight-bold mr-1" onclick="abrirRutaGps(-17.360332, -63.258289, 'Partida Maratón Olimpiada 33 - Urb. Celina')">
                                    <i class="fas fa-location-arrow mr-1"></i> GPS Partida
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold mr-1" onclick="abrirRutaGps(-17.361665, -63.244581, 'Meta Maratón Olimpiada 33 - Urb. Celina')">
                                    <i class="fas fa-location-arrow mr-1"></i> GPS Meta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
