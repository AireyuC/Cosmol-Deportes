<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-plus-circle text-info mr-2"></i> Registrar Nueva Delegación
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
                <h3 class="card-title font-weight-bold">Datos de la Delegación Institucional</h3>
            </div>
            <form action="<?php echo BASE_URL; ?>/delegaciones/guardar" method="post">
                <?php echo CsrfHelper::field(); ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="nombre">Nombre de la Delegación / Área <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Gerencia Comercial, Planta Operaciones" required value="<?php echo isset($old['nombre']) ? e($old['nombre']) : ''; ?>">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="alias">Alias o Nombre Corto</label>
                            <input type="text" name="alias" id="alias" class="form-control" placeholder="Ej: Comercial, Operaciones" value="<?php echo isset($old['alias']) ? e($old['alias']) : ''; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="color_representativo">Color Representativo</label>
                            <input type="color" name="color_representativo" id="color_representativo" class="form-control" value="<?php echo isset($old['color_representativo']) ? e($old['color_representativo']) : '#007bff'; ?>" style="height: 40px;">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="responsable">Delegado / Responsable</label>
                            <input type="text" name="responsable" id="responsable" class="form-control" placeholder="Ej: Ing. Carlos Mendoza" value="<?php echo isset($old['responsable']) ? e($old['responsable']) : ''; ?>">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="telefono">Teléfono / Celular de Contacto</label>
                            <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Ej: 70011223" value="<?php echo isset($old['telefono']) ? e($old['telefono']) : ''; ?>">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right bg-light">
                    <a href="<?php echo BASE_URL; ?>/delegaciones" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-info font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Guardar Delegación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
