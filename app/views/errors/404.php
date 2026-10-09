<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Página no encontrada | <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition">
<div class="container mt-5">
    <div class="error-page">
        <h2 class="headline text-warning">404</h2>
        <div class="error-content pt-3">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> ¡Ups! Página no encontrada.</h3>
            <p>
                No pudimos encontrar la sección o recurso solicitado en el sistema de las Olimpiadas.
                Puedes regresar al inicio para continuar navegando.
            </p>
            <a href="<?php echo BASE_URL; ?>/" class="btn btn-primary mt-2">
                <i class="fas fa-home mr-1"></i> Ir al Dashboard Principal
            </a>
        </div>
    </div>
</div>
</body>
</html>
