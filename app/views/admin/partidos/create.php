<?php
/**
 * Formulario para programar un nuevo encuentro deportivo
 *
 * @var array $torneos
 * @var array $canchas
 * @var array $grupos
 * @var array $equipos
 * @var int|null $torneoId
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="color: #0066B2;">
                    Programar Encuentro Deportivo
                </h1>
                <span class="text-muted text-sm">Asignación de equipos, sede, fecha y franja horaria</span>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/partidos" class="btn btn-outline-secondary font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al Fixture
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
                        <h3 class="card-title font-weight-bold mb-0">Detalles de Programación</h3>
                    </div>
                    <form action="<?php echo BASE_URL; ?>/partidos/guardar" method="post" id="formPartido" onsubmit="return validarEquipos();">
                        <?php echo CsrfHelper::field(); ?>
                        
                        <div class="card-body">
                            <!-- Fila 1: Torneo y Grupo -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="torneo_id" class="font-weight-bold text-dark">Torneo <span class="text-danger">*</span></label>
                                    <select name="torneo_id" id="torneo_id" class="form-control" required onchange="cargarEquiposTorneo(this.value);">
                                        <option value="">-- Seleccionar Torneo --</option>
                                        <?php foreach ($torneos as $t): ?>
                                            <option value="<?php echo e($t['id']); ?>" <?php echo ($torneoId == $t['id']) ? 'selected' : ''; ?>>
                                                <?php echo e($t['nombre']); ?> (<?php echo e($t['disciplina_nombre']); ?> - <?php echo e($t['rama']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted">Seleccione el torneo para cargar los equipos habilitados.</small>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="grupo_id" class="font-weight-bold text-dark">Grupo (Opcional)</label>
                                    <select name="grupo_id" id="grupo_id" class="form-control">
                                        <option value="">-- Sin Grupo / Todos --</option>
                                        <?php foreach ($grupos as $g): ?>
                                            <option value="<?php echo e($g['id']); ?>">
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
                                        <option value="Grupos" selected>Fase de Grupos</option>
                                        <option value="Octavos">Octavos de Final</option>
                                        <option value="Cuartos">Cuartos de Final</option>
                                        <option value="Semifinal">Semifinal</option>
                                        <option value="Tercer_Puesto">Partido por Tercer Puesto (Bronce)</option>
                                        <option value="Final">Gran Final (Oro y Plata)</option>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="jornada" class="font-weight-bold text-dark">Jornada / Fecha <span class="text-danger">*</span></label>
                                    <input type="text" name="jornada" id="jornada" class="form-control" placeholder="Ej. Jornada 1, Fecha 2, Llave A" value="Jornada 1" required>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Fila 3: Equipos Enfrentados -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="equipo_local_id" class="font-weight-bold text-dark">Equipo Local <span class="text-danger">*</span></label>
                                    <select name="equipo_local_id" id="equipo_local_id" class="form-control" required>
                                        <option value="">-- Seleccionar Equipo Local --</option>
                                        <?php foreach ($equipos as $eq): ?>
                                            <option value="<?php echo e($eq['id']); ?>">
                                                <?php echo e($eq['nombre']); ?> (<?php echo e($eq['delegacion_nombre']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="equipo_visitante_id" class="font-weight-bold text-dark">Equipo Visitante <span class="text-danger">*</span></label>
                                    <select name="equipo_visitante_id" id="equipo_visitante_id" class="form-control" required>
                                        <option value="">-- Seleccionar Equipo Visitante --</option>
                                        <?php foreach ($equipos as $eq): ?>
                                            <option value="<?php echo e($eq['id']); ?>">
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
                                            <option value="<?php echo e($c['id']); ?>">
                                                <?php echo e($c['sede_nombre']); ?> &mdash; <?php echo e($c['nombre_cancha']); ?> (<?php echo e($c['tipo_superficie'] ?? 'General'); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-text text-muted">El sistema detectará automáticamente posibles cruces de horarios.</small>
                                </div>

                                <div class="col-md-3 form-group">
                                    <label for="fecha_partido" class="font-weight-bold text-dark">Fecha del Encuentro</label>
                                    <input type="date" name="fecha_partido" id="fecha_partido" class="form-control">
                                </div>

                                <div class="col-md-3 form-group">
                                    <label for="hora_partido" class="font-weight-bold text-dark">Hora</label>
                                    <input type="time" name="hora_partido" id="hora_partido" class="form-control">
                                </div>
                            </div>

                            <!-- Fila 5: Estado y Observaciones -->
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="estado" class="font-weight-bold text-dark">Estado Inicial</label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="Programado" selected>Programado</option>
                                        <option value="En_Juego">En Juego</option>
                                    </select>
                                </div>

                                <div class="col-md-8 form-group">
                                    <label for="observaciones" class="font-weight-bold text-dark">Observaciones / Notas de Programación</label>
                                    <textarea name="observaciones" id="observaciones" rows="2" class="form-control" placeholder="Instrucciones adicionales, terna arbitral o notas..."></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer bg-light text-right">
                            <a href="<?php echo BASE_URL; ?>/partidos" class="btn btn-secondary mr-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Guardar y Programar
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
    if (!torneoId) {
        document.getElementById('equipo_local_id').innerHTML = '<option value="">-- Seleccionar Equipo Local --</option>';
        document.getElementById('equipo_visitante_id').innerHTML = '<option value="">-- Seleccionar Equipo Visitante --</option>';
        document.getElementById('grupo_id').innerHTML = '<option value="">-- Sin Grupo / Todos --</option>';
        return;
    }

    fetch('<?php echo BASE_URL; ?>/api/torneos/' + torneoId + '/equipos')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                // Actualizar equipos
                var optLocal = '<option value="">-- Seleccionar Equipo Local --</option>';
                var optVis   = '<option value="">-- Seleccionar Equipo Visitante --</option>';

                data.equipos.forEach(function(eq) {
                    var optText = eq.nombre + ' (' + (eq.delegacion_nombre || '') + ')';
                    optLocal += '<option value="' + eq.id + '">' + optText + '</option>';
                    optVis   += '<option value="' + eq.id + '">' + optText + '</option>';
                });

                document.getElementById('equipo_local_id').innerHTML = optLocal;
                document.getElementById('equipo_visitante_id').innerHTML = optVis;

                // Actualizar grupos
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
