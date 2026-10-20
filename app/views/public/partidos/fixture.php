<?php
/**
 * Vista Pública Oficial: Fixture y Calendario Deportivo COSMOL
 * Arquitectura modular segmentada por componentes:
 * - fixture/filtros.php: Barra unificada de filtros (Días sin contadores + Selector de disciplinas)
 * - fixture/card_maraton.php: Tarjeta de la disciplina Maratón (Circuito oficial 5.0 km)
 * - fixture/lista_disciplinas.php: Tarjetas de disciplinas con listado ordenado de encuentros y estados vacíos
 * - fixture/modal_maraton.php: Modal interactivo con visor satelital Leaflet y GPS
 * - fixture/scripts.php: Lógica JavaScript de filtros, interactividad y navegación GPS
 *
 * Compatible con PHP 7.3
 */

// Identificar el ID de la disciplina Maratón
$maratonDiscId = null;
foreach ($disciplinas as $dItem) {
    $nDisc = mb_strtolower(trim($dItem['nombre']), 'UTF-8');
    if (strpos($nDisc, 'maratón') !== false || strpos($nDisc, 'maraton') !== false || strpos($nDisc, 'atletismo') !== false) {
        $maratonDiscId = (int)$dItem['id'];
        break;
    }
}
$esFiltroMaraton = ($disciplinaFiltro && $disciplinaFiltro === $maratonDiscId);
$mostrarMaraton = (!$disciplinaFiltro || $esFiltroMaraton);

if (!function_exists('obtenerIconoDisciplinaFixture')) {
    function obtenerIconoDisciplinaFixture($nombre) {
        $n = mb_strtolower(trim($nombre), 'UTF-8');
        if (strpos($n, 'futbol') !== false || strpos($n, 'fútbol') !== false) return 'fas fa-futbol';
        if (strpos($n, 'futsal') !== false) return 'fas fa-running';
        if (strpos($n, 'basquet') !== false || strpos($n, 'básquet') !== false) return 'fas fa-basketball-ball';
        if (strpos($n, 'voley') !== false || strpos($n, 'voleibol') !== false) return 'fas fa-volleyball-ball';
        if (strpos($n, 'marat') !== false || strpos($n, 'atletismo') !== false) return 'fas fa-running';
        if (strpos($n, 'karaoke') !== false) return 'fas fa-microphone-alt';
        if (strpos($n, 'billar') !== false) return 'fas fa-dot-circle';
        if (strpos($n, 'cacho') !== false) return 'fas fa-dice';
        return 'fas fa-trophy';
    }
}

if (!function_exists('formatearFechaEspanolFixture')) {
    function formatearFechaEspanolFixture($fechaStr) {
        if (empty($fechaStr)) return '--/--/----';
        $dias = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $timestamp = strtotime($fechaStr);
        if (!$timestamp) return $fechaStr;
        $diaSemana = $dias[(int)date('w', $timestamp)];
        $diaNum = date('d', $timestamp);
        $mesNom = $meses[(int)date('n', $timestamp) - 1];
        $anio = date('Y', $timestamp);
        return $diaSemana . ', ' . $diaNum . ' ' . $mesNom . ' ' . $anio;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Fixture Deportivo | COSMOL'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/logoSinFondo.png">
    <!-- Leaflet CSS para el Mapa de la Maratón -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <!-- Estilos Oficiales del Portal Público -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/portal.css?v=<?php echo defined('APP_VERSION') ? APP_VERSION : '1.0.0'; ?>">
</head>
<body>
    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-public sticky-top">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-primary" href="<?php echo BASE_URL; ?>/">
                <img src="<?php echo BASE_URL; ?>/images/logoSinFondo.png" height="38" class="mr-2" alt="COSMOL">
                COSMOL DEPORTES
            </a>
            <div class="ml-auto d-flex align-items-center">
                <a href="<?php echo BASE_URL; ?>/" class="btn btn-outline-secondary btn-sm mr-2 font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Volver a Portada
                </a>
                <?php if (!empty($isAdminLogged)): ?>
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-primary btn-sm font-weight-bold" style="background-color: var(--cosmol-azul);">
                        <i class="fas fa-tachometer-alt mr-1"></i> Panel Admin
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/login" class="btn btn-outline-primary btn-sm font-weight-bold">
                        <i class="fas fa-lock mr-1"></i> Iniciar Sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="text-center mb-4">
            <h1 class="font-weight-bold text-dark mb-1" style="font-size: 2.1rem;">Fixture Deportivo</h1>
            <p class="text-muted text-sm mb-0">Consulte el cronograma de encuentros, horarios, canchas asignadas y ubicaciones GPS en tiempo real.</p>
        </div>

        <!-- 1. Barra de Filtros Unificada (Días sin contadores + Selector de Disciplina) -->
        <?php require __DIR__ . '/fixture/filtros.php'; ?>

        <!-- 2. Card de Maratón (Circuito Oficial 5.0 km) -->
        <?php require __DIR__ . '/fixture/card_maraton.php'; ?>

        <!-- 3. Cards de Disciplinas Deportivas con Listado Cronológico de Encuentros -->
        <?php require __DIR__ . '/fixture/lista_disciplinas.php'; ?>
    </div>

    <!-- 4. Modal Interactivo del Mapa de la Maratón -->
    <?php require __DIR__ . '/fixture/modal_maraton.php'; ?>

    <!-- 5. Footer Institucional Oficial -->
    <?php require __DIR__ . '/../layout/footer.php'; ?>

    <!-- 6. Scripts y Controladores JavaScript -->
    <?php require __DIR__ . '/fixture/scripts.php'; ?>
</body>
</html>
