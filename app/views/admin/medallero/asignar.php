<?php
/**
 * Vista: Asignación de Podio (Oro, Plata, Bronce) para un Torneo
 *
 * @var array       $torneo
 * @var array       $delegaciones
 * @var array       $asignadas     Indexado por 'ORO', 'PLATA', 'BRONCE'
 * @var string      $csrfToken
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-7">
                <h1 class="card-title mb-0 card-title-cosmol">
                    <i class="fas fa-award mr-2 text-warning"></i>Asignar Podio Olímpico
                </h1>
                <small class="text-muted d-block mt-1">
                    Torneo: <strong><?php echo htmlspecialchars($torneo['nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                    (Rama: <?php echo htmlspecialchars($torneo['rama'], ENT_QUOTES, 'UTF-8'); ?>)
                </small>
            </div>
            <div class="col-sm-5 text-right">
                <a href="<?php echo BASE_URL; ?>/medallero/gestion" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Volver a Gestión
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <?php if (empty($delegaciones)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                No hay delegaciones/equipos registrados en esta disciplina. Debe inscribir equipos antes de asignar medallas.
            </div>
        <?php else: ?>

            <form action="<?php echo BASE_URL; ?>/medallero/disciplinas/<?php echo (int)$torneo['id']; ?>/guardar" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">

                <div class="row">
                    <!-- Medalla de Oro -->
                    <div class="col-md-4 mb-3">
                        <div class="card card-outline card-warning shadow-sm h-100">
                            <div class="card-header bg-warning text-dark text-center font-weight-bold">
                                <i class="fas fa-medal mr-1"></i> 1º LUGAR — MEDALLA DE ORO 🥇
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="delegacion_oro">Delegación Ganadora:</label>
                                    <select name="delegacion_oro" id="delegacion_oro" class="form-control select2">
                                        <option value="">-- Sin Asignar --</option>
                                        <?php
                                        $oroDelId = isset($asignadas['ORO']) ? (int)$asignadas['ORO']['delegacion_id'] : 0;
                                        foreach ($delegaciones as $d):
                                        ?>
                                            <option value="<?php echo (int)$d['id']; ?>" <?php echo $oroDelId === (int)$d['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($d['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                                <?php if (!empty($d['alias'])): ?> (<?php echo htmlspecialchars($d['alias'], ENT_QUOTES, 'UTF-8'); ?>)<?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label for="puntos_oro">Puntos Olímpicos:</label>
                                    <input type="number" name="puntos_oro" id="puntos_oro" class="form-control"
                                           value="<?php echo isset($asignadas['ORO']) ? (int)$asignadas['ORO']['puntos_olimpicos'] : 10; ?>"
                                           min="0" max="100" required>
                                    <small class="form-text text-muted">Estándar internacional: 10 puntos</small>
                                </div>
                            </div>
                            <?php if (isset($asignadas['ORO'])): ?>
                                <div class="card-footer bg-light text-center py-2">
                                    <small class="text-success font-weight-bold">
                                        <i class="fas fa-check mr-1"></i> Asignada actualmente
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Medalla de Plata -->
                    <div class="col-md-4 mb-3">
                        <div class="card card-outline card-secondary shadow-sm h-100">
                            <div class="card-header bg-secondary text-white text-center font-weight-bold">
                                <i class="fas fa-medal mr-1"></i> 2º LUGAR — MEDALLA DE PLATA 🥈
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="delegacion_plata">Delegación Subcampeona:</label>
                                    <select name="delegacion_plata" id="delegacion_plata" class="form-control select2">
                                        <option value="">-- Sin Asignar --</option>
                                        <?php
                                        $plataDelId = isset($asignadas['PLATA']) ? (int)$asignadas['PLATA']['delegacion_id'] : 0;
                                        foreach ($delegaciones as $d):
                                        ?>
                                            <option value="<?php echo (int)$d['id']; ?>" <?php echo $plataDelId === (int)$d['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($d['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                                <?php if (!empty($d['alias'])): ?> (<?php echo htmlspecialchars($d['alias'], ENT_QUOTES, 'UTF-8'); ?>)<?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label for="puntos_plata">Puntos Olímpicos:</label>
                                    <input type="number" name="puntos_plata" id="puntos_plata" class="form-control"
                                           value="<?php echo isset($asignadas['PLATA']) ? (int)$asignadas['PLATA']['puntos_olimpicos'] : 7; ?>"
                                           min="0" max="100" required>
                                    <small class="form-text text-muted">Estándar internacional: 7 puntos</small>
                                </div>
                            </div>
                            <?php if (isset($asignadas['PLATA'])): ?>
                                <div class="card-footer bg-light text-center py-2">
                                    <small class="text-success font-weight-bold">
                                        <i class="fas fa-check mr-1"></i> Asignada actualmente
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Medalla de Bronce -->
                    <div class="col-md-4 mb-3">
                        <div class="card card-outline shadow-sm h-100" style="border-top-color: #cd7f32;">
                            <div class="card-header text-white text-center font-weight-bold" style="background-color: #cd7f32;">
                                <i class="fas fa-medal mr-1"></i> 3º LUGAR — MEDALLA DE BRONCE 🥉
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="delegacion_bronce">Delegación Tercer Puesto:</label>
                                    <select name="delegacion_bronce" id="delegacion_bronce" class="form-control select2">
                                        <option value="">-- Sin Asignar --</option>
                                        <?php
                                        $bronceDelId = isset($asignadas['BRONCE']) ? (int)$asignadas['BRONCE']['delegacion_id'] : 0;
                                        foreach ($delegaciones as $d):
                                        ?>
                                            <option value="<?php echo (int)$d['id']; ?>" <?php echo $bronceDelId === (int)$d['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($d['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                                <?php if (!empty($d['alias'])): ?> (<?php echo htmlspecialchars($d['alias'], ENT_QUOTES, 'UTF-8'); ?>)<?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label for="puntos_bronce">Puntos Olímpicos:</label>
                                    <input type="number" name="puntos_bronce" id="puntos_bronce" class="form-control"
                                           value="<?php echo isset($asignadas['BRONCE']) ? (int)$asignadas['BRONCE']['puntos_olimpicos'] : 5; ?>"
                                           min="0" max="100" required>
                                    <small class="form-text text-muted">Estándar internacional: 5 puntos</small>
                                </div>
                            </div>
                            <?php if (isset($asignadas['BRONCE'])): ?>
                                <div class="card-footer bg-light text-center py-2">
                                    <small class="text-success font-weight-bold">
                                        <i class="fas fa-check mr-1"></i> Asignada actualmente
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body text-right">
                        <a href="<?php echo BASE_URL; ?>/medallero/gestion" class="btn btn-secondary mr-2">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success font-weight-bold px-4">
                            <i class="fas fa-save mr-1"></i> Guardar Podio
                        </button>
                    </div>
                </div>
            </form>

            <?php if (!empty($asignadas)): ?>
                <!-- Tabla de Medallas Registradas con opción de eliminación -->
                <div class="card card-outline card-secondary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-trash-alt mr-2 text-danger"></i>Eliminar Medallas Registradas
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Medalla</th>
                                        <th>Delegación Asignada</th>
                                        <th>Puntos</th>
                                        <th class="text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (['ORO', 'PLATA', 'BRONCE'] as $tipo): ?>
                                        <?php if (isset($asignadas[$tipo])): ?>
                                            <tr>
                                                <td>
                                                    <?php if ($tipo === 'ORO'): ?>
                                                        <span class="badge badge-warning text-dark font-weight-bold">🥇 ORO</span>
                                                    <?php elseif ($tipo === 'PLATA'): ?>
                                                        <span class="badge badge-secondary font-weight-bold">🥈 PLATA</span>
                                                    <?php else: ?>
                                                        <span class="badge font-weight-bold text-white" style="background-color: #cd7f32;">🥉 BRONCE</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($asignadas[$tipo]['delegacion_nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary"><?php echo (int)$asignadas[$tipo]['puntos_olimpicos']; ?> pts</span>
                                                </td>
                                                <td class="text-right">
                                                    <form action="<?php echo BASE_URL; ?>/medallero/<?php echo (int)$asignadas[$tipo]['id']; ?>/eliminar"
                                                          method="POST" style="display:inline-block;"
                                                          onsubmit="return confirm('¿Está seguro de eliminar la medalla de <?php echo $tipo; ?>?');">
                                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar medalla">
                                                            <i class="fas fa-trash mr-1"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>
