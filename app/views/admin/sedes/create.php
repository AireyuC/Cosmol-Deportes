<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-plus-circle text-primary mr-2"></i> Registrar Nueva Sede
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
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Datos del Complejo Deportivo</h3>
            </div>
            <form action="<?php echo BASE_URL; ?>/sedes/guardar" method="post">
                <?php echo CsrfHelper::field(); ?>
                <div class="card-body">
                    <div class="form-group">
                        <label for="nombre">Nombre de la Sede / Complejo <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Complejo Deportivo Central Cosmol" required value="<?php echo isset($old['nombre']) ? e($old['nombre']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="direccion">Dirección / Ubicación</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Ej: Av. Principal #450, Barrio Las Palmas" value="<?php echo isset($old['direccion']) ? e($old['direccion']) : ''; ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="latitud"><i class="fas fa-map-pin text-danger mr-1"></i> Latitud GPS (opcional para ruta)</label>
                            <input type="text" name="latitud" id="latitud" class="form-control" placeholder="Ej: -17.783300" value="<?php echo isset($old['latitud']) ? e($old['latitud']) : ''; ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="longitud"><i class="fas fa-map-pin text-primary mr-1"></i> Longitud GPS (opcional para ruta)</label>
                            <input type="text" name="longitud" id="longitud" class="form-control" placeholder="Ej: -63.182100" value="<?php echo isset($old['longitud']) ? e($old['longitud']) : ''; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="telefono">Teléfono / Celular de Contacto</label>
                            <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Ej: 33445566 o 70011223" value="<?php echo isset($old['telefono']) ? e($old['telefono']) : ''; ?>">
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
                <div class="card-footer text-right bg-light">
                    <a href="<?php echo BASE_URL; ?>/sedes" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Guardar Sede y Continuar a Canchas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
