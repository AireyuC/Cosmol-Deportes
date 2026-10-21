<?php
/**
 * Submódulo: Modal Interactivo de la Llave de Eliminación Directa de Billar
 * Presenta el cuadro oficial de 16 equipos, llaves clasificatorias, cuartos, semifinales y final
 * Se alimenta dinámicamente de la programación de partidos registrada en la base de datos.
 * Compatible con PHP 7.3
 */

$billarMap = [];
if (!empty($partidos) && is_array($partidos)) {
    foreach ($partidos as $pItem) {
        $discId = isset($pItem['disciplina_id']) ? (int)$pItem['disciplina_id'] : 0;
        $discNom = isset($pItem['disciplina_nombre']) ? mb_strtolower(trim($pItem['disciplina_nombre']), 'UTF-8') : '';
        if ($discId === 9 || strpos($discNom, 'billar') !== false) {
            $j = trim($pItem['jornada']);
            $billarMap[$j] = $pItem;
        }
    }
}

if (!function_exists('billarMatchData')) {
    function billarMatchData($map, $key, $field, $fallback) {
        if (isset($map[$key][$field]) && $map[$key][$field] !== '' && $map[$key][$field] !== null) {
            return $map[$key][$field];
        }
        return $fallback;
    }
}

$bLocalA = billarMatchData($billarMap, 'Llave A', 'local_nombre', 'COSMOL A');
$bVisitA = billarMatchData($billarMap, 'Llave A', 'visitante_nombre', 'SEMAPA B');
$bHoraA  = !empty($billarMap['Llave A']['hora_partido']) ? substr($billarMap['Llave A']['hora_partido'], 0, 5) : '20:00';

$bLocalB = billarMatchData($billarMap, 'Llave B', 'local_nombre', 'SAGUAPAC A');
$bVisitB = billarMatchData($billarMap, 'Llave B', 'visitante_nombre', 'COSMOL C (Llave B)');
$bHoraB  = !empty($billarMap['Llave B']['hora_partido']) ? substr($billarMap['Llave B']['hora_partido'], 0, 5) : '20:00';

$bLocalC = billarMatchData($billarMap, 'Llave C', 'local_nombre', 'ELAPAS A');
$bVisitC = billarMatchData($billarMap, 'Llave C', 'visitante_nombre', 'COSAALT B');
$bHoraC  = !empty($billarMap['Llave C']['hora_partido']) ? substr($billarMap['Llave C']['hora_partido'], 0, 5) : '20:30';

$bLocalD = billarMatchData($billarMap, 'Llave D', 'local_nombre', 'SEMAPA A');
$bVisitD = billarMatchData($billarMap, 'Llave D', 'visitante_nombre', 'SAGUAPAC B');
$bHoraD  = !empty($billarMap['Llave D']['hora_partido']) ? substr($billarMap['Llave D']['hora_partido'], 0, 5) : '20:30';

$bLocalE = billarMatchData($billarMap, 'Llave E', 'local_nombre', 'SAMAPA A');
$bVisitE = billarMatchData($billarMap, 'Llave E', 'visitante_nombre', 'COSMOL B');
$bHoraE  = !empty($billarMap['Llave E']['hora_partido']) ? substr($billarMap['Llave E']['hora_partido'], 0, 5) : '21:00';

$bLocalF = billarMatchData($billarMap, 'Llave F', 'local_nombre', 'COSAALT A');
$bVisitF = billarMatchData($billarMap, 'Llave F', 'visitante_nombre', 'SAMAPA B');
$bHoraF  = !empty($billarMap['Llave F']['hora_partido']) ? substr($billarMap['Llave F']['hora_partido'], 0, 5) : '21:00';

$bLocalG = billarMatchData($billarMap, 'Llave G', 'local_nombre', 'SELA A');
$bVisitG = billarMatchData($billarMap, 'Llave G', 'visitante_nombre', 'ELAPAS B');
$bHoraG  = !empty($billarMap['Llave G']['hora_partido']) ? substr($billarMap['Llave G']['hora_partido'], 0, 5) : '21:30';

$bLocalH = billarMatchData($billarMap, 'Llave H', 'local_nombre', 'COSMOL C (Llave H)');
$bVisitH = billarMatchData($billarMap, 'Llave H', 'visitante_nombre', 'SELA B');
$bHoraH  = !empty($billarMap['Llave H']['hora_partido']) ? substr($billarMap['Llave H']['hora_partido'], 0, 5) : '21:30';
?>
<!-- MODAL INTERACTIVO: CUADRO DE ELIMINATORIA DE BILLAR -->
<div class="modal fade" id="modalLlaveBillar" tabindex="-1" role="dialog" aria-labelledby="modalLlaveBillarLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            
            <!-- Header del Modal -->
            <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #002D52 0%, #0066B2 100%);">
                <div class="d-flex align-items-center">
                    <div class="mr-3" style="font-size: 1.6rem; color: #F59E0B;">
                        <i class="fas fa-dot-circle"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalLlaveBillarLabel">
                            Torneo de Billar: Cuadro de Eliminación Directa
                        </h5>
                        <small class="text-white-50" style="font-size: 0.85rem;">
                            Jornadas Deportivas Nacionales ANESAPA - COSMOL 2026 (16 Equipos)
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
                <ul class="nav nav-pills mb-4 justify-content-center" id="pills-billar-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold px-4" id="pills-cuadro-tab" data-toggle="pill" href="#pills-cuadro" role="tab" aria-controls="pills-cuadro" aria-selected="true">
                            <i class="fas fa-sitemap mr-1"></i> Llaves por Fases
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold px-4" id="pills-esquema-tab" data-toggle="pill" href="#pills-esquema" role="tab" aria-controls="pills-esquema" aria-selected="false">
                            <i class="fas fa-project-diagram mr-1"></i> Esquema de Eliminación (Árbol)
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-billar-tabContent">
                    
                    <!-- Pestaña 1: Llaves por Fases -->
                    <div class="tab-pane fade show active" id="pills-cuadro" role="tabpanel" aria-labelledby="pills-cuadro-tab">
                        
                        <!-- Fase 1: Clasificatorios (8 Llaves Dinámicas) -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <h6 class="font-weight-bold text-uppercase mb-0 text-primary" style="letter-spacing: 0.5px;">
                                    <i class="fas fa-play-circle mr-1"></i> Fase 1 – Clasificatorios (Octavos de Final)
                                </h6>
                                <span class="badge badge-primary px-2 py-1">8 Encuentros</span>
                            </div>

                            <div class="row">
                                <!-- Llave A -->
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-dark">Llave A</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i><?php echo e($bHoraA); ?></small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark"><?php echo e($bLocalA); ?></span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark"><?php echo e($bVisitA); ?></span>
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
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i><?php echo e($bHoraB); ?></small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark"><?php echo e($bLocalB); ?></span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark"><?php echo e($bVisitB); ?></span>
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
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i><?php echo e($bHoraC); ?></small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark"><?php echo e($bLocalC); ?></span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark"><?php echo e($bVisitC); ?></span>
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
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i><?php echo e($bHoraD); ?></small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark"><?php echo e($bLocalD); ?></span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark"><?php echo e($bVisitD); ?></span>
                                                <span class="badge badge-light border text-muted">Visitante</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Llave E -->
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-dark">Llave E</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i><?php echo e($bHoraE); ?></small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark"><?php echo e($bLocalE); ?></span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark"><?php echo e($bVisitE); ?></span>
                                                <span class="badge badge-light border text-muted">Visitante</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Llave F -->
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-dark">Llave F</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i><?php echo e($bHoraF); ?></small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark"><?php echo e($bLocalF); ?></span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark"><?php echo e($bVisitF); ?></span>
                                                <span class="badge badge-light border text-muted">Visitante</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Llave G -->
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-dark">Llave G</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i><?php echo e($bHoraG); ?></small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark"><?php echo e($bLocalG); ?></span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark"><?php echo e($bVisitG); ?></span>
                                                <span class="badge badge-light border text-muted">Visitante</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Llave H -->
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-dark">Llave H</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i><?php echo e($bHoraH); ?></small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span class="font-weight-bold text-dark"><?php echo e($bLocalH); ?></span>
                                                <span class="badge badge-light border text-muted">Local</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-bold text-dark"><?php echo e($bVisitH); ?></span>
                                                <span class="badge badge-light border text-muted">Visitante</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fase 2: Cuartos de Final (4 Encuentros) -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <h6 class="font-weight-bold text-uppercase mb-0 text-success" style="letter-spacing: 0.5px;">
                                    <i class="fas fa-layer-group mr-1"></i> Fase 2 – Cuartos de Final
                                </h6>
                                <span class="badge badge-success px-2 py-1">4 Encuentros</span>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-success">Cuarto 1</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i>22:00</small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="py-1 border-bottom font-weight-bold text-dark">Ganador Llave A (<?php echo e($bLocalA); ?> / <?php echo e($bVisitA); ?>)</div>
                                            <div class="py-1 font-weight-bold text-dark">Ganador Llave H (<?php echo e($bLocalH); ?> / <?php echo e($bVisitH); ?>)</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-success">Cuarto 2</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i>22:00</small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="py-1 border-bottom font-weight-bold text-dark">Ganador Llave B (<?php echo e($bLocalB); ?> / <?php echo e($bVisitB); ?>)</div>
                                            <div class="py-1 font-weight-bold text-dark">Ganador Llave G (<?php echo e($bLocalG); ?> / <?php echo e($bVisitG); ?>)</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-success">Cuarto 3</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i>22:30</small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="py-1 border-bottom font-weight-bold text-dark">Ganador Llave C (<?php echo e($bLocalC); ?> / <?php echo e($bVisitC); ?>)</div>
                                            <div class="py-1 font-weight-bold text-dark">Ganador Llave F (<?php echo e($bLocalF); ?> / <?php echo e($bVisitF); ?>)</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100 border shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                            <span class="badge badge-success">Cuarto 4</span>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i>22:30</small>
                                        </div>
                                        <div class="card-body p-2 text-sm">
                                            <div class="py-1 border-bottom font-weight-bold text-dark">Ganador Llave D (<?php echo e($bLocalD); ?> / <?php echo e($bVisitD); ?>)</div>
                                            <div class="py-1 font-weight-bold text-dark">Ganador Llave E (<?php echo e($bLocalE); ?> / <?php echo e($bVisitE); ?>)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fase 3 y Fase Final (Semifinales + Gran Final + 3er Puesto) -->
                        <div class="row">
                            <!-- Semifinales -->
                            <div class="col-lg-6 mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <h6 class="font-weight-bold text-uppercase mb-0 text-info" style="letter-spacing: 0.5px;">
                                        <i class="fas fa-layer-group mr-1"></i> Fase 3 – Semifinales
                                    </h6>
                                    <span class="badge badge-info px-2 py-1">2 Encuentros</span>
                                </div>

                                <div class="card mb-3 border shadow-sm" style="border-radius: 10px;">
                                    <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                        <span class="badge badge-info">Semifinal 1</span>
                                        <small class="text-muted"><i class="far fa-clock mr-1"></i>23:00</small>
                                    </div>
                                    <div class="card-body p-2 text-sm">
                                        <div class="py-1 border-bottom font-weight-bold text-dark">Ganador Cuarto 1</div>
                                        <div class="py-1 font-weight-bold text-dark">Ganador Cuarto 4</div>
                                    </div>
                                </div>

                                <div class="card border shadow-sm" style="border-radius: 10px;">
                                    <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                        <span class="badge badge-info">Semifinal 2</span>
                                        <small class="text-muted"><i class="far fa-clock mr-1"></i>23:00</small>
                                    </div>
                                    <div class="card-body p-2 text-sm">
                                        <div class="py-1 border-bottom font-weight-bold text-dark">Ganador Cuarto 2</div>
                                        <div class="py-1 font-weight-bold text-dark">Ganador Cuarto 3</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fase Final -->
                            <div class="col-lg-6 mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <h6 class="font-weight-bold text-uppercase mb-0 text-warning" style="letter-spacing: 0.5px; color: #D97706 !important;">
                                        <i class="fas fa-trophy mr-1"></i> 🏆 Fase Final (Medallero)
                                    </h6>
                                    <span class="badge badge-warning text-dark px-2 py-1">Oro, Plata y Bronce</span>
                                </div>

                                <!-- Gran Final -->
                                <div class="card mb-3 border border-warning shadow-sm" style="border-radius: 10px; background: #FFFBEB;">
                                    <div class="card-header py-1 px-3 d-flex justify-content-between align-items-center border-bottom" style="background: #FEF3C7;">
                                        <span class="badge badge-warning text-dark font-weight-bold"><i class="fas fa-crown text-warning mr-1"></i> Gran Final (Oro y Plata)</span>
                                        <small class="text-dark font-weight-bold"><i class="far fa-clock mr-1"></i>23:30</small>
                                    </div>
                                    <div class="card-body p-2 text-sm">
                                        <div class="py-1 border-bottom font-weight-bold text-dark">Ganador Semifinal 1</div>
                                        <div class="py-1 font-weight-bold text-dark">Ganador Semifinal 2</div>
                                    </div>
                                </div>

                                <!-- 3er y 4to Lugar -->
                                <div class="card border shadow-sm" style="border-radius: 10px;">
                                    <div class="card-header bg-white py-1 px-3 d-flex justify-content-between align-items-center border-bottom">
                                        <span class="badge badge-secondary"><i class="fas fa-medal mr-1"></i> 3er y 4to Puesto (Bronce)</span>
                                        <small class="text-muted"><i class="far fa-clock mr-1"></i>23:30</small>
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
                    <div class="tab-pane fade" id="pills-esquema" role="tabpanel" aria-labelledby="pills-esquema-tab">
                        <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #0F172A; color: #38BDF8;">
                            <div class="card-header py-2 px-3 border-bottom d-flex justify-content-between align-items-center" style="background: #1E293B;">
                                <span class="font-weight-bold text-white text-sm">
                                    <i class="fas fa-project-diagram mr-2 text-warning"></i>Diagrama Oficial de la Eliminatoria Directa
                                </span>
                                <span class="badge badge-info">16 Parejas</span>
                            </div>
                            <div class="card-body p-3">
                                <pre class="mb-0 text-monospace text-light" style="font-size: 0.85rem; line-height: 1.45; overflow-x: auto; color: #38BDF8 !important;">
CLASIFICATORIOS           CUARTOS            SEMIFINALES        FINAL

A  <?php echo str_pad(mb_substr($bLocalA, 0, 10), 10); ?> vs <?php echo str_pad(mb_substr($bVisitA, 0, 10), 10); ?> ─┐
                          ├─ Cuarto 1 ─┐
H  <?php echo str_pad(mb_substr($bLocalH, 0, 10), 10); ?> vs <?php echo str_pad(mb_substr($bVisitH, 0, 10), 10); ?> ─┘            │
                                       ├─ Semi 1 ─┐
D  <?php echo str_pad(mb_substr($bLocalD, 0, 10), 10); ?> vs <?php echo str_pad(mb_substr($bVisitD, 0, 10), 10); ?> ┐           │          │
                           ├─ Cuarto 4 ┘          │
E  <?php echo str_pad(mb_substr($bLocalE, 0, 10), 10); ?> vs <?php echo str_pad(mb_substr($bVisitE, 0, 10), 10); ?> ─┘                      ├─ FINAL
                                                  │
B  <?php echo str_pad(mb_substr($bLocalB, 0, 10), 10); ?> vs <?php echo str_pad(mb_substr($bVisitB, 0, 10), 10); ?> ─┐                      │
                           ├─ Cuarto 2 ┐          │
G  <?php echo str_pad(mb_substr($bLocalG, 0, 10), 10); ?> vs <?php echo str_pad(mb_substr($bVisitG, 0, 10), 10); ?> ─┘           │          │
                                       ├─ Semi 2 ─┘
C  <?php echo str_pad(mb_substr($bLocalC, 0, 10), 10); ?> vs <?php echo str_pad(mb_substr($bVisitC, 0, 10), 10); ?> ─┐           │
                           ├─ Cuarto 3 ┘
F  <?php echo str_pad(mb_substr($bLocalF, 0, 10), 10); ?> vs <?php echo str_pad(mb_substr($bVisitF, 0, 10), 10); ?> ─┘

Perdedores de Semi 1 y Semi 2 → 3er y 4to puesto
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
                        <i class="fas fa-info-circle mr-1"></i> Los ganadores de cada llave avanzan automáticamente a la siguiente fase según programación oficial.
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