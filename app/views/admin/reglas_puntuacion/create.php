<?php
/**
 * Vista de Creación de Disciplina y Reglas de Puntuación
 *
 * @var array|null $old
 */
$nombre = isset($old['nombre']) ? $old['nombre'] : '';
$descripcion = isset($old['descripcion']) ? $old['descripcion'] : '';
$estado = isset($old['estado']) ? $old['estado'] : 'Activa';
$formatoSets = isset($old['formato_sets']) ? (int)$old['formato_sets'] : 0;
$setsParaGanar = isset($old['sets_para_ganar']) ? (int)$old['sets_para_ganar'] : 0;
$permiteEmpate = isset($old['permite_empate']) ? (int)$old['permite_empate'] : 1;
$ptsVictoria = isset($old['pts_victoria']) ? $old['pts_victoria'] : 3;
$ptsEmpate = isset($old['pts_empate']) ? $old['pts_empate'] : 1;
$ptsDerrota = isset($old['pts_derrota']) ? $old['pts_derrota'] : 0;
$ptsDerrotaAjustada = isset($old['pts_derrota_ajustada']) ? $old['pts_derrota_ajustada'] : 0;
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Registrar Nueva Disciplina
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/reglas-puntuacion" class="btn btn-secondary btn-sm font-weight-bold">
                    Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <form action="<?php echo BASE_URL; ?>/reglas-puntuacion/guardar" method="post" id="formDisciplina">
            <?php echo CsrfHelper::field(); ?>

            <div class="row">
                <!-- Columna Izquierda: Información General -->
                <div class="col-lg-6 mb-3">
                    <div class="card card-outline shadow-sm h-100 mb-0">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold text-dark">Información General</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre">Nombre de la Disciplina <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required value="<?php echo e($nombre); ?>" placeholder="Ej. Fútbol, Futsal, Voleibol Mixto, Cacho...">
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción o Modalidad</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="4" placeholder="Detalles de la disciplina, modalidad de juego, reglas específicas..."><?php echo e($descripcion); ?></textarea>
                            </div>

                            <div class="form-group mb-0">
                                <label for="estado">Estado</label>
                                <select name="estado" id="estado" class="form-control">
                                    <option value="Activa" <?php echo $estado === 'Activa' ? 'selected' : ''; ?>>Activa</option>
                                    <option value="Inactiva" <?php echo $estado === 'Inactiva' ? 'selected' : ''; ?>>Inactiva</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Reglas y Formato de Juego -->
                <div class="col-lg-6 mb-3">
                    <div class="card card-outline shadow-sm h-100 mb-0">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold text-dark">Reglas y Formato de Juego</h3>
                        </div>
                        <div class="card-body">
                            <!-- Plantilla rápida -->
                            <div class="form-group mb-3 pb-2 border-bottom">
                                <label class="text-xs text-uppercase text-muted font-weight-bold d-block mb-1">Cargar plantilla rápida de deporte:</label>
                                <div class="btn-group btn-group-sm flex-wrap w-100" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="aplicarPlantilla('futbol')">Fútbol / Futsal</button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="aplicarPlantilla('basquetbol')">Básquetbol</button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="aplicarPlantilla('voleibol')">Voleibol</button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="aplicarPlantilla('recreativo')">Recreativo</button>
                                </div>
                            </div>

                            <!-- Formato de Juego -->
                            <div class="form-group">
                                <label for="formato_sets" class="font-weight-bold">Formato de Juego <span class="text-danger">*</span></label>
                                <select name="formato_sets" id="formato_sets" class="form-control" onchange="toggleFormatoSets()">
                                    <option value="0" <?php echo empty($formatoSets) ? 'selected' : ''; ?>>Tiempo regular</option>
                                    <option value="1" <?php echo !empty($formatoSets) ? 'selected' : ''; ?>>Por Sets</option>
                                </select>
                                <small class="text-muted">Define la modalidad de registro del marcador y actas de cada encuentro.</small>
                            </div>

                            <!-- Sets requeridos para ganar -->
                            <div class="form-group" id="container_sets" style="<?php echo empty($formatoSets) ? 'display: none;' : ''; ?>">
                                <label for="sets_para_ganar" class="font-weight-bold">Sets requeridos para ganar el partido <span class="text-danger">*</span></label>
                                <input type="number" name="sets_para_ganar" id="sets_para_ganar" class="form-control" min="1" max="10" value="<?php echo e($setsParaGanar > 0 ? $setsParaGanar : 2); ?>">
                                <small class="text-muted">Por ejemplo: 2 sets para ganar (al mejor de 3 sets), o 3 sets (al mejor de 5 sets).</small>
                            </div>

                            <!-- Empates reglamentarios -->
                            <div class="form-group">
                                <label for="permite_empate" class="font-weight-bold">¿Admite empates reglamentarios? <span class="text-danger">*</span></label>
                                <select name="permite_empate" id="permite_empate" class="form-control" onchange="togglePermiteEmpate()">
                                    <option value="1" <?php echo !empty($permiteEmpate) ? 'selected' : ''; ?>>Sí - Permite empates reglamentarios</option>
                                    <option value="0" <?php echo empty($permiteEmpate) ? 'selected' : ''; ?>>No - No admite empates</option>
                                </select>
                                <small class="text-muted">En deportes sin empate, los partidos deben definir obligatoriamente un ganador.</small>
                            </div>

                            <!-- Puntuación en Tabla -->
                            <div class="form-group mb-2">
                                <label class="font-weight-bold d-block mb-1">Puntos en Tabla de Posiciones <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="pts_victoria" class="text-xs text-muted mb-1">Victoria</label>
                                        <input type="number" name="pts_victoria" id="pts_victoria" class="form-control" value="<?php echo e($ptsVictoria); ?>" required min="0">
                                    </div>
                                    <div class="col-4">
                                        <label for="pts_empate" class="text-xs text-muted mb-1">Empate</label>
                                        <input type="number" name="pts_empate" id="pts_empate" class="form-control" value="<?php echo e($ptsEmpate); ?>" required min="0">
                                    </div>
                                    <div class="col-4">
                                        <label for="pts_derrota" class="text-xs text-muted mb-1">Derrota</label>
                                        <input type="number" name="pts_derrota" id="pts_derrota" class="form-control" value="<?php echo e($ptsDerrota); ?>" required min="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Derrota Ajustada -->
                            <div class="form-group mb-0">
                                <label for="pts_derrota_ajustada">Puntos por Derrota Especial / Ajustada</label>
                                <input type="number" name="pts_derrota_ajustada" id="pts_derrota_ajustada" class="form-control" value="<?php echo e($ptsDerrotaAjustada); ?>" min="0">
                                <small class="text-muted">Puntos por derrota ajustada (ej. 1 punto en Vóley por perder 2-3 en sets, o 1 punto en Básquet por presentarse).</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <a href="<?php echo BASE_URL; ?>/reglas-puntuacion" class="btn btn-secondary font-weight-bold">Cancelar</a>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        Guardar Disciplina y Reglas
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function toggleFormatoSets() {
    var select = document.getElementById('formato_sets');
    var container = document.getElementById('container_sets');
    var inputSets = document.getElementById('sets_para_ganar');
    if (select.value === '1') {
        container.style.display = 'block';
        if (!inputSets.value || parseInt(inputSets.value, 10) <= 0) {
            inputSets.value = 2;
        }
    } else {
        container.style.display = 'none';
        inputSets.value = 0;
    }
}

function togglePermiteEmpate() {
    var select = document.getElementById('permite_empate');
    var inputEmpate = document.getElementById('pts_empate');
    if (select.value === '0') {
        inputEmpate.value = 0;
        inputEmpate.readOnly = true;
        inputEmpate.classList.add('bg-light');
    } else {
        inputEmpate.readOnly = false;
        inputEmpate.classList.remove('bg-light');
        if (parseInt(inputEmpate.value, 10) === 0) {
            inputEmpate.value = 1;
        }
    }
}

function aplicarPlantilla(tipo) {
    var formatoSets = document.getElementById('formato_sets');
    var setsGanar = document.getElementById('sets_para_ganar');
    var permiteEmpate = document.getElementById('permite_empate');
    var ptsVic = document.getElementById('pts_victoria');
    var ptsEmp = document.getElementById('pts_empate');
    var ptsDer = document.getElementById('pts_derrota');
    var ptsDerAj = document.getElementById('pts_derrota_ajustada');

    if (tipo === 'futbol') {
        formatoSets.value = '0';
        setsGanar.value = 0;
        permiteEmpate.value = '1';
        ptsVic.value = 3;
        ptsEmp.value = 1;
        ptsDer.value = 0;
        ptsDerAj.value = 0;
    } else if (tipo === 'basquetbol') {
        formatoSets.value = '0';
        setsGanar.value = 0;
        permiteEmpate.value = '0';
        ptsVic.value = 2;
        ptsEmp.value = 0;
        ptsDer.value = 1;
        ptsDerAj.value = 0;
    } else if (tipo === 'voleibol') {
        formatoSets.value = '1';
        setsGanar.value = 3;
        permiteEmpate.value = '0';
        ptsVic.value = 3;
        ptsEmp.value = 0;
        ptsDer.value = 0;
        ptsDerAj.value = 1;
    } else if (tipo === 'recreativo') {
        formatoSets.value = '0';
        setsGanar.value = 0;
        permiteEmpate.value = '0';
        ptsVic.value = 3;
        ptsEmp.value = 0;
        ptsDer.value = 0;
        ptsDerAj.value = 0;
    }

    toggleFormatoSets();
    togglePermiteEmpate();
}

document.addEventListener('DOMContentLoaded', function() {
    toggleFormatoSets();
    togglePermiteEmpate();
});
</script>
