<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-volleyball-ball text-success mr-2"></i> Disciplinas y Reglas de Puntuación
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/disciplinas/crear" class="btn btn-success">
                        <i class="fas fa-plus mr-1"></i> Nueva Disciplina
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline card-success shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Reglamento de Cómputo por Deporte</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">Icono</th>
                            <th>Disciplina Deportiva</th>
                            <th>Descripción</th>
                            <th class="text-center">Regla de Puntuación (Vic / Emp / Der)</th>
                            <th class="text-center">Formato de Sets</th>
                            <th class="text-center">Estado</th>
                            <?php if (AuthHelper::isAdmin()): ?>
                                <th class="text-center" style="width: 140px;">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($disciplinas)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No hay disciplinas registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($disciplinas as $d): ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge badge-success p-2 rounded-circle">
                                            <i class="fas fa-<?php echo e($d['icono']); ?> fa-lg"></i>
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">
                                        <?php echo e($d['nombre']); ?>
                                    </td>
                                    <td class="text-muted text-sm"><?php echo e($d['descripcion']); ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-primary px-2" title="Puntos por Victoria">G: <?php echo e($d['pts_victoria']); ?> pts</span>
                                        <?php if (!empty($d['permite_empate'])): ?>
                                            <span class="badge badge-warning px-2" title="Puntos por Empate">E: <?php echo e($d['pts_empate']); ?> pts</span>
                                        <?php else: ?>
                                            <span class="badge badge-light border text-muted px-2">Sin empate</span>
                                        <?php endif; ?>
                                        <span class="badge badge-secondary px-2" title="Puntos por Derrota">P: <?php echo e($d['pts_derrota']); ?> pts</span>
                                        <?php if (!empty($d['pts_derrota_ajustada'])): ?>
                                            <span class="badge badge-info px-2" title="Puntos por Derrota Ajustada (presentación o sets)">P. Aj: <?php echo e($d['pts_derrota_ajustada']); ?> pt</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($d['formato_sets'])): ?>
                                            <span class="badge badge-info"><i class="fas fa-check-circle mr-1"></i> Sí (a <?php echo e($d['sets_para_ganar']); ?> sets)</span>
                                        <?php else: ?>
                                            <span class="badge badge-light border text-muted">Tiempo Corrido / Goles</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo $d['estado'] === 'Activa' ? 'success' : 'secondary'; ?>">
                                            <?php echo e($d['estado']); ?>
                                        </span>
                                    </td>
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <td class="text-center">
                                            <a href="<?php echo BASE_URL; ?>/disciplinas/<?php echo $d['id']; ?>/editar" class="btn btn-sm btn-info" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo $d['id']; ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta disciplina?');">
                                                <?php echo CsrfHelper::field(); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
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
