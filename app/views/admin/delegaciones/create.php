<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Registrar Nueva Delegación
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/delegaciones" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline shadow-sm card-cosmol">
            <form action="<?php echo BASE_URL; ?>/delegaciones/guardar" method="post">
                <?php echo CsrfHelper::field(); ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="nombre">Nombre de la Delegación / Área <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo isset($old['nombre']) ? e($old['nombre']) : ''; ?>">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="alias">Alias o Nombre Corto</label>
                            <input type="text" name="alias" id="alias" class="form-control" value="<?php echo isset($old['alias']) ? e($old['alias']) : ''; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="responsable">Delegado / Responsable</label>
                            <input type="text" name="responsable" id="responsable" class="form-control" value="<?php echo isset($old['responsable']) ? e($old['responsable']) : ''; ?>">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="telefono">Teléfono / Celular de Contacto</label>
                            <input type="text" name="telefono" id="telefono" class="form-control" value="<?php echo isset($old['telefono']) ? e($old['telefono']) : ''; ?>">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right bg-light">
                    <a href="<?php echo BASE_URL; ?>/delegaciones" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
