/**
 * Script de Inicialización del Mapa Interactivo de la Maratón (Olimpiada 33)
 * Circuito Urb. Celina Muyurina (Montero) - 5.0 km
 * Portal Informativo COSMOL Deportes
 * Compatible con navegadores modernos y móviles
 */

(function() {
    // Coordenadas oficiales de los 11 puntos de control del circuito WGS84 [lat, lng]
    // Coordenadas oficiales de los 7 puntos de control del circuito WGS84 [lat, lng]
    // Fuente: Docs/ruta_maraton.md (Olimpiada 33)
    var rutaCoords = (typeof window.rutaMaratonCoords !== 'undefined' && Array.isArray(window.rutaMaratonCoords))
        ? window.rutaMaratonCoords
        : [
            [-17.359770, -63.258670], // 1. Inicio (Partida - recta inicial al sureste)
            [-17.369881, -63.252018], // 2. Giro recta inicial (1328 m)
            [-17.364783, -63.243443], // 3. Esquina noreste (1072 m)
            [-17.364355, -63.243718], // 4. Giro retorno (56 m)
            [-17.369076, -63.251698], // 5. Fin retorno suroeste (996 m)
            [-17.368046, -63.252373], // 6. Giro recta final hacia meta (135 m)
            [-17.361681, -63.244658]  // 7. Meta oficial llegada (1082 m)
        ];

    // Checkpoints clave para destacar en el trazado
    var checkpoints = [
        { idx: 1, num: '2', label: 'Giro Recta Inicial', desc: '1.33 km hacia el sureste' },
        { idx: 2, num: '3', label: 'Esquina Noreste', desc: 'Conexión norte Urb. Celina' },
        { idx: 3, num: '4', label: 'Giro Retorno', desc: 'Inicio tramo de retorno' },
        { idx: 4, num: '5', label: 'Fin Retorno Suroeste', desc: 'Recta hacia sector oeste' },
        { idx: 5, num: '6', label: 'Giro Recta Final', desc: 'Enfilada final hacia la meta' }
    ];

    // Función auxiliar para inicializar una instancia de mapa en un contenedor dado
    function initCircuitoMap(containerId, options) {
        var el = document.getElementById(containerId);
        if (!el) return null;
        if (typeof L === 'undefined') {
            console.warn('Leaflet no está cargado para el contenedor: ' + containerId);
            return null;
        }

        // Si ya tenía mapa inicializado en este contenedor
        if (el._leaflet_id) {
            return null;
        }

        var capaCalles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>'
        });

        var capaSatelite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri &mdash; Fuente: Esri, Maxar, Earthstar Geographics'
        });

        var map = L.map(containerId, {
            center: [-17.3665, -63.2490],
            zoom: 15,
            layers: [capaCalles],
            scrollWheelZoom: false
        });

        map.on('focus', function() { map.scrollWheelZoom.enable(); });
        map.on('blur', function() { map.scrollWheelZoom.disable(); });

        var baseLayers = {
            "🗺️ Calles": capaCalles,
            "🛰️ Satélite Híbrido": capaSatelite
        };
        L.control.layers(baseLayers, null, { position: 'topright' }).addTo(map);

        // Borde blanco de alto contraste
        L.polyline(rutaCoords, {
            color: '#FFFFFF',
            weight: 8,
            opacity: 0.95,
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(map);

        // Línea principal de carrera (Azul COSMOL oficial)
        var lineaPrincipal = L.polyline(rutaCoords, {
            color: '#0066B2',
            weight: 5,
            opacity: 1,
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(map);

        // Icono de Partida
        var iconoInicio = L.divIcon({
            className: 'custom-leaflet-marker',
            html: '<div class="custom-map-pin pin-start"><i class="fas fa-play" style="font-size: 13px; margin-left: 2px;"></i></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18]
        });

        // Icono de Meta
        var iconoMeta = L.divIcon({
            className: 'custom-leaflet-marker',
            html: '<div class="custom-map-pin pin-finish"><i class="fas fa-flag-checkered" style="font-size: 14px;"></i></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18]
        });

        var markerInicio = L.marker(rutaCoords[0], { icon: iconoInicio }).addTo(map);
        markerInicio.bindPopup(
            '<div style="min-width: 220px;">' +
            '<div style="font-weight: 800; color: #059669; font-size: 1.05rem; margin-bottom: 4px;">' +
            '<i class="fas fa-play-circle mr-1"></i> Partida Oficial (Largada)' +
            '</div>' +
            '<div style="font-size: 0.85rem; color: #334155; margin-bottom: 6px;">' +
            'Urb. Celina Muyurina - Inicio de la recta hacia el sureste.' +
            '</div>' +
            '<div style="font-size: 0.75rem; color: #64748B; margin-bottom: 8px;">' +
            'Coordenadas: -17.359770, -63.258670' +
            '</div>' +
            '<button type="button" class="btn btn-sm btn-success btn-block font-weight-bold" onclick="abrirRutaGps(-17.359770, -63.258670, \'Partida Maratón Olimpiada 33\')">' +
            '<i class="fas fa-location-arrow mr-1"></i> Cómo llegar (GPS)' +
            '</button>' +
            '</div>'
        );

        var markerMeta = L.marker(rutaCoords[rutaCoords.length - 1], { icon: iconoMeta }).addTo(map);
        markerMeta.bindPopup(
            '<div style="min-width: 220px;">' +
            '<div style="font-weight: 800; color: #DC2626; font-size: 1.05rem; margin-bottom: 4px;">' +
            '<i class="fas fa-flag-checkered mr-1"></i> Meta Oficial (Llegada)' +
            '</div>' +
            '<div style="font-size: 0.85rem; color: #334155; margin-bottom: 6px;">' +
            'Llegada de la Maratón 5.0 km - Urb. Celina Muyurina.' +
            '</div>' +
            '<div style="font-size: 0.75rem; color: #64748B; margin-bottom: 8px;">' +
            'Coordenadas: -17.361681, -63.244658' +
            '</div>' +
            '<button type="button" class="btn btn-sm btn-danger btn-block font-weight-bold" onclick="abrirRutaGps(-17.361681, -63.244658, \'Meta Maratón Olimpiada 33\')">' +
            '<i class="fas fa-location-arrow mr-1"></i> Cómo llegar (GPS)' +
            '</button>' +
            '</div>'
        );

        // Checkpoints intermedios
        checkpoints.forEach(function(cp) {
            var coord = rutaCoords[cp.idx];
            var iconCp = L.divIcon({
                className: 'custom-leaflet-marker',
                html: '<div class="pin-checkpoint">' + cp.num + '</div>',
                iconSize: [22, 22],
                iconAnchor: [11, 11],
                popupAnchor: [0, -11]
            });
            var mk = L.marker(coord, { icon: iconCp }).addTo(map);
            mk.bindPopup(
                '<div style="font-size: 0.85rem;">' +
                '<strong>Punto #' + cp.num + ': ' + cp.label + '</strong><br>' +
                '<span class="text-muted">' + cp.desc + '</span>' +
                '</div>'
            );
        });

        // Ajustar vista a los límites de la ruta
        map.fitBounds(lineaPrincipal.getBounds(), { padding: [35, 35] });

        // Asociar botones si se especificaron prefijos
        if (options && options.btnFitId) {
            var btnFit = document.getElementById(options.btnFitId);
            if (btnFit) {
                btnFit.addEventListener('click', function() {
                    map.fitBounds(lineaPrincipal.getBounds(), { padding: [35, 35] });
                });
            }
        }
        if (options && options.btnStartId) {
            var btnStart = document.getElementById(options.btnStartId);
            if (btnStart) {
                btnStart.addEventListener('click', function() {
                    map.setView(rutaCoords[0], 17);
                    markerInicio.openPopup();
                });
            }
        }
        if (options && options.btnFinishId) {
            var btnFinish = document.getElementById(options.btnFinishId);
            if (btnFinish) {
                btnFinish.addEventListener('click', function() {
                    map.setView(rutaCoords[rutaCoords.length - 1], 17);
                    markerMeta.openPopup();
                });
            }
        }

        return {
            map: map,
            linea: lineaPrincipal,
            markerInicio: markerInicio,
            markerMeta: markerMeta
        };
    }

    // Inicialización al cargar el DOM
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Inicializar mapa de la sección principal en la portada (#mapa-maraton)
        var mapPrincipal = initCircuitoMap('mapa-maraton', {
            btnFitId: 'btn-fit-map',
            btnStartId: 'btn-goto-start',
            btnFinishId: 'btn-goto-finish'
        });

        // 2. Control del Modal del Fixture (#modalMapaMaraton)
        var modalMaraton = $('#modalMapaMaraton');
        var mapModalInstance = null;

        if (modalMaraton.length) {
            modalMaraton.on('shown.bs.modal', function() {
                if (!mapModalInstance) {
                    mapModalInstance = initCircuitoMap('mapa-maraton-modal', {
                        btnFitId: 'btn-modal-fit-map',
                        btnStartId: 'btn-modal-goto-start',
                        btnFinishId: 'btn-modal-goto-finish'
                    });
                }
                if (mapModalInstance && mapModalInstance.map) {
                    mapModalInstance.map.invalidateSize();
                    mapModalInstance.map.fitBounds(mapModalInstance.linea.getBounds(), { padding: [25, 25] });
                }
            });
        }

        // Redimensionar mapas si cambia la pantalla
        window.addEventListener('resize', function() {
            if (mapPrincipal && mapPrincipal.map) {
                mapPrincipal.map.invalidateSize();
            }
            if (mapModalInstance && mapModalInstance.map) {
                mapModalInstance.map.invalidateSize();
            }
        });
    });

    // Exponer función global para abrir el modal o enfocar mapa
    window.CosmolMaraton = {
        coords: rutaCoords,
        initMap: initCircuitoMap
    };
})();
