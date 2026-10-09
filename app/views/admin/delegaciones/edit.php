<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-edit text-info mr-2"></i> Editar Delegación
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/delegaciones" class="btn btn-secondary btn-sm">
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
                <h3 class="card-title font-weight-bold">Modificar Datos de la Delegación</h3>
            </div>
            <form action="<?php echo BASE_URL; ?>/delegaciones/<?php echo $delegacion['id']; ?>/actualizar" method="post">
                <?php echo CsrfHelper::field(); ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="nombre">Nombre de la Delegación / Área <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo e($delegacion['nombre']); ?>" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="alias">Alias o Nombre Corto</label>
                            <input type="text" name="alias" id="alias" class="form-control" value="<?php echo e($delegacion['alias']); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="color_representativo">Color Representativo</label>
                            <input type="color" name="color_representativo" id="color_representativo" class="form-control" value="<?php echo e($delegacion['color_representativo']); ?>" style="height: 40px;">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="responsable">Delegado / Responsable</label>
                            <input type="text" name="responsable" id="responsable" class="form-control" value="<?php echo e($delegacion['responsable']); ?>">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="telefono">Teléfono / Celular de Contacto</label>
                            <input type="text" name="telefono" id="telefono" class="form-control" value="<?php echo e($delegacion['telefono']); ?>">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right bg-light">
                    <a href="<?php echo BASE_URL; ?>/delegaciones" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-info font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Actualizar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
