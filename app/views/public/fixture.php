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
    <style>
        :root { --cosmol-azul: #0066B2; --cosmol-fondo: #F8FAFC; }
        body { font-family: 'Source Sans Pro', sans-serif; background-color: var(--cosmol-fondo); }
        h1, h2, h3, h4, h5 { font-family: 'Outfit', sans-serif; }
        .navbar-public { background: #FFFFFF; border-bottom: 2px solid #E2E8F0; }
        .match-row { background: #FFFFFF; border-radius: 12px; border: 1px solid #E2E8F0; margin-bottom: 1rem; padding: 1.2rem; transition: all 0.2s ease; }
        .match-row:hover { box-shadow: 0 4px 15px rgba(0, 102, 178, 0.08); }
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
            <span class="badge badge-pill badge-info px-3 py-1 font-weight-bold">CALENDARIO OFICIAL</span>
            <h1 class="font-weight-bold mt-2" style="color: #002D52;">Fixture y Programación de Partidos</h1>
            <p class="text-muted mx-auto" style="max-width: 650px;">
                Consulte todos los encuentros por disciplina, fecha, sede y estado en tiempo real.
            </p>
        </div>

        <!-- Filtros -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
            <div class="card-body p-3">
                <form action="<?php echo BASE_URL; ?>/portal/fixture" method="get" class="form-row align-items-center">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <select name="disciplina_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Todas las disciplinas</option>
                            <?php foreach ($disciplinas as $d): ?>
                                <option value="<?php echo (int)$d['id']; ?>" <?php echo ($disciplinaFiltro === (int)$d['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($d['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <select name="estado" class="form-control" onchange="this.form.submit()">
                            <option value="">Todos los estados</option>
                            <option value="Programado" <?php echo ($estadoFiltro === 'Programado') ? 'selected' : ''; ?>>Solo Programados</option>
                            <option value="En_Juego" <?php echo ($estadoFiltro === 'En_Juego') ? 'selected' : ''; ?>>En Juego</option>
                            <option value="Finalizado" <?php echo ($estadoFiltro === 'Finalizado') ? 'selected' : ''; ?>>Finalizados</option>
                        </select>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-outline-secondary btn-sm">Limpiar Filtros</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Partidos -->
        <?php if (empty($partidos)): ?>
            <div class="alert alert-light border text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                <h5 class="text-muted font-weight-bold">No se encontraron encuentros con los filtros seleccionados.</h5>
            </div>
        <?php else: ?>
            <?php foreach ($partidos as $p): ?>
                <div class="match-row shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-muted text-xs mb-2 mb-md-0">
                            <span class="badge badge-primary px-2 py-1 mb-1 d-inline-block" style="background-color: #0066B2;">
                                <i class="fas fa-trophy mr-1"></i> <?php echo e($p['disciplina_nombre']); ?>
                            </span>
                            <div class="font-weight-bold text-dark text-sm"><?php echo e($p['torneo_nombre']); ?></div>
                            <div><i class="fas fa-calendar mr-1"></i> <?php echo e($p['fecha_partido']); ?> <?php echo !empty($p['hora_partido']) ? substr($p['hora_partido'], 0, 5) : ''; ?></div>
                        </div>
                        <div class="col-md-6 text-center my-2 my-md-0">
                            <div class="d-flex justify-content-center align-items-center font-weight-bold" style="font-size: 1.15rem;">
                                <span class="text-truncate px-2" style="max-width: 40%;"><?php echo e($p['local_nombre']); ?></span>
                                <?php if ($p['estado'] === 'Finalizado'): ?>
                                    <span class="badge badge-primary px-3 py-1 mx-2" style="background-color: #0066B2; font-size: 1.2rem;">
                                        <?php echo (int)$p['marcador_local']; ?> - <?php echo (int)$p['marcador_visitante']; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-light border px-2 py-1 mx-2 text-muted">VS</span>
                                <?php endif; ?>
                                <span class="text-truncate px-2" style="max-width: 40%;"><?php echo e($p['visitante_nombre']); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 text-md-right text-xs text-muted mt-2 mt-md-0">
                            <span class="badge badge-<?php echo ($p['estado'] === 'Finalizado') ? 'success' : (($p['estado'] === 'En_Juego') ? 'warning' : 'info'); ?> px-2 py-1 mb-1 d-inline-block">
                                <?php echo e($p['estado']); ?>
                            </span>
                            <div class="text-dark font-weight-bold"><i class="fas fa-map-marker-alt text-danger mr-1"></i> <?php echo e($p['sede_nombre']); ?></div>
                            <div><?php echo e($p['nombre_cancha']); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
