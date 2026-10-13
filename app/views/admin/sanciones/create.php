<?php
/**
 * Formulario para registrar una nueva sanción disciplinaria
 *
 * @var array $torneos
 * @var array $equipos
 * @var array $partidos
 * @var int|null $torneoId
 * @var int|null $jugadorId
 * @var int|null $partidoId
 * @var array|null $jugadorPreseleccionado
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Registrar Sanción Disciplinaria
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/sanciones" class="btn btn-secondary font-weight-bold">
                    Volver al Boletín
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card card-cosmol shadow-sm mb-4">
                    <div class="card-header py-2">
                        <h3 class="card-title font-weight-bold mb-0">
                            <i class="fas fa-file-signature text-primary mr-1"></i> Expediente de Falta Disciplinaria
                        </h3>
                    </div>

                    <form action="<?php echo BASE_URL; ?>/sanciones/guardar" method="post" id="formSancion">
                        <?php echo CsrfHelper::field(); ?>

                        <div class="card-body">

                            <!-- Fila 1: Torneo y Equipo -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="torneo_id" class="font-weight-bold text-dark">
                                        Torneo Oficial <span class="text-danger">*</span>
                                    </label>
                                    <select name="torneo_id" id="torneo_id" class="form-control" required onchange="cargarEquiposYPartidos(this.value);">
                                        <option value="">-- Seleccionar Torneo --</option>
                                        <?php foreach ($torneos as $t): ?>
                                            <option value="<?php echo e($t['id']); ?>" <?php echo ($torneoId == $t['id']) ? 'selected' : ''; ?>>
                                                <?php echo e($t['nombre']); ?> (<?php echo e($t['disciplina_nombre']); ?> - <?php echo e($t['rama']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="equipo_id" class="font-weight-bold text-dark">
                                        Equipo del Atleta <span class="text-danger">*</span>
                                    </label>
                                    <select name="equipo_id" id="equipo_id" class="form-control" required onchange="cargarJugadoresEquipo(this.value);">
                                        <option value="">-- Seleccionar Equipo --</option>
                                        <?php foreach ($equipos as $eq): ?>
                                            <?php
                                            $isSelEq = false;
                                            if ($jugadorPreseleccionado && $jugadorPreseleccionado['equipo_id'] == $eq['id']) {
                                                $isSelEq = true;
                                            }
                                            ?>
                                            <option value="<?php echo e($eq['id']); ?>" <?php echo $isSelEq ? 'selected' : ''; ?>>
                                                <?php echo e($eq['nombre']); ?> (<?php echo e($eq['delegacion_nombre']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Fila 2: Jugador y Partido (Opcional) -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="jugador_id" class="font-weight-bold text-dark">
                                        Atleta a Sancionar <span class="text-danger">*</span>
                                    </label>
                                    <select name="jugador_id" id="jugador_id" class="form-control" required>
                                        <?php if ($jugadorPreseleccionado): ?>
                                            <option value="<?php echo e($jugadorPreseleccionado['id']); ?>" selected>
                                                #<?php echo e($jugadorPreseleccionado['dorsal']); ?> <?php echo e($jugadorPreseleccionado['nombres']); ?> <?php echo e($jugadorPreseleccionado['apellidos']); ?> (CI: <?php echo e($jugadorPreseleccionado['documento']); ?>)
                                            </option>
                                        <?php else: ?>
                                            <option value="">-- Primero elija un Equipo --</option>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted">La lista de atletas se actualiza al seleccionar el equipo.</small>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="partido_id" class="font-weight-bold text-dark">
                                        Partido del Hecho (Opcional)
                                    </label>
                                    <select name="partido_id" id="partido_id" class="form-control">
                                        <option value="">-- Resolución de Mesa / Sin Partido Asociado --</option>
                                        <?php foreach ($partidos as $p): ?>
                                            <option value="<?php echo e($p['id']); ?>" <?php echo ($partidoId == $p['id']) ? 'selected' : ''; ?>>
                                                #<?php echo e($p['id']); ?>: <?php echo e($p['local_nombre']); ?> vs <?php echo e($p['visitante_nombre']); ?> (<?php echo e($p['jornada']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Fila 3: Tipo de Sanción y Fechas de Suspensión -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="tipo_sancion" class="font-weight-bold text-dark">
                                        Naturaleza de la Sanción <span class="text-danger">*</span>
                                    </label>
                                    <select name="tipo_sancion" id="tipo_sancion" class="form-control" required onchange="ajustarFechasPorTipo(this.value);">
                                        <option value="ROJA">Tarjeta Roja Directa / Doble Amarilla (Inhabilita)</option>
                                        <option value="PARTIDOS_SUSPENSION">Suspensión por Fechas Disciplinarias</option>
                                        <option value="EXPULSION">Expulsión Definitiva del Torneo</option>
                                        <option value="AMARILLA">Tarjeta Amarilla (Amonestación de advertencia)</option>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="partidos_suspension" class="font-weight-bold text-dark">
                                        Partidos de Suspensión a Cumplir <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="0" max="99" name="partidos_suspension" id="partidos_suspension" class="form-control" value="1" required>
                                    <small id="ayudaSuspension" class="form-text text-muted">
                                        Cantidad de fechas que el jugador no podrá participar. (Para amarillas ingrese 0).
                                    </small>
                                </div>
                            </div>

                            <!-- Fila 4: Motivo e Informe Oficial -->
                            <div class="form-group mb-0">
                                <label for="motivo" class="font-weight-bold text-dark">
                                    Motivo, Informe Arbitral y Observaciones <span class="text-danger">*</span>
                                </label>
                                <textarea name="motivo" id="motivo" rows="4" class="form-control" placeholder="Detalle la infracción reglamentaria cometida, minuto del juego, informe del árbitro o resolución del tribunal..." required minlength="5"></textarea>
                            </div>

                        </div>

                        <div class="card-footer bg-light text-right py-2">
                            <a href="<?php echo BASE_URL; ?>/sanciones" class="btn btn-secondary mr-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Guardar Sanción
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function ajustarFechasPorTipo(tipo) {
    var inputFechas = document.getElementById('partidos_suspension');
    var ayuda = document.getElementById('ayudaSuspension');

    if (tipo === 'AMARILLA') {
        inputFechas.value = 0;
        ayuda.textContent = 'Amonestación sin suspensión directa inmediata.';
    } else if (tipo === 'EXPULSION') {
        inputFechas.value = 99;
        ayuda.textContent = 'Expulsión definitiva de la competencia.';
    } else if (tipo === 'ROJA') {
        inputFechas.value = 1;
        ayuda.textContent = 'Suspensión reglamentaria mínima de 1 fecha para el siguiente encuentro.';
    } else {
        inputFechas.value = 2;
        ayuda.textContent = 'Fechas dictadas por resolución del comité disciplinario.';
    }
}

function cargarEquiposYPartidos(torneoId) {
    if (!torneoId) {
        document.getElementById('equipo_id').innerHTML = '<option value="">-- Primero elija un Torneo --</option>';
        document.getElementById('jugador_id').innerHTML = '<option value="">-- Primero elija un Equipo --</option>';
        return;
    }

    fetch('<?php echo BASE_URL; ?>/api/torneos/' + torneoId + '/equipos')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                var optEquipos = '<option value="">-- Seleccionar Equipo --</option>';
                data.equipos.forEach(function(eq) {
                    var desc = eq.nombre + ' (' + (eq.delegacion_nombre || '') + ')';
                    optEquipos += '<option value="' + eq.id + '">' + desc + '</option>';
                });
                document.getElementById('equipo_id').innerHTML = optEquipos;
                document.getElementById('jugador_id').innerHTML = '<option value="">-- Primero elija un Equipo --</option>';
            }
        })
        .catch(function(err) {
            console.error('Error cargando equipos:', err);
        });
}

function cargarJugadoresEquipo(equipoId) {
    if (!equipoId) {
        document.getElementById('jugador_id').innerHTML = '<option value="">-- Primero elija un Equipo --</option>';
        return;
    }

    fetch('<?php echo BASE_URL; ?>/api/equipos/' + equipoId + '/jugadores-json')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success && data.jugadores) {
                var optJugadores = '<option value="">-- Seleccionar Atleta --</option>';
                data.jugadores.forEach(function(j) {
                    var desc = '#' + j.dorsal + ' ' + j.nombres + ' ' + j.apellidos + ' (' + (j.posicion || 'Atleta') + ')';
                    optJugadores += '<option value="' + j.id + '">' + desc + '</option>';
                });
                document.getElementById('jugador_id').innerHTML = optJugadores;
            }
        })
        .catch(function(err) {
            console.error('Error cargando jugadores:', err);
        });
}
</script>
