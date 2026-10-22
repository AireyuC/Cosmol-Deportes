<?php
/**
 * Ficha detallada de Torneo, Grupos y Equipos
 *
 * @var string $pageTitle
 * @var array  $torneo
 * @var array  $grupos
 * @var array  $equiposSinGrupo
 * @var array  $todosEquipos
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-7">
                <h1 class="card-title mb-0 card-title-cosmol">
                    <?php echo e($torneo['nombre']); ?>
                </h1>
                <span class="text-muted text-sm ml-2">
                    <?php echo e($torneo['olimpiada_nombre']); ?> &bull; <?php echo e($torneo['disciplina_nombre']); ?>
                </span>
            </div>
            <div class="col-sm-5 text-right">
                <a href="<?php echo BASE_URL; ?>/disciplinas" class="btn btn-secondary font-weight-bold mr-1">
                    <i class="fa fa-arrow-left mr-1"></i> Volver
                </a>
                <?php if (AuthHelper::isAdmin()): ?>
                    <?php if ($torneo['estado'] !== 'Finalizado'): ?>
                        <button type="button" class="btn btn-warning font-weight-bold mr-1 shadow-sm" data-toggle="modal" data-target="#modalConcluirDisciplina" title="Dar por concluida la disciplina y actualizar el medallero olímpico">
                            <i class="fas fa-flag-checkered mr-1"></i> Concluir Disciplina
                        </button>
                    <?php else: ?>
                        <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo (int)$torneo['id']; ?>/concluir" method="post" class="d-inline mr-1">
                            <?php echo CsrfHelper::field(); ?>
                            <button type="submit" class="btn btn-outline-warning font-weight-bold" title="Recalcular automáticamente medallas de esta disciplina">
                                <i class="fas fa-sync-alt mr-1"></i> Sincronizar Medallas
                            </button>
                        </form>
                        <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo (int)$torneo['id']; ?>/reabrir" method="post" class="d-inline mr-1" onsubmit="return confirm('¿Desea reabrir esta disciplina para reprogramar encuentros o modificar actas?');">
                            <?php echo CsrfHelper::field(); ?>
                            <button type="submit" class="btn btn-outline-secondary font-weight-bold" title="Reabrir disciplina">
                                <i class="fas fa-unlock mr-1"></i> Reabrir
                            </button>
                        </form>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>/disciplinas/<?php echo e($torneo['id']); ?>/editar" class="btn btn-primary font-weight-bold">
                        Editar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Barra de Estado y Metadatos -->
        <div class="card card-outline shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row text-center text-md-left align-items-center">
                    <div class="col-md-2 mb-2 mb-md-0">
                        <span class="text-muted d-block text-xs text-uppercase font-weight-bold">Categoría</span>
                        <?php if ($torneo['rama'] === 'Masculino'): ?>
                            <span class="badge badge-primary px-3 py-1"><i class="fas fa-mars mr-1"></i> Masculino</span>
                        <?php elseif ($torneo['rama'] === 'Femenino'): ?>
                            <span class="badge badge-danger px-3 py-1"><i class="fas fa-venus mr-1"></i> Femenino</span>
                        <?php else: ?>
                            <span class="badge badge-info px-3 py-1"><i class="fas fa-venus-mars mr-1"></i> Mixto</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <span class="text-muted d-block text-xs text-uppercase font-weight-bold">Formato y Modalidad</span>
                        <span class="font-weight-bold text-dark">
                            <?php 
                                $tipoLabels = [
                                    'Grupos_Playoff' => 'Fase de Grupos',
                                    'Liga'           => 'Clasificación General',
                                    'Eliminatoria'   => 'Eliminación Directa'
                                ];
                                echo isset($tipoLabels[$torneo['tipo_torneo']]) ? $tipoLabels[$torneo['tipo_torneo']] : str_replace('_', ' ', e($torneo['tipo_torneo']));
                            ?>
                        </span>
                        <small class="text-muted d-block">(<?php echo ($torneo['modalidad'] === 'Solo_Ida') ? 'Solo Ida' : 'Ida y Vuelta'; ?>)</small>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <span class="text-muted d-block text-xs text-uppercase font-weight-bold">Estado Actual</span>
                        <?php 
                            $badgeColors = [
                                'Borrador'      => 'secondary',
                                'Inscripciones' => 'info',
                                'Fase_Grupos'   => 'primary',
                                'Playoffs'      => 'warning',
                                'Finalizado'    => 'success'
                            ];
                            $colorEst = isset($badgeColors[$torneo['estado']]) ? $badgeColors[$torneo['estado']] : 'secondary';
                            $estadoLabels = [
                                'Borrador'      => 'Borrador',
                                'Inscripciones' => 'Inscripciones',
                                'Fase_Grupos'   => 'Fase de Grupos',
                                'Playoffs'      => 'Fase Final (1º, 2º y 3º)',
                                'Finalizado'    => 'Finalizado'
                            ];
                            $textoEst = isset($estadoLabels[$torneo['estado']]) ? $estadoLabels[$torneo['estado']] : str_replace('_', ' ', e($torneo['estado']));
                        ?>
                        <span class="badge badge-<?php echo $colorEst; ?> px-3 py-1 font-weight-bold">
                            <?php echo $textoEst; ?>
                        </span>
                    </div>
                    <div class="col-md-5 text-md-right">
                        <a href="<?php echo BASE_URL; ?>/partidos?torneo_id=<?php echo e($torneo['id']); ?>" class="btn btn-sm btn-outline-info font-weight-bold mr-1">
                            Fixture
                        </a>
                        <?php if (AuthHelper::isAdmin()): ?>
                            <a href="<?php echo BASE_URL; ?>/partidos/crear?torneo_id=<?php echo e($torneo['id']); ?>" class="btn btn-sm btn-outline-primary font-weight-bold mr-1">
                                Programar Partido
                            </a>
                            <?php if ($torneo['tipo_torneo'] === 'Grupos_Playoff'): ?>
                                <button type="button" class="btn btn-sm btn-success font-weight-bold mr-1" data-toggle="modal" data-target="#modalNuevoGrupo">
                                    Crear Grupo
                                </button>
                            <?php endif; ?>
                            <a href="<?php echo BASE_URL; ?>/equipos/crear?torneo_id=<?php echo e($torneo['id']); ?>" class="btn btn-sm btn-primary font-weight-bold">
                                Inscribir Equipo
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- PODIO OFICIAL DE LA DISCIPLINA (Si ya se han proclamado medallas) -->
        <?php if (!empty($medallas)): ?>
            <?php 
                $medallasIndex = [];
                foreach ($medallas as $med) {
                    $medallasIndex[$med['tipo_medalla']] = $med;
                }
            ?>
            <div class="card shadow-sm mb-4 border" style="border-radius: 12px; border-left: 5px solid #FFD700 !important;">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark mb-0">
                        <i class="fas fa-medal text-warning mr-2"></i> Podio Oficial Proclamado: <?php echo e($torneo['nombre']); ?>
                    </h3>
                    <a href="<?php echo BASE_URL; ?>/medallero" class="btn btn-xs btn-outline-warning text-dark font-weight-bold">
                        <i class="fas fa-trophy mr-1 text-warning"></i> Ver Medallero General
                    </a>
                </div>
                <div class="card-body py-3">
                    <div class="row text-center">
                        <!-- ORO -->
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="p-3 rounded shadow-sm border" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); border-color: #FCD34D !important;">
                                <div class="font-weight-bold text-warning text-uppercase text-xs mb-1">
                                    🥇 Medalla de Oro (Campeón)
                                </div>
                                <h4 class="font-weight-bold text-dark mb-0">
                                    <?php echo isset($medallasIndex['ORO']) ? e($medallasIndex['ORO']['delegacion_nombre']) : 'Por definir'; ?>
                                </h4>
                                <span class="badge badge-warning text-dark mt-1 font-weight-bold">+5 Puntos Olímpicos</span>
                            </div>
                        </div>
                        <!-- PLATA -->
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="p-3 rounded shadow-sm border" style="background: linear-gradient(135deg, #F8FAFC 0%, #E2E8F0 100%); border-color: #CBD5E1 !important;">
                                <div class="font-weight-bold text-secondary text-uppercase text-xs mb-1">
                                    🥈 Medalla de Plata (Subcampeón)
                                </div>
                                <h4 class="font-weight-bold text-dark mb-0">
                                    <?php echo isset($medallasIndex['PLATA']) ? e($medallasIndex['PLATA']['delegacion_nombre']) : 'Por definir'; ?>
                                </h4>
                                <span class="badge badge-secondary text-white mt-1 font-weight-bold">+3 Puntos Olímpicos</span>
                            </div>
                        </div>
                        <!-- BRONCE -->
                        <div class="col-md-4">
                            <div class="p-3 rounded shadow-sm border" style="background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%); border-color: #FDBA74 !important;">
                                <div class="font-weight-bold text-uppercase text-xs mb-1" style="color: #C2410C;">
                                    🥉 Medalla de Bronce (3er Lugar)
                                </div>
                                <h4 class="font-weight-bold text-dark mb-0">
                                    <?php echo isset($medallasIndex['BRONCE']) ? e($medallasIndex['BRONCE']['delegacion_nombre']) : 'Por definir'; ?>
                                </h4>
                                <span class="badge text-white mt-1 font-weight-bold" style="background-color: #C2410C;">+1 Punto Olímpico</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECCIÓN: ENCUENTROS Y CARGA DE RESULTADOS DE LA DISCIPLINA -->
        <a id="resultados"></a>
        <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-clipboard-list text-primary mr-2"></i> Encuentros y Carga de Resultados
                    <span class="badge badge-info ml-2 font-weight-normal"><?php echo count($partidos); ?> registrados</span>
                </h3>
                <div class="card-tools">
                    <a href="<?php echo BASE_URL; ?>/partidos/crear?torneo_id=<?php echo e($torneo['id']); ?>" class="btn btn-sm btn-primary font-weight-bold">
                        <i class="fas fa-plus mr-1"></i> Programar Partido
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($partidos)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-calendar-times fa-2x mb-2 text-secondary d-block"></i>
                        Aún no se han programado partidos para esta disciplina.
                        <div class="mt-2">
                            <a href="<?php echo BASE_URL; ?>/partidos/crear?torneo_id=<?php echo e($torneo['id']); ?>" class="btn btn-sm btn-primary font-weight-bold">
                                <i class="fas fa-plus mr-1"></i> Programar Primer Partido
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo e($torneo['id']); ?>/resultados-rapidos" method="post">
                        <?php echo CsrfHelper::field(); ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 45px;">#</th>
                                        <th style="width: 125px;">Fecha / Hora</th>
                                        <th style="width: 130px;">Fase / Jornada</th>
                                        <th class="text-right" style="width: 25%;">Equipo Local</th>
                                        <th class="text-center" style="width: 180px;">Marcador</th>
                                        <th class="text-left" style="width: 25%;">Equipo Visitante</th>
                                        <th style="width: 140px;">Estado</th>
                                        <th class="text-center" style="width: 130px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($partidos as $p): ?>
                                        <tr>
                                            <td class="text-muted font-weight-bold align-middle">
                                                #<?php echo e($p['id']); ?>
                                            </td>
                                            <td class="align-middle">
                                                <?php if (!empty($p['fecha_partido'])): ?>
                                                    <div class="font-weight-bold text-dark text-xs">
                                                        <?php echo date('d/m/Y', strtotime($p['fecha_partido'])); ?>
                                                    </div>
                                                    <div class="text-muted text-xs">
                                                        <i class="far fa-clock mr-1"></i>
                                                        <?php echo !empty($p['hora_partido']) ? substr($p['hora_partido'], 0, 5) : 'Por def.'; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted text-xs italic">Por definir</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge badge-light border text-dark font-weight-bold px-2 py-1 text-xs">
                                                    <?php echo e($p['fase']); ?>
                                                </span>
                                                <div class="text-muted text-xs mt-1">
                                                    <?php echo e($p['jornada']); ?>
                                                </div>
                                            </td>
                                            <td class="text-right align-middle">
                                                <span class="font-weight-bold text-dark d-block">
                                                    <?php echo e($p['local_nombre']); ?>
                                                </span>
                                                <small class="badge" style="background-color: <?php echo e($p['local_color'] ?? '#0066B2'); ?>; color: #fff;">
                                                    <?php echo e($p['delegacion_local']); ?>
                                                </small>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="d-inline-flex align-items-center justify-content-center">
                                                    <input type="number" min="0" max="999" name="marcador_local[<?php echo $p['id']; ?>]" value="<?php echo ($p['marcador_local'] !== null) ? (int)$p['marcador_local'] : ''; ?>" class="form-control form-control-sm text-center font-weight-bold shadow-sm" style="width: 60px;" placeholder="0">
                                                    <span class="font-weight-bold mx-2 text-muted">&ndash;</span>
                                                    <input type="number" min="0" max="999" name="marcador_visitante[<?php echo $p['id']; ?>]" value="<?php echo ($p['marcador_visitante'] !== null) ? (int)$p['marcador_visitante'] : ''; ?>" class="form-control form-control-sm text-center font-weight-bold shadow-sm" style="width: 60px;" placeholder="0">
                                                </div>
                                            </td>
                                            <td class="text-left align-middle">
                                                <span class="font-weight-bold text-dark d-block">
                                                    <?php echo e($p['visitante_nombre']); ?>
                                                </span>
                                                <small class="badge" style="background-color: <?php echo e($p['visitante_color'] ?? '#28A745'); ?>; color: #fff;">
                                                    <?php echo e($p['delegacion_visitante']); ?>
                                                </small>
                                            </td>
                                            <td class="align-middle">
                                                <select name="estado_partido[<?php echo $p['id']; ?>]" class="form-control form-control-sm font-weight-bold">
                                                    <option value="Finalizado" <?php echo ($p['estado'] === 'Finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                                                    <option value="Programado" <?php echo ($p['estado'] === 'Programado') ? 'selected' : ''; ?>>Programado</option>
                                                    <option value="En_Juego" <?php echo ($p['estado'] === 'En_Juego') ? 'selected' : ''; ?>>En Juego</option>
                                                    <option value="WO" <?php echo ($p['estado'] === 'WO') ? 'selected' : ''; ?>>W.O.</option>
                                                    <option value="Suspendido" <?php echo ($p['estado'] === 'Suspendido') ? 'selected' : ''; ?>>Suspendido</option>
                                                </select>
                                            </td>
                                            <td class="text-center align-middle text-nowrap">
                                                <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $p['id']; ?>/acta" class="btn btn-xs btn-outline-success font-weight-bold px-2 py-1 mr-1" title="Acta Oficial Digital de Mesa">
                                                    <i class="fas fa-file-alt mr-1"></i> Acta
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $p['id']; ?>" class="btn btn-xs btn-outline-info font-weight-bold px-2 py-1" title="Ver Detalle y Ruta GPS">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Barra de Acciones de Resultados -->
                        <div class="card-footer bg-light py-3 d-flex flex-wrap align-items-center justify-content-between">
                            <div class="text-muted text-sm mb-2 mb-md-0">
                                <i class="fas fa-info-circle text-info mr-1"></i>
                                Ingrese marcadores y guarde en lote. Al presionar <strong>"Guardar y Concluir Disciplina"</strong>, el <strong>Medallero Olímpico se actualiza automáticamente</strong>.
                            </div>
                            <div class="text-right">
                                <button type="submit" name="accion" value="guardar" class="btn btn-primary font-weight-bold mr-2 shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Guardar Resultados
                                </button>
                                <?php if ($torneo['estado'] !== 'Finalizado'): ?>
                                    <button type="submit" name="concluir_disciplina" value="1" class="btn btn-success font-weight-bold shadow-sm" onclick="return confirm('¿Guardar los resultados y dar por CONCLUIDA esta disciplina? El sistema actualizará el Medallero Olímpico automáticamente para todos los usuarios.');">
                                        <i class="fas fa-trophy mr-1"></i> Guardar y Concluir Disciplina
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($torneo['tipo_torneo'] === 'Grupos_Playoff'): ?>
            <!-- Equipos Sin Grupo Asignado (Alerta de atención) -->
            <?php if (!empty($equiposSinGrupo)): ?>
                <div class="card card-warning card-outline shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold text-warning">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Equipos pendientes de asignación de Grupo (<?php echo count($equiposSinGrupo); ?>)
                        </h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Equipo</th>
                                    <th>Delegación</th>
                                    <th class="text-right" style="width: 280px;">Asignar a un Grupo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($equiposSinGrupo as $eqSin): ?>
                                    <tr>
                                        <td class="font-weight-bold"><?php echo e($eqSin['nombre']); ?></td>
                                        <td>
                                            <span class="badge" style="background-color: <?php echo e($eqSin['color_representativo']); ?>; color: #fff;">
                                                <?php echo e($eqSin['delegacion_nombre']); ?>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <?php if (!empty($grupos)): ?>
                                                <form action="<?php echo BASE_URL; ?>/grupos/asignar-equipo" method="post" class="form-inline justify-content-end">
                                                    <?php echo CsrfHelper::field(); ?>
                                                    <input type="hidden" name="equipo_id" value="<?php echo e($eqSin['id']); ?>">
                                                    <input type="hidden" name="torneo_id" value="<?php echo e($torneo['id']); ?>">
                                                    <select name="grupo_id" class="form-control form-control-sm mr-2" required>
                                                        <option value="">Seleccionar Grupo...</option>
                                                        <?php foreach ($grupos as $gOption): ?>
                                                            <option value="<?php echo e($gOption['id']); ?>"><?php echo e($gOption['nombre_grupo']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-warning text-dark font-weight-bold">
                                                        Asignar
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted text-sm">Cree un grupo primero</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sección de Grupos de Competencia -->
            <div class="row">
                <?php if (empty($grupos)): ?>
                    <div class="col-12">
                        <div class="card card-outline card-secondary py-5 text-center shadow-sm">
                            <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                            <h4 class="font-weight-bold text-dark">Aún no hay grupos creados para esta disciplina</h4>
                            <p class="text-muted">Crea grupos como <strong>Grupo A</strong>, <strong>Grupo B</strong> para organizar a los equipos participantes.</p>
                            <?php if (AuthHelper::isAdmin()): ?>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-warning text-dark font-weight-bold px-4" data-toggle="modal" data-target="#modalNuevoGrupo">
                                        <i class="fas fa-plus mr-1"></i> Crear Primer Grupo
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($grupos as $grupo): ?>
                        <div class="col-lg-6 mb-4">
                            <div class="card card-outline card-primary shadow-sm h-100">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h3 class="card-title font-weight-bold text-primary mb-0">
                                        <i class="fas fa-users mr-1"></i> <?php echo e($grupo['nombre_grupo']); ?>
                                    </h3>
                                    <div>
                                        <span class="badge badge-primary mr-2"><?php echo count($grupo['equipos']); ?> Equipos</span>
                                        <?php if (AuthHelper::isAdmin()): ?>
                                            <button type="button" class="btn btn-tool text-danger" data-toggle="modal" data-target="#modalDelGrupo<?php echo e($grupo['id']); ?>" title="Eliminar Grupo">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body p-0 table-responsive">
                                    <table class="table table-hover table-strip                                        <thead class="thead-light">
                                            <tr>
                                                <th>Equipo</th>
                                                <th>Delegación</th>
                                                <th class="text-center">Jugadores</th>
                                                <?php if (AuthHelper::isAdmin()): ?>
                                                    <th class="text-center text-nowrap" style="width: 180px;">Acciones</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($grupo['equipos'])): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">
                                                        <em>No hay equipos asignados a este grupo aún.</em>
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($grupo['equipos'] as $eq): ?>
                                                    <tr>
                                                        <td class="font-weight-bold">
                                                            <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/jugadores" class="text-dark">
                                                                <?php echo e($eq['nombre']); ?>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span class="badge" style="background-color: <?php echo e($eq['color_representativo']); ?>; color: #fff;">
                                                                <?php echo e($eq['delegacion_nombre']); ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/jugadores" class="badge badge-info px-2 py-1" title="Ver nómina">
                                                                <i class="fas fa-user-friends mr-1"></i> <?php echo e($eq['total_jugadores']); ?> atletas
                                                            </a>
                                                        </td>
                                                        <?php if (AuthHelper::isAdmin()): ?>
                                                            <td class="text-center text-nowrap">
                                                                <div class="d-inline-flex align-items-center justify-content-center" style="gap: 6px;">
                                                                    <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/jugadores" class="btn btn-xs btn-outline-primary font-weight-bold px-2 py-1" title="Ver Nómina">
                                                                        Nómina
                                                                    </a>
                                                                    <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Editar">
                                                                        Editar
                                                                    </a>
                                                                    <form action="<?php echo BASE_URL; ?>/grupos/desasignar-equipo/<?php echo e($eq['id']); ?>" method="post" class="d-inline" onsubmit="return confirm('¿Quitar este equipo del grupo?');">
                                                                        <?php echo CsrfHelper::field(); ?>
                                                                        <input type="hidden" name="torneo_id" value="<?php echo e($torneo['id']); ?>">
                                                                        <button type="submit" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" title="Quitar del Grupo">
                                                                            Quitar
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Modal Confirmar Eliminación de Grupo -->
                            <div class="modal fade" id="modalDelGrupo<?php echo e($grupo['id']); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title font-weight-bold">
                                                <i class="fas fa-exclamation-triangle mr-2"></i> Eliminar <?php echo e($grupo['nombre_grupo']); ?>
                                            </h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Está seguro de eliminar el <strong><?php echo e($grupo['nombre_grupo']); ?></strong>?</p>
                                            <p class="text-muted text-sm mb-0">Los equipos que pertenecen a este grupo quedarán sin grupo asignado, listos para ser reubicados.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <form action="<?php echo BASE_URL; ?>/grupos/<?php echo e($grupo['id']); ?>/eliminar" method="post" class="d-inline">
                                                <?php echo CsrfHelper::field(); ?>
                                                <input type="hidden" name="torneo_id" value="<?php echo e($torneo['id']); ?>">
                                                <button type="submit" class="btn btn-danger font-weight-bold">Sí, Eliminar Grupo</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Vista para Eliminación Directa y Clasificación General (Sin Grupos) -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-primary shadow-sm mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold text-primary mb-0">
                                <?php if ($torneo['tipo_torneo'] === 'Eliminatoria'): ?>
                                    <i class="fas fa-sitemap mr-1"></i> Participantes en Llaves de Eliminación Directa
                                <?php else: ?>
                                    <i class="fas fa-list-ol mr-1"></i> Delegaciones en Clasificación General
                                <?php endif; ?>
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-primary"><?php echo count($todosEquipos); ?> Participantes</span>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <?php if (empty($todosEquipos)): ?>
                                <div class="py-5 text-center text-muted">
                                    <i class="fas fa-users fa-3x text-secondary mb-3 d-block"></i>
                                    <h5 class="font-weight-bold text-dark">Aún no hay equipos o parejas inscritas en esta disciplina</h5>
                                    <p class="text-muted text-sm mb-3">Inscribe a las delegaciones participantes para programar los encuentros.</p>
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <a href="<?php echo BASE_URL; ?>/equipos/crear?torneo_id=<?php echo e($torneo['id']); ?>" class="btn btn-primary font-weight-bold px-3">
                                            <i class="fas fa-plus mr-1"></i> Inscribir Primer Participante
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Equipo / Representativo</th>
                                            <th>Delegación Perteneciente</th>
                                            <th class="text-center">Atletas / Jugadores</th>
                                            <?php if (AuthHelper::isAdmin()): ?>
                                                <th class="text-center text-nowrap" style="width: 220px;">Acciones</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $idx = 1; foreach ($todosEquipos as $eq): ?>
                                            <tr>
                                                <td class="font-weight-bold text-muted"><?php echo $idx++; ?></td>
                                                <td class="font-weight-bold">
                                                    <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/jugadores" class="text-dark">
                                                        <?php echo e($eq['nombre']); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge" style="background-color: <?php echo e($eq['color_representativo']); ?>; color: #fff;">
                                                        <?php echo e($eq['delegacion_nombre']); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/jugadores" class="badge badge-info px-2 py-1" title="Ver nómina de atletas">
                                                        <i class="fas fa-user-friends mr-1"></i> <?php echo e($eq['total_jugadores']); ?> atletas
                                                    </a>
                                                </td>
                                                <?php if (AuthHelper::isAdmin()): ?>
                                                    <td class="text-center text-nowrap">
                                                        <div class="d-inline-flex align-items-center justify-content-center" style="gap: 8px;">
                                                            <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/jugadores" class="btn btn-xs btn-outline-primary font-weight-bold px-2 py-1" title="Ver Plantilla de Jugadores">
                                                                Nómina
                                                            </a>
                                                            <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Editar">
                                                                Editar
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" data-toggle="modal" data-target="#modalDeleteEq<?php echo e($eq['id']); ?>" title="Eliminar">
                                                                Eliminar
                                                            </button>
                                                        </div>

                                                        <!-- Modal Confirmar Eliminación -->
                                                        <div class="modal fade" id="modalDeleteEq<?php echo e($eq['id']); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content text-left">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title font-weight-bold">
                                                                            <i class="fas fa-exclamation-triangle mr-2"></i> Confirmar Eliminación
                                                                        </h5>
                                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p class="mb-2">¿Estás seguro de que deseas eliminar al equipo <strong><?php echo e($eq['nombre']); ?></strong>?</p>
                                                                        <p class="text-muted text-sm mb-0">Esta acción removerá el equipo y a sus atletas asignados de esta disciplina.</p>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                                                                        <form action="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/eliminar" method="post" class="d-inline">
                                                                            <?php echo CsrfHelper::field(); ?>
                                                                            <button type="submit" class="btn btn-danger font-weight-bold">
                                                                                <i class="fas fa-trash-alt mr-1"></i> Sí, Eliminar
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Modal Crear Nuevo Grupo -->
<div class="modal fade" id="modalNuevoGrupo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-layer-group mr-2"></i> Crear Grupo en esta Disciplina
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo e($torneo['id']); ?>/grupos/guardar" method="post">
                <?php echo CsrfHelper::field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_grupo">Nombre del Grupo <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_grupo" id="nombre_grupo" class="form-control" placeholder="Ej. Grupo A, Grupo B, Grupo C..." required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="orden">Orden de Visualización</label>
                        <input type="number" name="orden" id="orden" class="form-control" value="<?php echo count($grupos) + 1; ?>" min="1">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning font-weight-bold text-dark">
                        <i class="fas fa-save mr-1"></i> Guardar Grupo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Concluir Disciplina -->
<div class="modal fade" id="modalConcluirDisciplina" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-flag-checkered mr-2"></i> Dar por Concluida la Disciplina
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo (int)$torneo['id']; ?>/concluir" method="post">
                <?php echo CsrfHelper::field(); ?>
                <div class="modal-body text-left">
                    <p class="mb-2">¿Está seguro de que desea dar por concluida la disciplina <strong><?php echo e($torneo['nombre']); ?></strong>?</p>
                    <div class="alert alert-info py-2 px-3 text-sm mb-0">
                        <i class="fas fa-medal mr-1 text-warning"></i>
                        <strong>Actualización Automática del Medallero:</strong><br>
                        El sistema evaluará los encuentros disputados (la Gran Final o la Tabla Acumulada de Posiciones) y asignará automáticamente las medallas de <strong>Oro 🥇</strong>, <strong>Plata 🥈</strong> y <strong>Bronce 🥉</strong> a las delegaciones correspondientes, mostrándolas en tiempo real a todos los deportistas y público en general.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning font-weight-bold text-dark">
                        <i class="fas fa-trophy mr-1"></i> Sí, Concluir y Proclamar Medallero
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

