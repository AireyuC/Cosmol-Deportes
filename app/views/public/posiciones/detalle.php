<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Tablas de Posiciones | COSMOL'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/logoSinFondo.png">
    <!-- Estilos Oficiales del Portal Público -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/portal.css">
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
            <span class="badge badge-pill badge-primary px-3 py-1 font-weight-bold" style="background-color: var(--cosmol-azul);">RANKING DEPORTIVO</span>
            <h1 class="font-weight-bold mt-2" style="color: #002D52;">Tablas de Posiciones Oficiales</h1>
            <p class="text-muted mx-auto" style="max-width: 650px;">
                Seleccione el torneo para visualizar la puntuación, partidos jugados y diferencia de goles/puntos.
            </p>
        </div>

        <!-- Selector de Torneo -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
            <div class="card-body p-3">
                <form action="<?php echo BASE_URL; ?>/portal/posiciones" method="get" class="form-inline justify-content-center">
                    <label class="font-weight-bold mr-2 text-dark">Seleccionar Torneo:</label>
                    <select name="torneo_id" class="form-control mr-2" onchange="this.form.submit()">
                        <?php foreach ($torneos as $t): ?>
                            <option value="<?php echo (int)$t['id']; ?>" <?php echo ($torneoSeleccionado && (int)$torneoSeleccionado['id'] === (int)$t['id']) ? 'selected' : ''; ?>>
                                <?php echo e($t['nombre']); ?> (<?php echo e($t['disciplina_nombre']); ?> - <?php echo e($t['rama']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>

        <!-- Tabla -->
        <?php if ($torneoSeleccionado): ?>
            <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold text-dark mb-0"><?php echo e($torneoSeleccionado['nombre']); ?></h5>
                    <span class="badge badge-primary"><?php echo count($tablaPosiciones); ?> Equipos</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-center">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th class="text-left">Equipo</th>
                                <th>Delegación</th>
                                <th style="width: 60px;">PJ</th>
                                <th style="width: 60px;">PG</th>
                                <th style="width: 60px;">PE</th>
                                <th style="width: 60px;">PP</th>
                                <th style="width: 60px;">GF/PF</th>
                                <th style="width: 60px;">GC/PC</th>
                                <th style="width: 60px;">DG/DP</th>
                                <th style="width: 80px;" class="font-weight-bold text-primary">PTS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tablaPosiciones)): ?>
                                <tr>
                                    <td colspan="11" class="py-4 text-muted">Aún no se han computado partidos en este torneo.</td>
                                </tr>
                            <?php else: ?>
                                <?php $pos = 1; foreach ($tablaPosiciones as $fila): ?>
                                    <tr>
                                        <td class="font-weight-bold text-muted"><?php echo $pos++; ?></td>
                                        <td class="text-left font-weight-bold text-dark">
                                            <span class="d-inline-block rounded-circle mr-1" style="width: 10px; height: 10px; background-color: <?php echo !empty($fila['color_representativo']) ? e($fila['color_representativo']) : '#007bff'; ?>;"></span>
                                            <?php echo e($fila['nombre_corto'] ? $fila['nombre_corto'] : $fila['nombre']); ?>
                                        </td>
                                        <td><span class="badge badge-light border"><?php echo e($fila['delegacion_nombre']); ?></span></td>
                                        <td><?php echo (int)$fila['pj']; ?></td>
                                        <td class="text-success font-weight-bold"><?php echo (int)$fila['pg']; ?></td>
                                        <td class="text-secondary"><?php echo (int)$fila['pe']; ?></td>
                                        <td class="text-danger"><?php echo (int)$fila['pp']; ?></td>
                                        <td><?php echo (int)$fila['gf']; ?></td>
                                        <td><?php echo (int)$fila['gc']; ?></td>
                                        <td class="font-weight-bold"><?php echo (int)$fila['dg']; ?></td>
                                        <td class="font-weight-bold text-primary" style="font-size: 1.1rem;"><?php echo (int)$fila['pts']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
