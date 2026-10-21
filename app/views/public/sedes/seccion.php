<?php
/**
 * Sección: Sedes Deportivas y Rutas GPS ("Cómo llegar")
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Compatible con PHP 7.3
 */
?>
<!-- 7. SECCIÓN: SEDES DEPORTIVAS Y "CÓMO LLEGAR" -->
<section id="sedes" class="container py-5">
    <div class="text-center mb-5">
        <h2 class="font-weight-bold mt-2 mb-1" style="color: #002D52;">
            Sedes Deportivas y Cómo Llegar
        </h2>
        <p class="text-muted mx-auto mb-0" style="max-width: 650px; font-size: 0.95rem;">
            Presione <strong>"Cómo llegar"</strong> para abrir la ruta en tiempo real desde su ubicación actual vía Google Maps.
        </p>
    </div>

    <div class="row">
        <?php if (empty($sedes)): ?>
            <div class="col-12 text-center py-5">
                <div class="p-4 bg-white rounded shadow-sm border mx-auto" style="max-width: 500px;">
                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                    <h5 class="font-weight-bold text-dark">No hay sedes registradas aún</h5>
                    <p class="text-muted text-sm mb-0">El comité organizador publicará las sedes y campos deportivos oficiales próximamente.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($sedes as $s): ?>
                <div class="col-md-6 mb-4">
                    <div class="venue-card h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="venue-header d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="font-weight-bold text-dark mb-1" style="font-size: 1.1rem;"><?php echo e($s['nombre']); ?></h5>
                                    <span class="text-muted text-sm d-block">
                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i> <?php echo e($s['direccion']); ?>
                                    </span>
                                </div>
                                <span class="badge font-weight-bold px-2 py-1 ml-2 flex-shrink-0" style="background-color: #EBF4FA; color: #0066B2; border: 1px solid #C9E2F5; font-size: 0.78rem;">
                                    <?php echo count(isset($s['canchas']) ? $s['canchas'] : []); ?> Canchas
                                </span>
                            </div>

                            <div class="p-3">
                                <!-- Canchas en esta sede -->
                                <span class="font-weight-bold text-xs text-uppercase text-muted d-block mb-2">Campos de Juego:</span>
                                <div class="mb-3">
                                    <?php if (!empty($s['canchas'])): ?>
                                        <?php foreach ($s['canchas'] as $c): ?>
                                            <div class="py-1 text-sm border-bottom d-flex justify-content-between align-items-center">
                                                <span class="font-weight-bold text-dark">
                                                    <i class="fas fa-circle mr-1" style="font-size: 6px; color: #0066B2; vertical-align: middle;"></i>
                                                    <?php echo e($c['nombre_cancha']); ?>
                                                </span>
                                                <span class="badge" style="background-color: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; font-size: 0.72rem;">
                                                    <?php echo e($c['tipo_superficie']); ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-muted text-sm py-1">Canchas oficiales del complejo.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="px-3 pb-3">
                            <!-- Botón GPS Cómo Llegar -->
                            <?php 
                                $lat = !empty($s['latitud']) ? (float)$s['latitud'] : -17.7833;
                                $lng = !empty($s['longitud']) ? (float)$s['longitud'] : -63.1821;
                            ?>
                            <button type="button" class="btn btn-gps btn-block font-weight-bold" onclick="abrirRutaGps(<?php echo $lat; ?>, <?php echo $lng; ?>, '<?php echo addslashes($s['nombre']); ?>')">
                                <i class="fas fa-location-arrow mr-1"></i> Cómo llegar (GPS)
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
