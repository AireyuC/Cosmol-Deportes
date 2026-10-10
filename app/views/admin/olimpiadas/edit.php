<?php
/**
 * Vista de Edición de Olimpiada
 * Variables inyectadas desde OlimpiadaController::edit() vía extract():
 *
 * @var array  $olimpiada  Datos de la olimpiada a editar
 * @var string $pageTitle  Título de la página
 */
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-edit text-info mr-2"></i> Editar Olimpiada
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/olimpiadas" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-info card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Modificar Datos de la Olimpiada</h3>
            </div>
            <form action="<?php echo BASE_URL; ?>/olimpiadas/<?php echo $olimpiada['id']; ?>/actualizar" method="post">
                <?php echo CsrfHelper::field(); ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="nombre">Nombre de la Olimpiada <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo e($olimpiada['nombre']); ?>" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="anio">Año <span class="text-danger">*</span></label>
                            <input type="number" name="anio" id="anio" class="form-control" value="<?php echo e($olimpiada['anio']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lema">Lema o Frase del Evento</label>
                        <input type="text" name="lema" id="lema" class="form-control" value="<?php echo e($olimpiada['lema']); ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo e($olimpiada['fecha_inicio']); ?>">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="fecha_fin">Fecha de Culminación</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo e($olimpiada['fecha_fin']); ?>">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="estado">Estado Actual</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="Planificada" <?php echo $olimpiada['estado'] === 'Planificada' ? 'selected' : ''; ?>>Planificada</option>
                                <option value="En Curso" <?php echo $olimpiada['estado'] === 'En Curso' ? 'selected' : ''; ?>>En Curso</option>
                                <option value="Finalizada" <?php echo $olimpiada['estado'] === 'Finalizada' ? 'selected' : ''; ?>>Finalizada</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right bg-light">
                    <a href="<?php echo BASE_URL; ?>/olimpiadas" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-info font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Actualizar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
