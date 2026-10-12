<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="color: #0066B2;">
                    Disciplinas y Reglas de Puntuación
                </h1>
                <span class="text-muted text-sm">Reglamento de cómputo para la Olimpiada COSMOL</span>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/disciplinas/crear" class="btn btn-primary font-weight-bold">
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
            <div class="card-header">
                <h3 class="card-title mb-0">Sistema de Puntuación por Deporte</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-cosmol">
                        <tr>
                            <th>Disciplina Deportiva</th>
                            <th>Descripción</th>
                            <th class="text-center">Puntos (Victoria / Empate / Derrota)</th>
                            <th class="text-center">Formato de Sets</th>
                            <th class="text-center">Estado</th>
                            <?php if (AuthHelper::isAdmin()): ?>
                                <th class="text-center" style="width: 160px;">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($disciplinas)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hay disciplinas registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($disciplinas as $d): ?>
                                <tr>
                                    <td class="font-weight-bold text-dark">
                                        <?php echo e($d['nombre']); ?>
                                    </td>
                                    <td class="text-muted text-sm"><?php echo e($d['descripcion']); ?></td>
                                    <td class="text-center">
                                        <span class="badge py-1 px-2 mr-1" style="background-color: #0066B2; color: #FFFFFF;" title="Puntos por Victoria">V: <?php echo e($d['pts_victoria']); ?> pts</span>
                                        <?php if (!empty($d['permite_empate'])): ?>
                                            <span class="badge py-1 px-2 mr-1" style="background-color: #00A3E0; color: #FFFFFF;" title="Puntos por Empate">E: <?php echo e($d['pts_empate']); ?> pts</span>
                                        <?php else: ?>
                                            <span class="badge badge-light border text-muted py-1 px-2 mr-1">Sin empate</span>
                                        <?php endif; ?>
                                        <span class="badge badge-secondary py-1 px-2 mr-1" title="Puntos por Derrota">D: <?php echo e($d['pts_derrota']); ?> pts</span>
                                        <?php if (!empty($d['pts_derrota_ajustada'])): ?>
                                            <span class="badge py-1 px-2" style="background-color: #EAF4FB; color: #0066B2; border: 1px solid #BFE2F7;" title="Puntos por Derrota Ajustada">D. Aj: <?php echo e($d['pts_derrota_ajustada']); ?> pt</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($d['formato_sets'])): ?>
                                            <span class="badge py-1 px-2" style="background-color: #EAF4FB; color: #0066B2; border: 1px solid #BFE2F7;">A <?php echo e($d['sets_para_ganar']); ?> sets</span>
                                        <?php else: ?>
                                            <span class="badge badge-light border text-muted py-1 px-2">Tiempo regular / Goles</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo $d['estado'] === 'Activa' ? 'success' : 'secondary'; ?> py-1 px-2">
                                            <?php echo e($d['estado']); ?>
                                        </span>
                                    </td>
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <td class="text-center">
                                            <a href="<?php echo BASE_URL; ?>/disciplinas/<?php echo $d['id']; ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Editar">
                                                Editar
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo $d['id']; ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta disciplina?');">
                                                <?php echo CsrfHelper::field(); ?>
                                                <button type="submit" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" title="Eliminar">
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
        </div>
    </div>
</div>
