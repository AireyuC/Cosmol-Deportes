<?php
/**
 * Vista de listado general de sedes
 *
 * @var array $sedes
 */
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-map-marker-alt text-primary mr-2"></i> Sedes y Recintos Deportivos
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/sedes/crear" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Nueva Sede
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Complejos Habilitados para la Olimpiada</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Nombre del Complejo</th>
                            <th>Dirección / Ubicación</th>
                            <th>Teléfono</th>
                            <th class="text-center">Canchas Habilitadas</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center" style="width: 220px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sedes)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No hay sedes registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($sedes as $s): ?>
                                <tr>
                                    <td><strong>#<?php echo e($s['id']); ?></strong></td>
                                    <td class="font-weight-bold text-dark">
                                        <i class="fas fa-landmark text-secondary mr-1"></i>
                                        <?php echo e($s['nombre']); ?>
                                    </td>
                                    <td class="text-muted text-sm"><?php echo e($s['direccion']); ?></td>
                                    <td class="text-muted text-sm"><?php echo e($s['telefono']); ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-info px-2 py-1">
                                            <i class="fas fa-layer-group mr-1"></i> <?php echo e($s['total_canchas']); ?> Canchas
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo $s['estado'] === 'Activa' ? 'success' : 'secondary'; ?>">
                                            <?php echo e($s['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo BASE_URL; ?>/sedes/<?php echo $s['id']; ?>/canchas" class="btn btn-sm btn-outline-primary" title="Administrar Canchas">
                                            <i class="fas fa-futbol mr-1"></i> Canchas
                                        </a>
                                        <?php if (AuthHelper::isAdmin()): ?>
                                            <a href="<?php echo BASE_URL; ?>/sedes/<?php echo $s['id']; ?>/editar" class="btn btn-sm btn-info" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/sedes/<?php echo $s['id']; ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta sede y todas sus canchas asociadas?');">
                                                <?php echo CsrfHelper::field(); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
