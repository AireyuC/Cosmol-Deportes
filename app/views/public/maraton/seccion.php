<?php
/**
 * Sección Oficial: Ruta Maratón Olimpiada 33 (Circuito Urb. Celina Muyurina - 5.0 km)
 * Portal Informativo COSMOL Deportes
 * Trazado Oficial de 7 Puntos de Control WGS84
 * Compatible con PHP 7.3
 */

// Coordenadas oficiales de los 7 puntos de control
$puntosMaraton = [
    [
        'numero'      => 1,
        'nombre'      => 'Partida Oficial (Inicio)',
        'descripcion' => 'Recta inicial hacia el sureste',
        'latitud'     => -17.359770,
        'longitud'    => -63.258670,
        'es_hito'     => 'inicio'
    ],
    [
        'numero'      => 2,
        'nombre'      => 'Giro Recta Inicial',
        'descripcion' => '1.33 km hacia el sureste',
        'latitud'     => -17.369881,
        'longitud'    => -63.252018,
        'es_hito'     => 'giro'
    ],
    [
        'numero'      => 3,
        'nombre'      => 'Esquina Noreste',
        'descripcion' => '1.07 km tramo norte Urb. Celina',
        'latitud'     => -17.364783,
        'longitud'    => -63.243443,
        'es_hito'     => 'giro'
    ],
    [
        'numero'      => 4,
        'nombre'      => 'Giro Retorno',
        'descripcion' => '56 m inicio de retorno',
        'latitud'     => -17.364355,
        'longitud'    => -63.243718,
        'es_hito'     => 'giro'
    ],
    [
        'numero'      => 5,
        'nombre'      => 'Fin Retorno Suroeste',
        'descripcion' => '996 m hacia sector oeste',
        'latitud'     => -17.369076,
        'longitud'    => -63.251698,
        'es_hito'     => 'giro'
    ],
    [
        'numero'      => 6,
        'nombre'      => 'Giro Recta Final',
        'descripcion' => '135 m enfilada final',
        'latitud'     => -17.368046,
        'longitud'    => -63.252373,
        'es_hito'     => 'giro'
    ],
    [
        'numero'      => 7,
        'nombre'      => 'Meta Oficial (Llegada)',
        'descripcion' => '1.08 km recta final a la llegada',
        'latitud'     => -17.361681,
        'longitud'    => -63.244658,
        'es_hito'     => 'meta'
    ]
];
?>
<!-- SECCIÓN OFICIAL: RUTA DE LA MARATÓN -->
<section id="maraton" class="py-5" style="background-color: #F8FAFC; border-top: 1px solid var(--cosmol-borde); border-bottom: 1px solid var(--cosmol-borde);">
    <div class="container">
        <!-- Encabezado de la Sección -->
        <div class="text-center mb-4">
            <h2 class="font-weight-bold mt-2 mb-1" style="color: var(--cosmol-azul-deep); font-size: 2.1rem;">
                Ruta Oficial: Maratón 5.0 km
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">
                Circuito en la Urbanización Celina Muyurina (Montero)
            </p>
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
                    <div class="font-weight-bold my-1" style="font-size: 1.25rem; color: #166534;">Punto 1 (Partida)</div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-3">
                <div class="card border shadow-sm text-center p-3 h-100" style="border-radius: 12px; background: #FFFFFF; border-color: #E2E8F0 !important;">
                    <div class="text-muted text-xs text-uppercase font-weight-bold">Punto de Llegada</div>
                    <div class="font-weight-bold my-1" style="font-size: 1.25rem; color: #991B1B;">Punto 7 (Meta)</div>
                </div>
            </div>
        </div>

        <!-- Contenedor Principal: Mapa Interactivo a Pantalla Completa -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card border shadow-sm h-100" style="border-radius: 16px; overflow: hidden; border-color: #CBD5E1 !important;">
                    <!-- Barra Superior de Control del Mapa -->
                    <div class="card-header bg-white py-3 px-3 d-flex flex-wrap justify-content-between align-items-center border-bottom">
                        <!-- Botones Rápidos del Mapa -->
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary font-weight-bold" id="btn-fit-map" title="Ver todo el circuito">
                                Ver Todo
                            </button>
                            <button type="button" class="btn btn-outline-success font-weight-bold" id="btn-goto-start" title="Enfocar en la largada">
                                Partida (Punto 1)
                            </button>
                            <button type="button" class="btn btn-outline-danger font-weight-bold" id="btn-goto-finish" title="Enfocar en la meta">
                                Meta (Punto 7)
                            </button>
                        </div>
                    </div>

                    <!-- Div del Mapa Leaflet -->
                    <div class="position-relative">
                        <div id="mapa-maraton" style="height: 440px; width: 100%; background-color: #E2E8F0; z-index: 1;"></div>
                        <!-- Indicador flotante en la esquina del mapa -->
                        <div class="position-absolute" style="bottom: 15px; left: 15px; z-index: 400; background: rgba(255, 255, 255, 0.95); padding: 6px 12px; border-radius: 8px; border: 1px solid #CBD5E1; font-size: 0.78rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                            <span class="d-inline-block mr-2" style="width: 14px; height: 4px; background: #0066B2; vertical-align: middle; border-radius: 2px;"></span>
                            <strong style="color: #0F172A;">Ruta Oficial Actualizada • 7 Puntos de Giro</strong>
                        </div>
                    </div>

                    <!-- Footer del Mapa con Atajos GPS -->
                    <div class="card-footer bg-light p-3">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-2 mb-md-0 text-center text-md-left">
                                <small class="text-muted d-block d-sm-inline mt-1 mt-sm-0">
                                    Toque cualquier marcador numérico para ver detalles del tramo y coordenadas.
                                </small>
                            </div>
                            <div class="col-md-6 text-center text-md-right">
                                <button type="button" class="btn btn-sm btn-outline-success font-weight-bold mr-1" onclick="abrirRutaGps(-17.359770, -63.258670, 'Partida Maratón Olimpiada 33 - Urb. Celina')">
                                    <i class="fas fa-location-arrow mr-1"></i> GPS Partida
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold mr-1" onclick="abrirRutaGps(-17.361681, -63.244658, 'Meta Maratón Olimpiada 33 - Urb. Celina')">
                                    <i class="fas fa-location-arrow mr-1"></i> GPS Meta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tira Interactiva de los 7 Puntos Oficiales -->
        <div class="card border shadow-sm p-3 bg-white" style="border-radius: 12px; border-color: #E2E8F0 !important;">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                <div>
                    <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 0.95rem;">
                        Puntos de Control del Circuito
                    </h6>
                    <small class="text-muted">Haga clic en cualquiera de los puntos para centrar el mapa y visualizar el tramo.</small>
                </div>
                <span class="badge badge-light border text-primary font-weight-bold px-2 py-1">
                    Total: 7 Puntos WGS84
                </span>
            </div>

            <div class="row">
                <?php foreach ($puntosMaraton as $pt): 
                    $badgeStyle = 'background: #0066B2; color: #FFFFFF;';
                    if ($pt['es_hito'] === 'inicio') {
                        $badgeStyle = 'background: #10B981; color: #FFFFFF;';
                    } elseif ($pt['es_hito'] === 'meta') {
                        $badgeStyle = 'background: #EF4444; color: #FFFFFF;';
                    }
                ?>
                    <div class="col-6 col-md-4 col-lg-3 mb-2">
                        <button type="button" class="btn btn-light btn-block text-left p-2 border btn-checkpoint-focus" style="border-radius: 8px; font-size: 0.8rem; background: #FFFFFF;" onclick="centrarEnPunto(<?php echo $pt['numero'] - 1; ?>)">
                            <div class="d-flex align-items-center">
                                <span class="d-inline-flex align-items-center justify-content-center mr-2 font-weight-bold" style="width: 24px; height: 24px; border-radius: 50%; <?php echo $badgeStyle; ?> font-size: 11px;">
                                    <?php echo $pt['numero']; ?>
                                </span>
                                <div class="text-truncate">
                                    <strong class="d-block text-truncate text-dark" style="font-size: 0.82rem;">
                                        <?php echo e($pt['nombre']); ?>
                                    </strong>
                                    <span class="text-muted text-xs d-block text-truncate">
                                        <?php echo e($pt['latitud']); ?>, <?php echo e($pt['longitud']); ?>
                                    </span>
                                </div>
                            </div>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Script Autónomo de Inicialización y Trazado Directo de la Ruta -->
<script>
(function() {
    // 7 Puntos oficiales de control WGS84 [lat, lng]
    var rutaCoords = [
        [-17.359770, -63.258670], // Punto 1: Inicio (Partida)
        [-17.369881, -63.252018], // Punto 2: Giro recta inicial (1328 m)
        [-17.364783, -63.243443], // Punto 3: Esquina noreste (1072 m)
        [-17.364355, -63.243718], // Punto 4: Giro retorno (56 m)
        [-17.369076, -63.251698], // Punto 5: Fin retorno suroeste (996 m)
        [-17.368046, -63.252373], // Punto 6: Giro recta final hacia meta (135 m)
        [-17.361681, -63.244658]  // Punto 7: Meta oficial llegada (1082 m)
    ];

    window.rutaMaratonCoords = rutaCoords;
    window._maratonMarkers = [];

    function renderMapaPrincipal() {
        var el = document.getElementById('mapa-maraton');
        if (!el || typeof L === 'undefined') return;

        // Limpiar instancia previa si existe para evitar conflictos de inicialización
        if (el._leaflet_id) {
            try {
                if (window._instanciaMapaMaraton) {
                    window._instanciaMapaMaraton.remove();
                }
            } catch(e) {}
            el._leaflet_id = null;
            el.innerHTML = '';
        }

        var capaCalles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        });

        var capaSatelite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri'
        });

        var map = L.map('mapa-maraton', {
            center: [-17.3655, -63.2510],
            zoom: 15,
            layers: [capaCalles],
            scrollWheelZoom: false
        });
        window._instanciaMapaMaraton = map;

        map.on('focus', function() { map.scrollWheelZoom.enable(); });
        map.on('blur', function() { map.scrollWheelZoom.disable(); });

        L.control.layers({
            "🗺️ Calles": capaCalles,
            "🛰️ Satélite Híbrido": capaSatelite
        }, null, { position: 'topright' }).addTo(map);

        // 1. Trazado borde blanco de alto contraste
        L.polyline(rutaCoords, {
            color: '#FFFFFF',
            weight: 8,
            opacity: 0.95,
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(map);

        // 2. Trazado línea principal de carrera (Azul COSMOL oficial)
        var lineaRuta = L.polyline(rutaCoords, {
            color: '#0066B2',
            weight: 5,
            opacity: 1,
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(map);

        // Marcador Punto 1: Partida
        var iconoInicio = L.divIcon({
            className: 'custom-leaflet-marker',
            html: '<div class="custom-map-pin pin-start"><i class="fas fa-play" style="font-size: 13px; margin-left: 2px;"></i></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18]
        });

        var markerInicio = L.marker(rutaCoords[0], { icon: iconoInicio }).addTo(map);
        markerInicio.bindPopup(
            '<div style="min-width: 210px;">' +
            '<div style="font-weight: 800; color: #059669; font-size: 1.05rem; margin-bottom: 4px;">Punto 1: Partida Oficial (Largada)</div>' +
            '<div style="font-size: 0.82rem; color: #334155; margin-bottom: 6px;">Urb. Celina Muyurina - Inicio de la recta hacia el sureste.</div>' +
            '<div style="font-size: 0.75rem; color: #64748B; margin-bottom: 8px;">Coordenadas: -17.359770, -63.258670</div>' +
            '<button type="button" class="btn btn-sm btn-success btn-block font-weight-bold" onclick="abrirRutaGps(-17.359770, -63.258670, \'Partida Maratón Olimpiada 33\')">' +
            '<i class="fas fa-location-arrow mr-1"></i> Cómo llegar (GPS)</button>' +
            '</div>'
        );
        window._maratonMarkers[0] = markerInicio;

        // Marcadores Puntos 2 al 6
        var checkpoints = [
            { idx: 1, num: '2', label: 'Punto 2: Giro Recta Inicial', desc: '1.33 km recorridos hacia el sureste' },
            { idx: 2, num: '3', label: 'Punto 3: Esquina Noreste', desc: '1.07 km tramo norte Urb. Celina' },
            { idx: 3, num: '4', label: 'Punto 4: Giro Retorno', desc: '56 m giro retorno suroeste' },
            { idx: 4, num: '5', label: 'Punto 5: Fin Retorno Suroeste', desc: '996 m recta hacia sector oeste' },
            { idx: 5, num: '6', label: 'Punto 6: Giro Recta Final', desc: '135 m enfilada final a la meta' }
        ];

        checkpoints.forEach(function(cp) {
            var iconCp = L.divIcon({
                className: 'custom-leaflet-marker',
                html: '<div class="pin-checkpoint">' + cp.num + '</div>',
                iconSize: [24, 24],
                iconAnchor: [12, 12],
                popupAnchor: [0, -12]
            });
            var mk = L.marker(rutaCoords[cp.idx], { icon: iconCp }).addTo(map);
            mk.bindPopup(
                '<div style="font-size: 0.85rem;">' +
                '<strong>' + cp.label + '</strong><br>' +
                '<span class="text-muted">' + cp.desc + '</span><br>' +
                '<small style="color: #64748B;">' + rutaCoords[cp.idx][0] + ', ' + rutaCoords[cp.idx][1] + '</small>' +
                '</div>'
            );
            window._maratonMarkers[cp.idx] = mk;
        });

        // Marcador Punto 7: Meta
        var iconoMeta = L.divIcon({
            className: 'custom-leaflet-marker',
            html: '<div class="custom-map-pin pin-finish"><i class="fas fa-flag-checkered" style="font-size: 14px;"></i></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18]
        });

        var markerMeta = L.marker(rutaCoords[6], { icon: iconoMeta }).addTo(map);
        markerMeta.bindPopup(
            '<div style="min-width: 210px;">' +
            '<div style="font-weight: 800; color: #DC2626; font-size: 1.05rem; margin-bottom: 4px;">Punto 7: Meta Oficial (Llegada)</div>' +
            '<div style="font-size: 0.82rem; color: #334155; margin-bottom: 6px;">Llegada de la Maratón 5.0 km - Urb. Celina Muyurina.</div>' +
            '<div style="font-size: 0.75rem; color: #64748B; margin-bottom: 8px;">Coordenadas: -17.361681, -63.244658</div>' +
            '<button type="button" class="btn btn-sm btn-danger btn-block font-weight-bold" onclick="abrirRutaGps(-17.361681, -63.244658, \'Meta Maratón Olimpiada 33\')">' +
            '<i class="fas fa-location-arrow mr-1"></i> Cómo llegar (GPS)</button>' +
            '</div>'
        );
        window._maratonMarkers[6] = markerMeta;

        // Encuadrar el trazado completo con margen adecuado
        map.fitBounds(lineaRuta.getBounds(), { padding: [35, 35] });

        // Conectar botones de control
        var btnFit = document.getElementById('btn-fit-map');
        if (btnFit) {
            btnFit.onclick = function() {
                map.fitBounds(lineaRuta.getBounds(), { padding: [35, 35] });
            };
        }
        var btnStart = document.getElementById('btn-goto-start');
        if (btnStart) {
            btnStart.onclick = function() {
                map.setView(rutaCoords[0], 17);
                markerInicio.openPopup();
            };
        }
        var btnFinish = document.getElementById('btn-goto-finish');
        if (btnFinish) {
            btnFinish.onclick = function() {
                map.setView(rutaCoords[6], 17);
                markerMeta.openPopup();
            };
        }

        setTimeout(function() {
            map.invalidateSize();
        }, 250);
    }

    // Centrar en cualquier punto específico desde la lista interactiva
    window.centrarEnPunto = function(indice) {
        if (!window._instanciaMapaMaraton || !rutaCoords[indice]) return;
        window._instanciaMapaMaraton.setView(rutaCoords[indice], 17);
        if (window._maratonMarkers[indice]) {
            window._maratonMarkers[indice].openPopup();
        }
        // Desplazar suavemente hacia el mapa
        var mapElem = document.getElementById('mapa-maraton');
        if (mapElem) {
            mapElem.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };

    // Polling hasta que Leaflet esté disponible en el navegador
    function cargarCuandoListo() {
        if (typeof L !== 'undefined') {
            renderMapaPrincipal();
        } else {
            setTimeout(cargarCuandoListo, 100);
        }
    }

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        cargarCuandoListo();
    } else {
        document.addEventListener('DOMContentLoaded', cargarCuandoListo);
    }
})();
</script>
