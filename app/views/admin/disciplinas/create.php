<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-plus-circle text-success mr-2"></i> Registrar Disciplina y Reglas
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/disciplinas" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <form action="<?php echo BASE_URL; ?>/disciplinas/guardar" method="post">
            <?php echo CsrfHelper::field(); ?>

            <div class="row">
                <!-- Columna Izquierda: Datos del Deporte -->
                <div class="col-md-6">
                    <div class="card card-success card-outline shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-info-circle mr-1"></i> Información General
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre">Nombre de la Disciplina <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Futsal, Voleibol, Básquetbol" required value="<?php echo isset($old['nombre']) ? e($old['nombre']) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción o Modalidad</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Detalles de la disciplina..."><?php echo isset($old['descripcion']) ? e($old['descripcion']) : ''; ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="icono">Icono FontAwesome</label>
                                    <input type="text" name="icono" id="icono" class="form-control" placeholder="Ej: futbol, running, basketball-ball" value="<?php echo isset($old['icono']) ? e($old['icono']) : 'trophy'; ?>">
                                    <small class="text-muted">Nombre del icono sin prefijo (ej: futbol, volleyball-ball)</small>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="estado">Estado</label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="Activa">Activa</option>
                                        <option value="Inactiva">Inactiva</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Esquema de Puntuación -->
                <div class="col-md-6">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-calculator mr-1"></i> Esquema de Puntuación
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="pts_victoria">Victoria (pts) <span class="text-danger">*</span></label>
                                    <input type="number" name="pts_victoria" id="pts_victoria" class="form-control" value="3" required>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="pts_empate">Empate (pts) <span class="text-danger">*</span></label>
                                    <input type="number" name="pts_empate" id="pts_empate" class="form-control" value="1" required>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="pts_derrota">Derrota (pts) <span class="text-danger">*</span></label>
                                    <input type="number" name="pts_derrota" id="pts_derrota" class="form-control" value="0" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pts_derrota_ajustada">Puntos por Derrota Especial / Ajustada</label>
                                <input type="number" name="pts_derrota_ajustada" id="pts_derrota_ajustada" class="form-control" value="0">
                                <small class="text-muted">Ejemplo: 1 punto en Básquet por jugar el partido (W.O.=0), o 1 punto en Vóley por perder 2-3 en sets.</small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="permite_empate" name="permite_empate" value="1" checked>
                                    <label class="custom-control-label font-weight-normal" for="permite_empate">
                                        ¿Admite empates reglamentarios? (Desmarcar para Básquetbol o Voleibol)
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="formato_sets" name="formato_sets" value="1">
                                    <label class="custom-control-label font-weight-normal" for="formato_sets">
                                        ¿Se disputa en formato de Sets? (Ej. Voleibol)
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sets_para_ganar">Sets requeridos para ganar (si aplica)</label>
                                <input type="number" name="sets_para_ganar" id="sets_para_ganar" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body text-right">
                    <a href="<?php echo BASE_URL; ?>/disciplinas" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-success font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Guardar Disciplina y Puntuación
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
