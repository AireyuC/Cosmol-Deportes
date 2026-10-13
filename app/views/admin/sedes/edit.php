<?php
/**
 * Vista de edición de una sede y sus canchas asociadas
 *
 * @var array $sede
 * @var array $canchas
 */
$canchas = isset($canchas) ? $canchas : [];
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Editar Sede y Canchas
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/sedes" class="btn btn-secondary btn-sm font-weight-bold">
                    Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Columna Izquierda: Edición de Datos de la Sede -->
            <div class="col-lg-6 mb-3">
                <div class="card card-outline shadow-sm h-100 mb-0">
                    <div class="card-header bg-light">
                        <h3 class="card-title font-weight-bold text-dark">Información del Complejo</h3>
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
                                <input type="text" name="direccion" id="direccion" class="form-control" value="<?php echo e($sede['direccion'] ?? ''); ?>">
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="telefono">Teléfono / Contacto</label>
                                    <input type="text" name="telefono" id="telefono" class="form-control" value="<?php echo e($sede['telefono'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="estado">Estado</label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="Activa" <?php echo ($sede['estado'] ?? '') === 'Activa' ? 'selected' : ''; ?>>Activa</option>
                                        <option value="Inactiva" <?php echo ($sede['estado'] ?? '') === 'Inactiva' ? 'selected' : ''; ?>>Inactiva</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="latitud">Latitud GPS</label>
                                    <input type="text" name="latitud" id="latitud" class="form-control" placeholder="-17.7833" value="<?php echo isset($sede['latitud']) ? e($sede['latitud']) : ''; ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="longitud">Longitud GPS</label>
                                    <input type="text" name="longitud" id="longitud" class="form-control" placeholder="-63.1821" value="<?php echo isset($sede['longitud']) ? e($sede['longitud']) : ''; ?>">
                                </div>
                            </div>
                            <small class="text-muted d-block">Las coordenadas GPS permiten generar la ruta de cómo llegar desde el GPS del usuario.</small>
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                            <a href="<?php echo BASE_URL; ?>/sedes" class="btn btn-secondary font-weight-bold">Cancelar</a>
                            <button type="submit" class="btn btn-primary font-weight-bold px-4">
                                Actualizar Sede
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Columna Derecha: Canchas Registradas y Agregar Cancha -->
            <div class="col-lg-6 mb-3">
                <div class="card card-outline shadow-sm mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold text-dark mb-0">Canchas del Complejo</h3>
                        <span class="badge badge-info px-2 py-1"><?php echo count($canchas); ?> canchas</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($canchas)): ?>
                            <div class="p-4 text-center text-muted text-sm">
                                No hay canchas registradas en esta sede deportiva.
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($canchas as $c): ?>
                                    <div class="list-group-item p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="font-weight-bold text-dark mb-1">
                                                    <?php echo e($c['nombre_cancha']); ?>
                                                </h6>
                                                <div class="text-xs text-muted">
                                                    <strong>Superficie:</strong> <?php echo !empty($c['tipo_superficie']) ? e($c['tipo_superficie']) : 'No especificada'; ?>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge badge-<?php echo $c['estado'] === 'Disponible' ? 'success' : ($c['estado'] === 'Mantenimiento' ? 'warning' : 'secondary'); ?> px-2 py-1 mb-2 d-inline-block">
                                                    <?php echo e($c['estado']); ?>
                                                </span>
                                                <div>
                                                    <button type="button" class="btn btn-xs btn-outline-info font-weight-bold px-2 py-1 mr-1" data-toggle="collapse" data-target="#editCancha<?php echo e($c['id']); ?>" aria-expanded="false">
                                                        Editar
                                                    </button>
                                                    <form action="<?php echo BASE_URL; ?>/canchas/<?php echo e($c['id']); ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta cancha?');">
                                                        <?php echo CsrfHelper::field(); ?>
                                                        <input type="hidden" name="redirect_to" value="/sedes/<?php echo e($sede['id']); ?>/editar">
                                                        <button type="submit" class="btn btn-xs btn-danger font-weight-bold px-2 py-1">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Formulario colapsable para editar la cancha -->
                                        <div class="collapse mt-3 pt-3 border-top" id="editCancha<?php echo e($c['id']); ?>">
                                            <form action="<?php echo BASE_URL; ?>/canchas/<?php echo e($c['id']); ?>/actualizar" method="post">
                                                <?php echo CsrfHelper::field(); ?>
                                                <input type="hidden" name="redirect_to" value="/sedes/<?php echo e($sede['id']); ?>/editar">
                                                
                                                <div class="form-group mb-2">
                                                    <label class="text-xs font-weight-bold mb-1">Nombre de la Cancha <span class="text-danger">*</span></label>
                                                    <input type="text" name="nombre_cancha" class="form-control form-control-sm" value="<?php echo e($c['nombre_cancha']); ?>" required>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-7 form-group mb-2">
                                                        <label class="text-xs font-weight-bold mb-1">Tipo de Superficie</label>
                                                        <input type="text" name="tipo_superficie" class="form-control form-control-sm" value="<?php echo e($c['tipo_superficie'] ?? ''); ?>" placeholder="Ej. Césped Sintético">
                                                    </div>
                                                    <div class="col-sm-5 form-group mb-2">
                                                        <label class="text-xs font-weight-bold mb-1">Disponibilidad</label>
                                                        <select name="estado" class="form-control form-control-sm">
                                                            <option value="Disponible" <?php echo ($c['estado'] ?? '') === 'Disponible' ? 'selected' : ''; ?>>Disponible</option>
                                                            <option value="Mantenimiento" <?php echo ($c['estado'] ?? '') === 'Mantenimiento' ? 'selected' : ''; ?>>Mantenimiento</option>
                                                            <option value="Inactiva" <?php echo ($c['estado'] ?? '') === 'Inactiva' ? 'selected' : ''; ?>>Inactiva</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="text-right mt-2">
                                                    <button type="button" class="btn btn-xs btn-secondary" data-toggle="collapse" data-target="#editCancha<?php echo e($c['id']); ?>">Cancelar</button>
                                                    <button type="submit" class="btn btn-xs btn-primary font-weight-bold">Guardar Cambios de Cancha</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Card para Agregar Cancha Rápida a la Sede -->
                <div class="card card-outline shadow-sm mb-0">
                    <div class="card-header bg-light">
                        <h4 class="card-title font-weight-bold text-dark text-sm mb-0">Agregar Nueva Cancha a esta Sede</h4>
                    </div>
                    <form action="<?php echo BASE_URL; ?>/sedes/<?php echo e($sede['id']); ?>/canchas/guardar" method="post">
                        <?php echo CsrfHelper::field(); ?>
                        <input type="hidden" name="redirect_to" value="/sedes/<?php echo e($sede['id']); ?>/editar">
                        <div class="card-body">
                            <div class="form-group mb-2">
                                <label for="nuevo_nombre_cancha" class="text-xs font-weight-bold mb-1">Nombre o Identificador de Cancha <span class="text-danger">*</span></label>
                                <input type="text" name="nombre_cancha" id="nuevo_nombre_cancha" class="form-control form-control-sm" required placeholder="Ej. Cancha Auxiliar #2, Cancha de Básquetbol">
                            </div>

                            <div class="row">
                                <div class="col-sm-7 form-group mb-2">
                                    <label for="nuevo_tipo_superficie" class="text-xs font-weight-bold mb-1">Superficie</label>
                                    <input type="text" name="tipo_superficie" id="nuevo_tipo_superficie" class="form-control form-control-sm" placeholder="Ej. Parquet Flotante, Césped Sintético">
                                </div>
                                <div class="col-sm-5 form-group mb-2">
                                    <label for="nuevo_estado_cancha" class="text-xs font-weight-bold mb-1">Disponibilidad</label>
                                    <select name="estado" id="nuevo_estado_cancha" class="form-control form-control-sm">
                                        <option value="Disponible">Disponible</option>
                                        <option value="Mantenimiento">Mantenimiento</option>
                                        <option value="Inactiva">Inactiva</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light text-right py-2">
                            <button type="submit" class="btn btn-sm btn-primary font-weight-bold">
                                Agregar Cancha
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
