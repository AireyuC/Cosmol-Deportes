<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Nueva Olimpiada
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/olimpiadas" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline shadow-sm">
            <form action="<?php echo BASE_URL; ?>/olimpiadas/guardar" method="post">
                <?php echo CsrfHelper::field(); ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="nombre">Nombre de la Olimpiada <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="anio">Año <span class="text-danger">*</span></label>
                            <input type="number" name="anio" id="anio" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lema">Lema o Frase del Evento</label>
                        <input type="text" name="lema" id="lema" class="form-control" value="<?php echo isset($old['lema']) ? e($old['lema']) : ''; ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo isset($old['fecha_inicio']) ? e($old['fecha_inicio']) : ''; ?>">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="fecha_fin">Fecha de Culminación</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo isset($old['fecha_fin']) ? e($old['fecha_fin']) : ''; ?>">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="estado">Estado Inicial</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="Planificada">Planificada</option>
                                <option value="En Curso">En Curso</option>
                                <option value="Finalizada">Finalizada</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right bg-light">
                    <a href="<?php echo BASE_URL; ?>/olimpiadas" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
