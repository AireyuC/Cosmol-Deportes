<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-edit text-warning mr-2"></i> Editar Torneo: <?php echo e($torneo['nombre']); ?>
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/torneos/<?php echo e($torneo['id']); ?>" class="btn btn-info mr-1">
                    <i class="fas fa-eye mr-1"></i> Ver Tablero
                </a>
                <a href="<?php echo BASE_URL; ?>/torneos" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al Listado
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline card-warning shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">Modificar Parámetros del Torneo</h3>
            </div>
            <form action="<?php echo BASE_URL; ?>/torneos/<?php echo e($torneo['id']); ?>/actualizar" method="post">
                <?php echo CsrfHelper::field(); ?>

                <div class="card-body">
                    <div class="row">
                        <!-- Nombre del Torneo -->
                        <div class="col-md-8 form-group">
                            <label for="nombre">Nombre del Torneo <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo e($torneo['nombre']); ?>" required>
                        </div>

                        <!-- Estado del Torneo -->
                        <div class="col-md-4 form-group">
                            <label for="estado">Estado de la Competencia <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-control font-weight-bold" required>
                                <option value="Borrador" <?php echo ($torneo['estado'] === 'Borrador') ? 'selected' : ''; ?>>Borrador (Configuración)</option>
                                <option value="Inscripciones" <?php echo ($torneo['estado'] === 'Inscripciones') ? 'selected' : ''; ?>>Inscripciones Abiertas</option>
                                <option value="Fase_Grupos" <?php echo ($torneo['estado'] === 'Fase_Grupos') ? 'selected' : ''; ?>>Fase de Grupos en Disputa</option>
                                <option value="Playoffs" <?php echo ($torneo['estado'] === 'Playoffs') ? 'selected' : ''; ?>>Fase de Playoffs / Eliminatorias</option>
                                <option value="Finalizado" <?php echo ($torneo['estado'] === 'Finalizado') ? 'selected' : ''; ?>>Finalizado (Medallas Asignadas)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Macro-Evento Olimpiada -->
                        <div class="col-md-4 form-group">
                            <label for="olimpiada_id">Olimpiada Asociada <span class="text-danger">*</span></label>
                            <select name="olimpiada_id" id="olimpiada_id" class="form-control" required>
                                <?php foreach ($olimpiadas as $olimp): ?>
                                    <option value="<?php echo e($olimp['id']); ?>" <?php echo ($torneo['olimpiada_id'] == $olimp['id']) ? 'selected' : ''; ?>>
                                        <?php echo e($olimp['nombre']); ?> (<?php echo e($olimp['anio']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Disciplina Deportiva -->
                        <div class="col-md-4 form-group">
                            <label for="disciplina_id">Disciplina Deportiva <span class="text-danger">*</span></label>
                            <select name="disciplina_id" id="disciplina_id" class="form-control" required>
                                <?php foreach ($disciplinas as $d): ?>
                                    <option value="<?php echo e($d['id']); ?>" <?php echo ($torneo['disciplina_id'] == $d['id']) ? 'selected' : ''; ?>>
                                        <?php echo e($d['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Rama / Categoría -->
                        <div class="col-md-4 form-group">
                            <label for="rama">Rama / Categoría <span class="text-danger">*</span></label>
                            <select name="rama" id="rama" class="form-control" required>
                                <option value="Masculino" <?php echo ($torneo['rama'] === 'Masculino') ? 'selected' : ''; ?>>Masculino (Varones)</option>
                                <option value="Femenino" <?php echo ($torneo['rama'] === 'Femenino') ? 'selected' : ''; ?>>Femenino (Damas)</option>
                                <option value="Mixto" <?php echo ($torneo['rama'] === 'Mixto') ? 'selected' : ''; ?>>Mixto</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Formato -->
                        <div class="col-md-4 form-group">
                            <label for="tipo_torneo">Formato de Competencia <span class="text-danger">*</span></label>
                            <select name="tipo_torneo" id="tipo_torneo" class="form-control" required>
                                <option value="Grupos_Playoff" <?php echo ($torneo['tipo_torneo'] === 'Grupos_Playoff') ? 'selected' : ''; ?>>Fase de Grupos + Playoffs</option>
                                <option value="Liga" <?php echo ($torneo['tipo_torneo'] === 'Liga') ? 'selected' : ''; ?>>Liga (Todos contra todos)</option>
                                <option value="Eliminatoria" <?php echo ($torneo['tipo_torneo'] === 'Eliminatoria') ? 'selected' : ''; ?>>Eliminación Directa</option>
                            </select>
                        </div>

                        <!-- Modalidad encuentros -->
                        <div class="col-md-4 form-group">
                            <label for="modalidad">Encuentros <span class="text-danger">*</span></label>
                            <select name="modalidad" id="modalidad" class="form-control" required>
                                <option value="Solo_Ida" <?php echo ($torneo['modalidad'] === 'Solo_Ida') ? 'selected' : ''; ?>>Solo Ida</option>
                                <option value="Ida_Vuelta" <?php echo ($torneo['modalidad'] === 'Ida_Vuelta') ? 'selected' : ''; ?>>Ida y Vuelta</option>
                            </select>
                        </div>

                        <!-- Fechas -->
                        <div class="col-md-2 form-group">
                            <label for="fecha_inicio">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo e($torneo['fecha_inicio']); ?>">
                        </div>

                        <div class="col-md-2 form-group">
                            <label for="fecha_fin">Fecha Fin</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo e($torneo['fecha_fin']); ?>">
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-right">
                    <a href="<?php echo BASE_URL; ?>/torneos/<?php echo e($torneo['id']); ?>" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-warning font-weight-bold text-dark">
                        <i class="fas fa-sync mr-1"></i> Actualizar Torneo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
