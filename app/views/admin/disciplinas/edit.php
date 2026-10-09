<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-edit text-info mr-2"></i> Editar Disciplina y Reglas
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
        <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo $disciplina['id']; ?>/actualizar" method="post">
            <?php echo CsrfHelper::field(); ?>

            <div class="row">
                <!-- Columna Izquierda: Datos del Deporte -->
                <div class="col-md-6">
                    <div class="card card-info card-outline shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-info-circle mr-1"></i> Información General
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre">Nombre de la Disciplina <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo e($disciplina['nombre']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción o Modalidad</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="3"><?php echo e($disciplina['descripcion']); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="icono">Icono FontAwesome</label>
                                    <input type="text" name="icono" id="icono" class="form-control" value="<?php echo e($disciplina['icono']); ?>">
                                    <small class="text-muted">Nombre del icono (ej: futbol, running, volleyball-ball)</small>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="estado">Estado</label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="Activa" <?php echo $disciplina['estado'] === 'Activa' ? 'selected' : ''; ?>>Activa</option>
                                        <option value="Inactiva" <?php echo $disciplina['estado'] === 'Inactiva' ? 'selected' : ''; ?>>Inactiva</option>
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
                                    <input type="number" name="pts_victoria" id="pts_victoria" class="form-control" value="<?php echo e($disciplina['pts_victoria']); ?>" required>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="pts_empate">Empate (pts) <span class="text-danger">*</span></label>
                                    <input type="number" name="pts_empate" id="pts_empate" class="form-control" value="<?php echo e($disciplina['pts_empate']); ?>" required>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="pts_derrota">Derrota (pts) <span class="text-danger">*</span></label>
                                    <input type="number" name="pts_derrota" id="pts_derrota" class="form-control" value="<?php echo e($disciplina['pts_derrota']); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pts_derrota_ajustada">Puntos por Derrota Especial / Ajustada</label>
                                <input type="number" name="pts_derrota_ajustada" id="pts_derrota_ajustada" class="form-control" value="<?php echo e($disciplina['pts_derrota_ajustada']); ?>">
                                <small class="text-muted">Ej: 1 punto en Básquetbol por presentación o 1 punto en Vóleibol por derrota 2-3 en sets.</small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="permite_empate" name="permite_empate" value="1" <?php echo !empty($disciplina['permite_empate']) ? 'checked' : ''; ?>>
                                    <label class="custom-control-label font-weight-normal" for="permite_empate">
                                        ¿Admite empates reglamentarios?
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="formato_sets" name="formato_sets" value="1" <?php echo !empty($disciplina['formato_sets']) ? 'checked' : ''; ?>>
                                    <label class="custom-control-label font-weight-normal" for="formato_sets">
                                        ¿Se disputa en formato de Sets?
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sets_para_ganar">Sets requeridos para ganar (si aplica)</label>
                                <input type="number" name="sets_para_ganar" id="sets_para_ganar" class="form-control" value="<?php echo e($disciplina['sets_para_ganar']); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body text-right">
                    <a href="<?php echo BASE_URL; ?>/disciplinas" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-info font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Actualizar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
