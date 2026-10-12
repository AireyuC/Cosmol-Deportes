<?php
/**
 * Vista de edición de una sede
 *
 * @var array $sede
 */
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-edit text-info mr-2"></i> Editar Sede
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/sedes" class="btn btn-secondary btn-sm">
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
                <h3 class="card-title font-weight-bold">Modificar Datos de la Sede</h3>
            </div>
            <form action="<?php echo BASE_URL; ?>/sedes/<?php echo $sede['id']; ?>/actualizar" method="post">
                <?php echo CsrfHelper::field(); ?>
                <div class="card-body">
                    <div class="form-group">
                        <label for="nombre">Nombre de la Sede / Complejo <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo e($sede['nombre']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="direccion">Dirección / Ubicación</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="<?php echo e($sede['direccion']); ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="latitud"><i class="fas fa-map-pin text-danger mr-1"></i> Latitud GPS</label>
                            <input type="text" name="latitud" id="latitud" class="form-control" placeholder="-17.7833" value="<?php echo isset($sede['latitud']) ? e($sede['latitud']) : ''; ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="longitud"><i class="fas fa-map-pin text-primary mr-1"></i> Longitud GPS</label>
                            <input type="text" name="longitud" id="longitud" class="form-control" placeholder="-63.1821" value="<?php echo isset($sede['longitud']) ? e($sede['longitud']) : ''; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="telefono">Teléfono / Celular de Contacto</label>
                            <input type="text" name="telefono" id="telefono" class="form-control" value="<?php echo e($sede['telefono']); ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="estado">Estado</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="Activa" <?php echo $sede['estado'] === 'Activa' ? 'selected' : ''; ?>>Activa</option>
                                <option value="Inactiva" <?php echo $sede['estado'] === 'Inactiva' ? 'selected' : ''; ?>>Inactiva</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right bg-light">
                    <a href="<?php echo BASE_URL; ?>/sedes" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-info font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Actualizar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
