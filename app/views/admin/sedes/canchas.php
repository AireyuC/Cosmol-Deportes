<?php
/**
 * Vista de administración de canchas de una sede
 *
 * @var array $sede
 * @var array $canchas
 */
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    Cancha en: <?php echo e($sede['nombre'] ?? ''); ?>
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/sedes" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Columna Izquierda: Listado de Canchas de la Sede -->
            <div class="col-lg-7">
                <div class="card card-outline card-cosmol shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Campos de Juego Registrados</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nombre de la Cancha</th>
                                    <th>Tipo de Superficie</th>
                                    <th class="text-center">Estado</th>
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <th class="text-center" style="width: 100px;">Acción</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($canchas)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No se han registrado canchas para esta sede.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($canchas as $c): ?>
                                        <tr>
                                            <td><strong><?php echo e($c['id']); ?></strong></td>
                                            <td class="font-weight-bold"><?php echo e($c['nombre_cancha']); ?></td>
                                            <td><span class="badge badge-light border"><?php echo e($c['tipo_superficie']); ?></span></td>
                                            <td class="text-center">
                                                <span class="badge badge-<?php echo $c['estado'] === 'Disponible' ? 'success' : 'warning'; ?>">
                                                    <?php echo e($c['estado']); ?>
                                                </span>
                                            </td>
                                            <?php if (AuthHelper::isAdmin()): ?>
                                                <td class="text-center">
                                                    <form action="<?php echo BASE_URL; ?>/canchas/<?php echo $c['id']; ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta cancha?');">
                                                        <?php echo CsrfHelper::field(); ?>
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Cancha">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Formulario para Agregar Cancha -->
            <?php if (AuthHelper::isAdmin()): ?>
                <div class="col-lg-5">
                    <div class="card card-outline card-cosmol shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">
                                Agregar Nueva Cancha
                            </h3>
                        </div>
                        <form action="<?php echo BASE_URL; ?>/sedes/<?php echo e($sede['id'] ?? ''); ?>/canchas/guardar" method="post">
                            <?php echo CsrfHelper::field(); ?>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nombre_cancha">Nombre o Identificador de la Cancha <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre_cancha" id="nombre_cancha" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="tipo_superficie">Tipo de Superficie</label>
                                    <input type="text" name="tipo_superficie" id="tipo_superficie" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="estado">Disponibilidad</label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="Disponible">Disponible</option>
                                        <option value="Mantenimiento">En Mantenimiento</option>
                                        <option value="Inactiva">Inactiva</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer text-right bg-light">
                                <button type="submit" class="btn btn-primary font-weight-bold">
                                    Agregar Cancha
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
