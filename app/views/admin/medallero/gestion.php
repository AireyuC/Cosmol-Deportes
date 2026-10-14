<?php
/**
 * Vista: Gestión Administrativa de Medallero por Torneo
 *
 * @var array|null  $olimpiada
 * @var array       $torneos
 * @var string|null $flashMsg
 * @var string      $flashType
 * @var string      $csrfToken
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    <i class="fas fa-medal mr-2 text-warning"></i>Gestión del Medallero
                </h1>
                <?php if ($olimpiada): ?>
                    <small class="text-muted">
                        <?php echo htmlspecialchars($olimpiada['nombre'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo (int)$olimpiada['anio']; ?>)
                    </small>
                <?php endif; ?>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/medallero" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al Medallero
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">



        <div class="card card-outline card-warning shadow-sm">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-trophy mr-2 text-warning"></i>Torneos Disponibles para Premiación
                </h3>
                <div class="card-tools">
                    <span class="badge badge-info">Torneos en Playoffs / Finalizados</span>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($torneos)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-info-circle fa-2x mb-2 text-secondary"></i>
                        <p class="mb-0">No hay torneos en fase de Playoffs o Finalizados listos para premiar.</p>
                        <small>Los torneos pasan a estar disponibles cuando su estado avanza a Playoffs o Finalizado.</small>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Disciplina</th>
                                    <th>Torneo / Categoría</th>
                                    <th>Rama</th>
                                    <th>Estado Torneo</th>
                                    <th>Estado Medallas</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($torneos as $t): ?>
                                    <tr>
                                        <td>
                                            <i class="<?php echo !empty($t['disciplina_icono']) ? htmlspecialchars($t['disciplina_icono'], ENT_QUOTES, 'UTF-8') : 'fas fa-futbol'; ?> mr-2 text-primary"></i>
                                            <strong><?php echo htmlspecialchars($t['disciplina_nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($t['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-light border">
                                                <?php echo htmlspecialchars($t['rama'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($t['estado'] === 'Finalizado'): ?>
                                                <span class="badge badge-success">Finalizado</span>
                                            <?php elseif ($t['estado'] === 'Playoffs'): ?>
                                                <span class="badge badge-warning">Playoffs</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary"><?php echo htmlspecialchars($t['estado'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php $asignadas = (int)$t['medallas_asignadas']; ?>
                                            <?php if ($asignadas >= 3): ?>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i> Podio Completo (<?php echo $asignadas; ?>)
                                                </span>
                                            <?php elseif ($asignadas > 0): ?>
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Parcial (<?php echo $asignadas; ?>)
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-clock mr-1"></i> Sin Asignar
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right">
                                            <a href="<?php echo BASE_URL; ?>/medallero/disciplinas/<?php echo (int)$t['id']; ?>/asignar" class="btn btn-sm btn-warning font-weight-bold">
                                                <i class="fas fa-award mr-1"></i> <?php echo $asignadas > 0 ? 'Editar Podio' : 'Asignar Podio'; ?>
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>/posiciones?torneo_id=<?php echo (int)$t['id']; ?>" class="btn btn-sm btn-outline-info" title="Ver Posiciones">
                                                <i class="fas fa-table"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
