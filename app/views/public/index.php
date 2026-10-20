<?php
/**
 * Portal Web Informativo Oficial de las Olimpiadas COSMOL 2026
 * Diseñado para delegaciones departamentales, deportistas y público en general.
 * Acceso público libre y autónomo con diseño plano corporativo.
 *
 * Arquitectura modular segmentada por secciones:
 * - layout/navbar.php: Barra de navegación superior
 * - layout/hero.php: Hero banner con lema oficial
 * - layout/stats.php: Barra de estadísticas rápidas
 * - partidos/seccion.php: Partidos del Día (Fixture de hoy)
 * - disciplinas/seccion.php: Catálogo interactivo de disciplinas
 * - posiciones/seccion.php: Tabla de posiciones olímpicas y podios
 * - sedes/seccion.php: Sedes habilitadas y rutas GPS ("Cómo llegar")
 * - delegaciones/seccion.php: Delegaciones y áreas institucionales
 * - layout/footer.php: Footer informativo institucional
 * - layout/scripts.php: Scripts JavaScript y geolocalización GPS
 *
 * Compatible con PHP 7.3
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Olimpiadas COSMOL 2026 | Portal Oficial'; ?></title>

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap 4.6 (AdminLTE compatible) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/logoSinFondo.png">

    <!-- Leaflet CSS para el Mapa Interactivo de la Maratón -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Estilos Oficiales del Portal Público -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/portal.css">
</head>
<body>

    <!-- 1. Barra de Navegación -->
    <?php require __DIR__ . '/layout/navbar.php'; ?>

    <!-- 2. Hero Banner Principal -->
    <?php require __DIR__ . '/layout/hero.php'; ?>

    <!-- 3. Barra de Estadísticas Rápidas -->
    <?php require __DIR__ . '/layout/stats.php'; ?>

    <!-- 4. Sección de Partidos del Día -->
    <?php require __DIR__ . '/partidos/seccion.php'; ?>

    <!-- 4.1. Sección: Ruta Oficial de la Media Maratón (Circuito Urb. Celina Muyurina) -->
    <?php require __DIR__ . '/maraton/seccion.php'; ?>

    <!-- 4.2. Sección: Cronograma General de Actividades (Jueves 22 al Sábado 24) -->
    <?php require __DIR__ . '/cronograma/seccion.php'; ?>

    <!-- 5. Sección de Disciplinas Deportivas -->
    <?php require __DIR__ . '/disciplinas/seccion.php'; ?>

    <!-- 6. Sección de Posiciones Olímpicas y Medallero -->
    <?php require __DIR__ . '/posiciones/seccion.php'; ?>

    <!-- 7. Sección de Sedes Deportivas y Rutas GPS -->
    <?php require __DIR__ . '/sedes/seccion.php'; ?>

    <!-- 8. Sección de Delegaciones Participantes -->
    <?php require __DIR__ . '/delegaciones/seccion.php'; ?>

    <!-- 9. Footer Informativo -->
    <?php require __DIR__ . '/layout/footer.php'; ?>

    <!-- 10. Scripts y Navegación GPS -->
    <?php require __DIR__ . '/layout/scripts.php'; ?>

</body>
</html>
