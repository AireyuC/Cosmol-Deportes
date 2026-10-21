<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="0;url=<?php echo BASE_URL; ?>/#posiciones">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Medallero Olímpico | COSMOL'; ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/image.png">
    <!-- Estilos Oficiales del Portal Público -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/portal.css">

    <script>
        // Redirección inmediata a la sección unificada de posiciones en la portada
        window.location.replace("<?php echo BASE_URL; ?>/#posiciones");
    </script>
</head>
<body style="background-color: #F8FAFC;">
    <nav class="navbar navbar-expand-lg navbar-light navbar-public sticky-top">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="<?php echo BASE_URL; ?>/" style="color: #002D52;">
                <img src="<?php echo BASE_URL; ?>/images/image.png" height="38" class="mr-2" alt="COSMOL">
                COSMOL
            </a>
            <div class="ml-auto d-flex align-items-center">
                <a href="<?php echo BASE_URL; ?>/#posiciones" class="btn btn-outline-secondary btn-sm font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Ir a Posiciones
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5 text-center">
        <div class="card border p-4 mx-auto shadow-sm" style="max-width: 550px; border-radius: 12px; background: #FFFFFF; border-color: #E2E8F0 !important;">
            <h4 class="font-weight-bold mb-2" style="color: #002D52;">Tabla de Posiciones y Medallero</h4>
            <p class="text-muted text-sm mb-3">
                Esta sección se encuentra unificada y actualizada en tiempo real en la página principal.
            </p>
            <div>
                <a href="<?php echo BASE_URL; ?>/#posiciones" class="btn font-weight-bold px-4" style="background-color: #0066B2; color: #FFFFFF; border-radius: 8px;">
                    Ver Tabla en la Portada
                </a>
            </div>
        </div>
    </div>
</body>
</html>
