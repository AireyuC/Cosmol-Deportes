<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Registrar Nueva Disciplina
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/disciplinas" class="btn btn-secondary font-weight-bold">
                    Volver a Disciplinas
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline card-warning shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">Datos de la Disciplina</h3>
            </div>
            <form action="<?php echo BASE_URL; ?>/disciplinas/guardar" method="post">
                <?php echo CsrfHelper::field(); ?>

                <div class="card-body">
                    <div class="row">
                        <!-- Nombre de la Disciplina -->
                        <div class="col-md-8 form-group">
                            <label for="nombre">Nombre de la Disciplina <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej. Fútbol Varones - Olimpiadas 2026" required autofocus>
                        </div>

                        <!-- Macro-Evento Olimpiada -->
                        <div class="col-md-4 form-group">
                            <label for="olimpiada_id">Olimpiada Asociada <span class="text-danger">*</span></label>
                            <select name="olimpiada_id" id="olimpiada_id" class="form-control" required>
                                <?php foreach ($olimpiadas as $olimp): ?>
                                    <option value="<?php echo e($olimp['id']); ?>" <?php echo ($olimpiadaActiva && $olimpiadaActiva['id'] == $olimp['id']) ? 'selected' : ''; ?>>
                                        <?php echo e($olimp['nombre']); ?> (<?php echo e($olimp['anio']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Rama / Categoría -->
                        <div class="col-md-4 form-group">
                            <label for="rama">Rama / Categoría <span class="text-danger">*</span></label>
                            <select name="rama" id="rama" class="form-control" required>
                                <option value="Masculino">Masculino (Varones)</option>
                                <option value="Femenino">Femenino (Damas)</option>
                                <option value="Mixto">Mixto</option>
                            </select>
                        </div>

                        <!-- Formato de Competencia -->
                        <div class="col-md-4 form-group">
                            <label for="tipo_torneo">Formato de Competencia <span class="text-danger">*</span></label>
                            <select name="tipo_torneo" id="tipo_torneo" class="form-control" required>
                                <option value="Grupos_Playoff">Fase de Grupos</option>
                                <option value="Liga">Clasificación General</option>
                                <option value="Eliminatoria">Eliminación Directa</option>
                            </select>
                        </div>

                        <!-- Modalidad de partidos -->
                        <div class="col-md-4 form-group">
                            <label for="modalidad">Encuentros <span class="text-danger">*</span></label>
                            <select name="modalidad" id="modalidad" class="form-control" required>
                                <option value="Solo_Ida">Solo Ida (1 encuentro)</option>
                                <option value="Ida_Vuelta">Ida y Vuelta (2 encuentros)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Fechas -->
                        <div class="col-md-6 form-group">
                            <label for="fecha_inicio">Fecha de Inicio Estimada</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="fecha_fin">Fecha de Culminación Estimada</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                        </div>
                    </div>

                    <div class="alert alert-info py-2 px-3 mt-2 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Nota:</strong> Si seleccionas el formato <em>"Fase de Grupos"</em>, podrás crear los grupos correspondientes (ej. <strong>Grupo A</strong> y <strong>Grupo B</strong>) desde la ficha de la disciplina para distribuir los equipos.
                    </div>
                </div>

                <div class="card-footer bg-light text-right">
                    <a href="<?php echo BASE_URL; ?>/disciplinas" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-warning font-weight-bold text-dark">
                        <i class="fas fa-save mr-1"></i> Guardar Disciplina
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
