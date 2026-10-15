<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Medallero Olímpico | COSMOL'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/logoSinFondo.png">
    <style>
        :root { --cosmol-azul: #0066B2; --cosmol-fondo: #F8FAFC; }
        body { font-family: 'Source Sans Pro', sans-serif; background-color: var(--cosmol-fondo); }
        h1, h2, h3, h4, h5 { font-family: 'Outfit', sans-serif; }
        .navbar-public { background: #FFFFFF; border-bottom: 2px solid #E2E8F0; }
        .badge-oro { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: #FFF; font-weight: 700; border-radius: 50px; padding: 0.3rem 0.8rem; }
        .badge-plata { background: linear-gradient(135deg, #94A3B8 0%, #64748B 100%); color: #FFF; font-weight: 700; border-radius: 50px; padding: 0.3rem 0.8rem; }
        .badge-bronce { background: linear-gradient(135deg, #D97706 0%, #B45309 100%); color: #FFF; font-weight: 700; border-radius: 50px; padding: 0.3rem 0.8rem; }
    </style>
</head>
<body>
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
                <?php if ($isAdminLogged): ?>
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-primary btn-sm font-weight-bold" style="background-color: #0066B2;">
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

    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="badge badge-pill badge-warning px-3 py-1 font-weight-bold text-dark">CLASIFICACIÓN OLÍMPICA</span>
            <h1 class="font-weight-bold mt-2" style="color: #002D52;">Medallero General de las Olimpiadas</h1>
            <p class="text-muted mx-auto" style="max-width: 650px;">
                Conteo oficial de preseas de Oro, Plata y Bronce acumuladas por cada delegación departamental.
            </p>
        </div>

        <!-- Tabla General -->
        <div class="card border-0 shadow-sm mb-5" style="border-radius: 16px; overflow: hidden;">
            <div class="card-header bg-white border-bottom p-3">
                <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-trophy text-warning mr-2"></i> Tabla Acumulada Oficial</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-center">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th style="width: 70px;">Pos</th>
                            <th class="text-left">Delegación / Departamento</th>
                            <th style="width: 120px;"><i class="fas fa-medal text-warning mr-1"></i> Oro</th>
                            <th style="width: 120px;"><i class="fas fa-medal text-secondary mr-1"></i> Plata</th>
                            <th style="width: 120px;"><i class="fas fa-medal mr-1" style="color: #CD7F32;"></i> Bronce</th>
                            <th style="width: 120px;">Total</th>
                            <th style="width: 120px;" class="font-weight-bold text-primary">Pts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($resumen)): ?>
                            <tr>
                                <td colspan="7" class="py-4 text-muted">
                                    No se han asignado medallas aún. Las medallas se cargarán al término de las finales.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $pos = 1; foreach ($resumen as $m): ?>
                                <tr>
                                    <td class="font-weight-bold text-secondary">#<?php echo $pos++; ?></td>
                                    <td class="text-left font-weight-bold">
                                        <span class="d-inline-block rounded-circle mr-2" style="width: 12px; height: 12px; background-color: <?php echo !empty($m['color_representativo']) ? e($m['color_representativo']) : '#007bff'; ?>;"></span>
                                        <?php echo e($m['delegacion_nombre']); ?>
                                    </td>
                                    <td><span class="badge-oro"><?php echo (int)$m['oro']; ?></span></td>
                                    <td><span class="badge-plata"><?php echo (int)$m['plata']; ?></span></td>
                                    <td><span class="badge-bronce"><?php echo (int)$m['bronce']; ?></span></td>
                                    <td class="font-weight-bold text-dark"><?php echo (int)$m['total_medallas']; ?></td>
                                    <td class="font-weight-bold text-primary"><?php echo (int)$m['puntos_totales']; ?> pts</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Podios por Torneo -->
        <?php if (!empty($podios)): ?>
            <h3 class="font-weight-bold mb-4" style="color: #002D52;">Podios Proclamados por Torneo</h3>
            <div class="row">
                <?php foreach ($podios as $torneoId => $dataTorneo): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                            <div class="card-header bg-light border-bottom font-weight-bold">
                                <i class="fas fa-medal text-primary mr-2"></i> <?php echo e($dataTorneo['torneo_nombre']); ?>
                            </div>
                            <div class="card-body p-3">
                                <ul class="list-unstyled mb-0">
                                    <li class="py-2 border-bottom d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-medal text-warning fa-lg mr-2"></i> Oro (Campeón)</span>
                                        <strong class="text-dark"><?php echo !empty($dataTorneo['oro']) ? e($dataTorneo['oro']) : '-'; ?></strong>
                                    </li>
                                    <li class="py-2 border-bottom d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-medal text-secondary fa-lg mr-2"></i> Plata (Subcampeón)</span>
                                        <strong class="text-dark"><?php echo !empty($dataTorneo['plata']) ? e($dataTorneo['plata']) : '-'; ?></strong>
                                    </li>
                                    <li class="py-2 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-medal fa-lg mr-2" style="color: #CD7F32;"></i> Bronce (3er Lugar)</span>
                                        <strong class="text-dark"><?php echo !empty($dataTorneo['bronce']) ? e($dataTorneo['bronce']) : '-'; ?></strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
