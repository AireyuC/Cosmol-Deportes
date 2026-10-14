<?php
/**
 * Vista de Listado de Disciplinas Deportivas y Reglas
 *
 * @var array $disciplinas
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Reglas y Disciplinas
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/reglas-puntuacion/crear" class="btn btn-primary font-weight-bold">
                        Nueva Disciplina
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-cosmol shadow-sm mb-4">
            
            <!-- Vista Tabla (Desktop y Tablets: >= 768px) -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="thead-cosmol">
                        <tr>
                            <th>Disciplina Deportiva</th>
                            <th>Descripción</th>
                            <th class="text-center">Formato de Juego</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center" style="width: 140px;">Reglas</th>
                            <?php if (AuthHelper::isAdmin()): ?>
                                <th class="text-center" style="width: 160px;">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($disciplinas)): ?>
                            <tr>
                                <td colspan="<?php echo AuthHelper::isAdmin() ? '6' : '5'; ?>" class="text-center text-muted py-4">
                                    No hay disciplinas registradas.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($disciplinas as $d): ?>
                                <tr>
                                    <td class="font-weight-bold text-dark">
                                        <?php echo e($d['nombre']); ?>
                                    </td>
                                    <td class="text-muted text-sm">
                                        <?php echo !empty($d['descripcion']) ? e($d['descripcion']) : '<span class="text-muted font-italic">Sin descripción</span>'; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($d['formato_sets'])): ?>
                                            <span class="badge py-1 px-2" style="background-color: #EAF4FB; color: #0066B2; border: 1px solid #BFE2F7;">
                                                Por sets (<?php echo e($d['sets_para_ganar']); ?> para ganar)
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-light border text-muted py-1 px-2">
                                                Tiempo regular
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo $d['estado'] === 'Activa' ? 'success' : 'secondary'; ?> py-1 px-2">
                                            <?php echo e($d['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold px-2 py-1" data-toggle="modal" data-target="#modalReglas<?php echo e($d['id']); ?>">
                                            Ver Reglas
                                        </button>
                                    </td>
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <td class="text-center">
                                            <a href="<?php echo BASE_URL; ?>/reglas-puntuacion/<?php echo $d['id']; ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1 mr-1">
                                                Editar
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/reglas-puntuacion/<?php echo $d['id']; ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta disciplina?');">
                                                <?php echo CsrfHelper::field(); ?>
                                                <button type="submit" class="btn btn-xs btn-danger font-weight-bold px-2 py-1">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Vista Móvil (Smartphones: < 768px) -->
            <div class="d-block d-md-none">
                <?php if (empty($disciplinas)): ?>
                    <div class="text-center text-muted py-4 text-sm">
                        No hay disciplinas registradas.
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($disciplinas as $d): ?>
                            <div class="list-group-item p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                                        <?php echo e($d['nombre']); ?>
                                    </h5>
                                    <span class="badge badge-<?php echo $d['estado'] === 'Activa' ? 'success' : 'secondary'; ?> px-2 py-1">
                                        <?php echo e($d['estado']); ?>
                                    </span>
                                </div>

                                <?php if (!empty($d['descripcion'])): ?>
                                    <p class="text-muted text-sm mb-2">
                                        <?php echo e($d['descripcion']); ?>
                                    </p>
                                <?php endif; ?>

                                <div class="mb-3 text-xs text-muted">
                                    <strong>Formato de Juego:</strong>
                                    <?php if (!empty($d['formato_sets'])): ?>
                                        Por sets (<?php echo e($d['sets_para_ganar']); ?> para ganar)
                                    <?php else: ?>
                                        Tiempo regular / Goles
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" data-toggle="modal" data-target="#modalReglas<?php echo e($d['id']); ?>">
                                        Ver Reglas
                                    </button>

                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <div>
                                            <a href="<?php echo BASE_URL; ?>/reglas-puntuacion/<?php echo $d['id']; ?>/editar" class="btn btn-sm btn-info font-weight-bold mr-1">
                                                Editar
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/reglas-puntuacion/<?php echo $d['id']; ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta disciplina?');">
                                                <?php echo CsrfHelper::field(); ?>
                                                <button type="submit" class="btn btn-sm btn-danger font-weight-bold">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<!-- Modales de Detalles y Reglas de Puntuación -->
<?php if (!empty($disciplinas)): ?>
    <?php foreach ($disciplinas as $d): ?>
        <div class="modal fade" id="modalReglas<?php echo e($d['id']); ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?php echo e($d['id']); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-dark" id="modalLabel<?php echo e($d['id']); ?>">
                            Reglas de: <?php echo e($d['nombre']); ?>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Descripción -->
                        <div class="mb-3">
                            <span class="text-muted d-block text-xs text-uppercase font-weight-bold mb-1">Descripción / Modalidad</span>
                            <p class="text-dark mb-0 text-sm">
                                <?php echo !empty($d['descripcion']) ? e($d['descripcion']) : '<span class="text-muted font-italic">Sin descripción detallada registrada.</span>'; ?>
                            </p>
                        </div>

                        <!-- Esquema de Puntuación -->
                        <div class="mb-3">
                            <span class="text-muted d-block text-xs text-uppercase font-weight-bold mb-2">Esquema de Puntuación en Tabla</span>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="p-2 border rounded bg-light">
                                        <small class="text-muted d-block font-weight-bold">Victoria</small>
                                        <span class="h5 font-weight-bold text-primary mb-0"><?php echo e($d['pts_victoria']); ?></span>
                                        <small class="text-muted d-block">pts</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 border rounded bg-light">
                                        <small class="text-muted d-block font-weight-bold">Empate</small>
                                        <span class="h5 font-weight-bold text-info mb-0"><?php echo !empty($d['permite_empate']) ? e($d['pts_empate']) : '0'; ?></span>
                                        <small class="text-muted d-block"><?php echo !empty($d['permite_empate']) ? 'pts' : 'No aplica'; ?></small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 border rounded bg-light">
                                        <small class="text-muted d-block font-weight-bold">Derrota</small>
                                        <span class="h5 font-weight-bold text-secondary mb-0"><?php echo e($d['pts_derrota']); ?></span>
                                        <small class="text-muted d-block">pts</small>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($d['pts_derrota_ajustada'])): ?>
                                <div class="mt-2 text-xs text-muted">
                                    <strong>Derrota Ajustada / Especial:</strong> <?php echo e($d['pts_derrota_ajustada']); ?> punto(s) (ej. por sets 2-3 o presentación reglamentaria).
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Formato de Juego -->
                        <div class="row pt-2 border-top">
                            <div class="col-6 mb-2">
                                <span class="text-muted d-block text-xs text-uppercase font-weight-bold">Formato de Juego</span>
                                <span class="font-weight-bold text-dark text-sm">
                                    <?php if (!empty($d['formato_sets'])): ?>
                                        Por sets
                                    <?php else: ?>
                                        Tiempo regular
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="text-muted d-block text-xs text-uppercase font-weight-bold">Empates</span>
                                <span class="font-weight-bold text-dark text-sm">
                                    <?php echo !empty($d['permite_empate']) ? 'Permitido' : 'No admitido'; ?>
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block text-xs text-uppercase font-weight-bold">Estado</span>
                                <span class="badge badge-<?php echo $d['estado'] === 'Activa' ? 'success' : 'secondary'; ?> px-2 py-1">
                                    <?php echo e($d['estado']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                        <?php if (AuthHelper::isAdmin()): ?>
                            <a href="<?php echo BASE_URL; ?>/reglas-puntuacion/<?php echo $d['id']; ?>/editar" class="btn btn-primary btn-sm font-weight-bold">
                                Editar Reglas
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
