<?php
/**
 * Submódulo: Scripts JavaScript de Fixture
 * Maneja navegación GPS, Leaflet Modal y filtrado interactivo por día
 * Compatible con PHP 7.3
 */
?>
<!-- Bootstrap 4 Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Leaflet JS y Controlador del Mapa de la Maratón -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="<?php echo BASE_URL; ?>/js/maraton-mapa.js"></script>

<!-- Script de Navegación GPS e Interactividad de Filtros -->
<script>
    function abrirRutaGps(latDestino, lngDestino, nombreSede) {
        if (!latDestino || !lngDestino) {
            alert('No se encontraron coordenadas configuradas para la sede "' + nombreSede + '".');
            return;
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var userLat = position.coords.latitude;
                    var userLng = position.coords.longitude;
                    var url = "https://www.google.com/maps/dir/?api=1&origin=" + userLat + "," + userLng + "&destination=" + latDestino + "," + lngDestino + "&travelmode=driving";
                    window.open(url, "_blank");
                },
                function(error) {
                    var fallbackUrl = "https://www.google.com/maps/search/?api=1&query=" + latDestino + "," + lngDestino;
                    window.open(fallbackUrl, "_blank");
                },
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
            );
        } else {
            var url = "https://www.google.com/maps/search/?api=1&query=" + latDestino + "," + lngDestino;
            window.open(url, "_blank");
        }
    }

    function aplicarFiltroDia(dia) {
        // 1. Actualizar estado visual de los botones
        $('.match-day-btn').removeClass('active');
        $('.match-day-btn[data-day-filter="' + dia + '"]').addClass('active');

        // 2. Ocultar todos los avisos de días vacíos
        $('.match-empty-state').addClass('d-none');

        var totalVisibles = 0;

        // Manejo limpio de la tarjeta informativa de Maratón según filtro de día
        var $maratonCard = $('.maraton-fixture-card');
        if ($maratonCard.length > 0) {
            var selectDiscVal = $('select[name="disciplina_id"]').val();
            var esFiltroMaratonExplicito = (selectDiscVal == '<?php echo $maratonDiscId; ?>');
            if (dia === 'todos' || esFiltroMaratonExplicito) {
                $maratonCard.removeClass('d-none');
                totalVisibles++;
            } else {
                $maratonCard.addClass('d-none');
            }
        }

        // 3. Evaluar cada disciplina y sus respectivos partidos
        $('.discipline-section').each(function() {
            var $seccion = $(this);
            var countEnSeccion = 0;

            $seccion.find('.match-card-item').each(function() {
                var $card = $(this);
                var matchDay = $card.attr('data-match-day');

                if (dia === 'todos' || matchDay === dia) {
                    $card.removeClass('d-none');
                    countEnSeccion++;
                    totalVisibles++;
                } else {
                    $card.addClass('d-none');
                }
            });

            // Actualizar contador visible en la cabecera de la card
            $seccion.find('.discipline-count-visible').text(countEnSeccion);

            // Si la disciplina tiene al menos un partido visible para el día, mostrar la sección; sino ocultarla
            if (countEnSeccion > 0) {
                $seccion.removeClass('d-none');
            } else {
                $seccion.addClass('d-none');
            }
        });

        // 4. Si ningún partido de ninguna disciplina coincide con el filtro, mostrar el aviso correspondiente
        if (totalVisibles === 0) {
            if (dia === 'todos') {
                $('#empty-todos').removeClass('d-none');
            } else {
                $('#empty-' + dia).removeClass('d-none');
            }
        }
    }

    $(document).ready(function() {
        // Evento click en botones de día (Ayer, Hoy, Mañana, Todos)
        $('.match-day-btn').on('click', function(e) {
            e.preventDefault();
            var day = $(this).attr('data-day-filter');
            aplicarFiltroDia(day);
        });

        // Si se pasa un filtro por URL (?dia=ayer, ?dia=hoy, ?dia=manana)
        var urlParams = new URLSearchParams(window.location.search);
        var diaParam = urlParams.get('dia');
        if (diaParam && ['ayer', 'hoy', 'manana', 'todos'].indexOf(diaParam) !== -1) {
            aplicarFiltroDia(diaParam);
        }
    });
</script>
