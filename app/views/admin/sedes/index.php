<?php
/**
 * Vista de listado general de sedes
 *
 * @var array $sedes
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="color: #0066B2;">
                    Sedes y Recintos Deportivos
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/sedes/crear" class="btn btn-primary font-weight-bold">
                        Nueva Sede
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-cosmol shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title mb-0">Complejos Deportivos Registrados</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-cosmol">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Nombre del Complejo</th>
                            <th>Dirección / Ubicación</th>
                            <th class="text-center">Canchas</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center" style="width: 200px;">Acciones</th>
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
                                    <td class="text-secondary font-weight-bold">#<?php echo e($s['id']); ?></td>
                                    <td class="font-weight-bold text-dark">
                                        <?php echo e($s['nombre']); ?>
                                    </td>
                                    <td class="text-muted text-sm">
                                        <?php echo e($s['direccion']); ?>
                                        <?php if (!empty($s['latitud']) && !empty($s['longitud'])): ?>
                                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $s['latitud']; ?>,<?php echo $s['longitud']; ?>" target="_blank" class="badge py-1 px-2 ml-1" style="background-color: #EAF4FB; color: #0066B2; border: 1px solid #BFE2F7;" title="Ver ruta desde mi ubicación">
                                                Cómo llegar &rsaquo;
                                            </a>
                                        <?php elseif (!empty($s['direccion'])): ?>
                                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode($s['direccion'] . ' ' . $s['nombre']); ?>" target="_blank" class="badge py-1 px-2 ml-1" style="background-color: #EAF4FB; color: #0066B2; border: 1px solid #BFE2F7;" title="Ver ruta en mapa">
                                                Cómo llegar &rsaquo;
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge py-1 px-2" style="background-color: #00A3E0; color: #FFFFFF;">
                                            <?php echo e($s['total_canchas']); ?> Canchas
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo $s['estado'] === 'Activa' ? 'success' : 'secondary'; ?> px-2 py-1">
                                            <?php echo e($s['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo BASE_URL; ?>/sedes/<?php echo $s['id']; ?>/canchas" class="btn btn-xs btn-outline-primary font-weight-bold px-2 py-1" title="Administrar Canchas">
                                            Cancha
                                        </a>
                                        <?php if (AuthHelper::isAdmin()): ?>
                                            <a href="<?php echo BASE_URL; ?>/sedes/<?php echo $s['id']; ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Editar">
                                                Editar
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/sedes/<?php echo $s['id']; ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta sede y todas sus canchas asociadas?');">
                                                <?php echo CsrfHelper::field(); ?>
                                                <button type="submit" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" title="Eliminar">
                                                    Eliminar
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
