<?php
/**
 * Formulario de reprogramación y edición de un partido
 *
 * @var array $partido
 * @var array $torneos
 * @var array $canchas
 * @var array $grupos
 * @var array $equipos
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Reprogramar / Editar Encuentro #<?php echo e($partido['id']); ?>
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>" class="btn btn-secondary font-weight-bold">
                    Volver a la Ficha
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card card-cosmol shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold mb-0">Modificar Datos de Programación</h3>
                    </div>
                    <form action="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>/actualizar" method="post" id="formPartidoEdit" onsubmit="return validarEquipos();">
                        <?php echo CsrfHelper::field(); ?>
                        
                        <div class="card-body">
                            <!-- Fila 1: Torneo y Grupo -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="torneo_id" class="font-weight-bold text-dark">Torneo <span class="text-danger">*</span></label>
                                    <select name="torneo_id" id="torneo_id" class="form-control" required onchange="cargarEquiposTorneo(this.value);">
                                        <?php foreach ($torneos as $t): ?>
                                            <option value="<?php echo e($t['id']); ?>" <?php echo ($partido['torneo_id'] == $t['id']) ? 'selected' : ''; ?>>
                                                <?php echo e($t['nombre']); ?> (<?php echo e($t['disciplina_nombre']); ?> - <?php echo e($t['rama']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="grupo_id" class="font-weight-bold text-dark">Grupo</label>
                                    <select name="grupo_id" id="grupo_id" class="form-control">
                                        <option value="">-- Sin Grupo / Todos --</option>
                                        <?php foreach ($grupos as $g): ?>
                                            <option value="<?php echo e($g['id']); ?>" <?php echo ($partido['grupo_id'] == $g['id']) ? 'selected' : ''; ?>>
                                                <?php echo e($g['nombre_grupo']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Fila 2: Fase y Jornada -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="fase" class="font-weight-bold text-dark">Fase del Torneo <span class="text-danger">*</span></label>
                                    <select name="fase" id="fase" class="form-control" required>
                                        <option value="Grupos" <?php echo ($partido['fase'] === 'Grupos') ? 'selected' : ''; ?>>Fase de Grupos</option>
                                        <option value="Octavos" <?php echo ($partido['fase'] === 'Octavos') ? 'selected' : ''; ?>>Octavos de Final</option>
                                        <option value="Cuartos" <?php echo ($partido['fase'] === 'Cuartos') ? 'selected' : ''; ?>>Cuartos de Final</option>
                                        <option value="Semifinal" <?php echo ($partido['fase'] === 'Semifinal') ? 'selected' : ''; ?>>Semifinal</option>
                                        <option value="Tercer_Puesto" <?php echo ($partido['fase'] === 'Tercer_Puesto') ? 'selected' : ''; ?>>Partido por Tercer Puesto (Bronce)</option>
                                        <option value="Final" <?php echo ($partido['fase'] === 'Final') ? 'selected' : ''; ?>>Gran Final (Oro y Plata)</option>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="jornada" class="font-weight-bold text-dark">Jornada / Fecha <span class="text-danger">*</span></label>
                                    <input type="text" name="jornada" id="jornada" class="form-control" value="<?php echo e($partido['jornada']); ?>" required>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Fila 3: Equipos Enfrentados -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="equipo_local_id" class="font-weight-bold text-dark">Equipo Local <span class="text-danger">*</span></label>
                                    <select name="equipo_local_id" id="equipo_local_id" class="form-control" required>
                                        <?php foreach ($equipos as $eq): ?>
                                            <option value="<?php echo e($eq['id']); ?>" <?php echo ($partido['equipo_local_id'] == $eq['id']) ? 'selected' : ''; ?>>
                                                <?php echo e($eq['nombre']); ?> (<?php echo e($eq['delegacion_nombre']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="equipo_visitante_id" class="font-weight-bold text-dark">Equipo Visitante <span class="text-danger">*</span></label>
                                    <select name="equipo_visitante_id" id="equipo_visitante_id" class="form-control" required>
                                        <?php foreach ($equipos as $eq): ?>
                                            <option value="<?php echo e($eq['id']); ?>" <?php echo ($partido['equipo_visitante_id'] == $eq['id']) ? 'selected' : ''; ?>>
                                                <?php echo e($eq['nombre']); ?> (<?php echo e($eq['delegacion_nombre']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Fila 4: Sede, Cancha, Fecha y Hora -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="cancha_id" class="font-weight-bold text-dark">Cancha / Recinto Deportivo</label>
                                    <select name="cancha_id" id="cancha_id" class="form-control">
                                        <option value="">-- Por definir / Sin asignar --</option>
                                        <?php foreach ($canchas as $c): ?>
                                            <option value="<?php echo e($c['id']); ?>" <?php echo ($partido['cancha_id'] == $c['id']) ? 'selected' : ''; ?>>
                                                <?php echo e($c['sede_nombre']); ?> &mdash; <?php echo e($c['nombre_cancha']); ?> (<?php echo e($c['tipo_superficie'] ?? 'General'); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-3 form-group">
                                    <label for="fecha_partido" class="font-weight-bold text-dark">Fecha del Encuentro</label>
                                    <input type="date" name="fecha_partido" id="fecha_partido" class="form-control" value="<?php echo e($partido['fecha_partido'] ?? ''); ?>">
                                </div>

                                <div class="col-md-3 form-group">
                                    <label for="hora_partido" class="font-weight-bold text-dark">Hora</label>
                                    <input type="time" name="hora_partido" id="hora_partido" class="form-control" value="<?php echo e($partido['hora_partido'] ? substr($partido['hora_partido'], 0, 5) : ''); ?>">
                                </div>
                            </div>

                            <!-- Fila 5: Estado y Observaciones -->
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="estado" class="font-weight-bold text-dark">Estado del Partido</label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="Programado" <?php echo ($partido['estado'] === 'Programado') ? 'selected' : ''; ?>>Programado</option>
                                        <option value="En_Juego" <?php echo ($partido['estado'] === 'En_Juego') ? 'selected' : ''; ?>>En Juego</option>
                                        <option value="Finalizado" <?php echo ($partido['estado'] === 'Finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                                        <option value="Suspendido" <?php echo ($partido['estado'] === 'Suspendido') ? 'selected' : ''; ?>>Suspendido</option>
                                        <option value="WO" <?php echo ($partido['estado'] === 'WO') ? 'selected' : ''; ?>>W.O.</option>
                                    </select>
                                </div>

                                <div class="col-md-8 form-group">
                                    <label for="observaciones" class="font-weight-bold text-dark">Observaciones / Notas</label>
                                    <textarea name="observaciones" id="observaciones" rows="2" class="form-control"><?php echo e($partido['observaciones'] ?? ''); ?></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer bg-light text-right">
                            <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>" class="btn btn-secondary mr-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Actualizar Encuentro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validarEquipos() {
    var local = document.getElementById('equipo_local_id').value;
    var visitante = document.getElementById('equipo_visitante_id').value;

    if (local && visitante && local === visitante) {
        alert('El equipo local y el equipo visitante no pueden ser el mismo.');
        return false;
    }
    return true;
}

function cargarEquiposTorneo(torneoId) {
    if (!torneoId) return;

    fetch('<?php echo BASE_URL; ?>/api/torneos/' + torneoId + '/equipos')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                var optLocal = '<option value="">-- Seleccionar Equipo Local --</option>';
                var optVis   = '<option value="">-- Seleccionar Equipo Visitante --</option>';

                data.equipos.forEach(function(eq) {
                    var optText = eq.nombre + ' (' + (eq.delegacion_nombre || '') + ')';
                    optLocal += '<option value="' + eq.id + '">' + optText + '</option>';
                    optVis   += '<option value="' + eq.id + '">' + optText + '</option>';
                });

                document.getElementById('equipo_local_id').innerHTML = optLocal;
                document.getElementById('equipo_visitante_id').innerHTML = optVis;

                var optGrupos = '<option value="">-- Sin Grupo / Todos --</option>';
                data.grupos.forEach(function(g) {
                    optGrupos += '<option value="' + g.id + '">' + g.nombre_grupo + '</option>';
                });
                document.getElementById('grupo_id').innerHTML = optGrupos;
            }
        })
        .catch(function(err) {
            console.error('Error cargando equipos del torneo:', err);
        });
}
</script>
