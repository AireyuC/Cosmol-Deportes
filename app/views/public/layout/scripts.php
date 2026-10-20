<?php
/**
 * Scripts JavaScript del Portal Público
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Compatible con PHP 7.3
 */
?>
<!-- Bootstrap 4 Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script de Navegación GPS ("Cómo llegar") -->
<script>
    function abrirRutaGps(latDestino, lngDestino, nombreSede) {
        if (!latDestino || !lngDestino) {
            alert('No se encontraron coordenadas configuradas para la sede "' + nombreSede + '".');
            return;
        }

        // Verificar si el navegador soporta geolocalización
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var userLat = position.coords.latitude;
                    var userLng = position.coords.longitude;
                    // Abrir Google Maps con la ruta desde la ubicación actual del usuario hacia la sede
                    var url = "https://www.google.com/maps/dir/?api=1&origin=" + userLat + "," + userLng + "&destination=" + latDestino + "," + lngDestino + "&travelmode=driving";
                    window.open(url, "_blank");
                },
                function(error) {
                    // Si el usuario deniega o falla la geolocalización, abrir directamente las coordenadas de destino
                    var fallbackUrl = "https://www.google.com/maps/search/?api=1&query=" + latDestino + "," + lngDestino;
                    window.open(fallbackUrl, "_blank");
                },
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
            );
        } else {
            // Fallback sin geolocalización en navegador
            var url = "https://www.google.com/maps/search/?api=1&query=" + latDestino + "," + lngDestino;
            window.open(url, "_blank");
        }
    }

    // Preservación y control de scroll al recargar (F5)
    $(document).ready(function() {
        // Activar restauración nativa de scroll del navegador
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'auto';
        }

        // Si la página se abrió con un ancla (#posiciones, #fixture, etc.), scroll inicial y limpiar el hash
        // para que futuras recargas mantengan la posición exacta donde el usuario se encuentre navegando
        if (window.location.hash) {
            var targetHash = window.location.hash;
            var $targetElem = $(targetHash);
            if ($targetElem.length) {
                $('html, body').scrollTop($targetElem.offset().top - 70);
            }
            if (window.history && window.history.replaceState) {
                history.replaceState(null, document.title, window.location.pathname + window.location.search);
            }
        }

        // Navegación suave en enlaces de ancla del menú superior (#fixture, #posiciones, etc.)
        // manteniendo la URL limpia sin hash para no romper la posición de scroll al recargar
        $('a[href^="#"]').on('click', function(e) {
            var targetId = $(this).attr('href');
            // Ignorar tabs y pills de Bootstrap (data-toggle="pill" o "tab")
            if ($(this).attr('data-toggle') === 'pill' || $(this).attr('data-toggle') === 'tab' || $(this).attr('data-toggle') === 'collapse') {
                return;
            }

            if (targetId && targetId !== '#' && targetId.length > 1) {
                var $target = $(targetId);
                if ($target.length) {
                    e.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: $target.offset().top - 70
                    }, 350);

                    // Mantener la URL limpia sin hash
                    if (window.history && window.history.replaceState) {
                        history.replaceState(null, document.title, window.location.pathname + window.location.search);
                    }
                }
            }
        });

        // Filtrado de disciplinas
        $('.sport-filter-btn').on('click', function() {
            var filter = $(this).data('filter');
            $('.sport-filter-btn').removeClass('active');
            $(this).addClass('active');

            if (filter === 'all') {
                $('.sport-col').fadeIn(200);
            } else {
                $('.sport-col').each(function() {
                    if ($(this).data('category') === filter) {
                        $(this).fadeIn(200);
                    } else {
                        $(this).fadeOut(150);
                    }
                });
            }
        });
    });
</script>

<!-- Leaflet JS y Controlador del Mapa de la Media Maratón -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="<?php echo BASE_URL; ?>/js/maraton-mapa.js"></script>
