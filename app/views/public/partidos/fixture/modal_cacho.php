<?php
/**
 * Submódulo: Modal Interactivo de la Llave de Eliminación Directa de Cacho
 * Presenta el cuadro oficial de 8 equipos, llaves clasificatorias, semifinales y final
 * Fuente: Docs/cacho_eliminatoria.md
 * Compatible con PHP 7.3
 */
?>
<!-- MODAL INTERACTIVO: CUADRO DE ELIMINATORIA DE CACHO -->
<div class="modal fade" id="modalLlaveCacho" tabindex="-1" role="dialog" aria-labelledby="modalLlaveCachoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            
            <!-- Header del Modal -->
            <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #002D52 0%, #0066B2 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3" style="font-size: 1.6rem; color: #F59E0B;">
                        <i class="fas fa-dice"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalLlaveCachoLabel">
                            Torneo de Cacho: Cuadro de Eliminación Directa
                        </h5>
                        <small class="text-white-50" style="font-size: 0.85rem;">
                            Jornadas Deportivas Nacionales ANESAPA - COSMOL 2026 (8 Equipos)
                        </small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Barra de Información y Sede -->
            <div class="bg-light px-4 py-2 border-bottom d-flex flex-wrap justify-content-between align-items-center">
                <div class="my-1 text-sm text-dark">
                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                    <strong>Sede:</strong> Billar Springfield, Calle Isaías Parada esq. Chuquisaca
                    <span class="mx-2 text-muted">•</span>
                    <i class="far fa-calendar-alt text-primary mr-1"></i>
                    <strong>Viernes 23 de Octubre</strong> (20:00 en adelante)
                </div>
                <div class="my-1">
                    <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" onclick="abrirRutaGps(-17.341496, -63.254641, 'Billar Springfield')">
                        <i class="fas fa-location-arrow mr-1"></i> Cómo llegar (GPS)
                    </button>
                </div>
            </div>

            <!-- Cuerpo del Modal -->
            <div class="modal-body p-4" style="background: #F8FAFC; max-height: 75vh; overflow-y: auto;">
                
                <!-- Nav Tabs para alternar entre Cuadro Visual y Esquema del Fixture -->
                <ul class="nav nav-pills mb-4 justify-content-center" id="pills-cacho-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold px-4" id="pills-cuadro-cacho-tab" data-toggle="pill" href="#pills-cuadro-cacho" role="tab" aria-controls="pills-cuadro-cacho" aria-selected="true">
                            <i class="fas fa-sitemap mr-1"></i> Llaves por Fases
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold px-4" id="pills-esquema-cacho-tab" data-toggle="pill" href="#pills-esquema-cacho" role="tab" aria-controls="pills-esquema-cacho" aria-selected="false">
                            <i class="fas fa-project-diagram mr-1"></i> Esquema de Eliminación (Árbol)
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-cacho-tabContent">
                    
                    <!-- Pestaña 1: Llaves por Fases -->
                    <div class="tab-pane fade show active" id="pills-cuadro-cacho" role="tabpanel" aria-labelledby="pills-cuadro-cacho-tab">
                        
                        <!-- Fase 1: Clasificatorios (4 Llaves) -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <h6 class="font-weight-bold text-uppercase mb-0 text-primary" style="letter-spacing: 0.5px;">
                                    <i class="fas fa-play-circle mr-1"></i> Fase 1 – Clasificatorios (Cuartos de Final)
                                </h6>
                                <span class="badge badge-primary px-2 py-1">4 Encuentros</span>
                            </div>

                            <div class="row">
                                <!-- Llave A -->
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-dark">Llave A</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i>20:00</small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark">COSMOL</span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark">SELA</span>
                                                <span class="badge badge-light border text-muted">Visitante</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Llave B -->
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-dark">Llave B</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i>20:30</small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark">SAGUAPAC</span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark">EMAPYC</span>
                                                <span class="badge badge-light border text-muted">Visitante</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Llave C -->
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-dark">Llave C</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i>21:00</small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark">ELAPAS</span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark">COSAALT</span>
                                                <span class="badge badge-light border text-muted">Visitante</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Llave D -->
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-dark">Llave D</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i>21:30</small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark">SEMAPA</span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark">SAMAPA</span>
                                                <span class="badge badge-light border text-muted">Visitante</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fase 2 y Fase Final (Semifinales + Gran Final + 3er Puesto) -->
                        <div class="row">
                            <!-- Semifinales -->
                            <div class="col-lg-6 mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <h6 class="font-weight-bold text-uppercase mb-0 text-info" style="letter-spacing: 0.5px;">
                                        <i class="fas fa-layer-group mr-1"></i> Fase 2 – Semifinales
                                    </h6>
                                    <span class="badge badge-info px-2 py-1">2 Encuentros</span>
                                </div>

                                <div class="card mb-3 border shadow-sm" style="border-radius: 10px;">
                                    <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                        <span class="badge badge-info">Semifinal 1</span>
                                        <small class="text-muted"><i class="far fa-clock mr-1"></i>22:00</small>
                                    </div>
                                    <div class="card-body p-2 text-sm">
                                        <div class="py-1 border-bottom font-weight-bold text-dark">Ganador Llave A (COSMOL o SELA)</div>
                                        <div class="py-1 font-weight-bold text-dark">Ganador Llave C (ELAPAS o COSAALT)</div>
                                    </div>
                                </div>

                                <div class="card border shadow-sm" style="border-radius: 10px;">
                                    <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                        <span class="badge badge-info">Semifinal 2</span>
                                        <small class="text-muted"><i class="far fa-clock mr-1"></i>22:00</small>
                                    </div>
                                    <div class="card-body p-2 text-sm">
                                        <div class="py-1 border-bottom font-weight-bold text-dark">Ganador Llave B (SAGUAPAC o EMAPYC)</div>
                                        <div class="py-1 font-weight-bold text-dark">Ganador Llave D (SEMAPA o SAMAPA)</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fase Final -->
                            <div class="col-lg-6 mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <h6 class="font-weight-bold text-uppercase mb-0 text-warning" style="letter-spacing: 0.5px; color: #D97706 !important;">
                                        <i class="fas fa-trophy mr-1"></i>Fase Final
                                    </h6>
                                </div>

                                <!-- Gran Final -->
                                <div class="card mb-3 border border-warning shadow-sm" style="border-radius: 10px; background: #FFFBEB;">
                                    <div class="card-header py-1 px-3 d-flex justify-content-between align-items-center border-bottom" style="background: #FEF3C7;">
                                        <span class="badge badge-warning text-dark font-weight-bold"><i class="fas fa-crown text-warning mr-1"></i> Gran Final (Oro y Plata)</span>
                                        <small class="text-dark font-weight-bold"><i class="far fa-clock mr-1"></i>22:30</small>
                                    </div>
                                    <div class="card-body p-2 text-sm">
                                        <div class="py-1 border-bottom font-weight-bold text-dark">Ganador Semifinal 1</div>
                                        <div class="py-1 font-weight-bold text-dark">Ganador Semifinal 2</div>
                                    </div>
                                </div>

                                <!-- 3er y 4to Lugar -->
                                <div class="card border shadow-sm" style="border-radius: 10px;">
                                    <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                        <span class="badge badge-secondary"><i class="fas fa-medal mr-1"></i> 3er y 4to Puesto</span>
                                        <small class="text-muted"><i class="far fa-clock mr-1"></i>22:30</small>
                                    </div>
                                    <div class="card-body p-2 text-sm">
                                        <div class="py-1 border-bottom font-weight-bold text-dark">Perdedor Semifinal 1</div>
                                        <div class="py-1 font-weight-bold text-dark">Perdedor Semifinal 2</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Pestaña 2: Esquema Oficial (Árbol) -->
                    <div class="tab-pane fade" id="pills-esquema-cacho" role="tabpanel" aria-labelledby="pills-esquema-cacho-tab">
                        <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #0F172A; color: #38BDF8;">
                            <div class="card-header py-2 px-3 border-bottom d-flex justify-content-between align-items-center" style="background: #1E293B;">
                                <span class="font-weight-bold text-white text-sm">
                                    <i class="fas fa-dice mr-2 text-warning"></i>Diagrama Oficial de la Eliminatoria Directa
                                </span>
                                <span class="badge badge-info">8 Delegaciones</span>
                            </div>
                            <div class="card-body p-3">
                                <pre class="mb-0 text-monospace text-light" style="font-size: 0.85rem; line-height: 1.45; overflow-x: auto; color: #38BDF8 !important;">
CLASIFICATORIOS             SEMIFINALES          FINAL

A  COSMOL   vs SELA     ─┐
                          ├─ Semi 1 ─┐
C  ELAPAS   vs COSAALT  ─┘           │
                                     ├─ FINAL
B  SAGUAPAC vs EMAPYC   ─┐           │
                          ├─ Semi 2 ─┘
D  SEMAPA   vs SAMAPA   ─┘

Perdedores de Semi 1 y Semi 2 → 3er y 4to lugar
                                </pre>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Footer del Modal -->
            <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted text-xs">
                        <i class="fas fa-info-circle mr-1"></i> Modalidad por parejas directivas de cada delegación institucional.
                    </span>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-primary font-weight-bold mr-2" onclick="abrirRutaGps(-17.341496, -63.254641, 'Billar Springfield')">
                        <i class="fas fa-map-marked-alt mr-1"></i> Ver Sede Springfield
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
