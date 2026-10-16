<?php
/**
 * Vista de Listado de Sedes y Canchas Deportivas
 *
 * @var array $sedes
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Sedes y Canchas Deportivas
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
            
            <!-- Vista Tabla (Desktop y Tablets: >= 768px) -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="thead-cosmol">
                        <tr>
                            <th>Complejo Deportivo</th>
                            <th>Dirección / Ubicación</th>
                            <th class="text-center" style="width: 140px;">Cómo llegar</th>
                            <th class="text-center" style="width: 120px;">Canchas</th>
                            <th class="text-center" style="width: 100px;">Estado</th>
                            <th class="text-center text-nowrap" style="width: <?php echo AuthHelper::isAdmin() ? '240px' : '100px'; ?>;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sedes)): ?>
                            <tr>
                                <td colspan="<?php echo AuthHelper::isAdmin() ? '6' : '5'; ?>" class="text-center text-muted py-4">
                                    No hay complejos deportivos registrados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($sedes as $s): ?>
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">
                                            <?php echo e($s['nombre']); ?>
                                        </div>
                                        <?php if (!empty($s['telefono'])): ?>
                                            <div class="text-muted text-xs">
                                                Tel: <?php echo e($s['telefono']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted text-sm">
                                        <?php echo !empty($s['direccion']) ? e($s['direccion']) : '<span class="text-muted font-italic">Sin dirección registrada</span>'; ?>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-outline-info font-weight-bold px-2 py-1" onclick="comoLlegar(<?php echo !empty($s['latitud']) ? (float)$s['latitud'] : 'null'; ?>, <?php echo !empty($s['longitud']) ? (float)$s['longitud'] : 'null'; ?>, '<?php echo e(addslashes($s['direccion'] ?? '')); ?>', '<?php echo e(addslashes($s['nombre'])); ?>')" title="Trazar ruta GPS desde mi ubicación actual">
                                            Cómo llegar
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-light border font-weight-bold px-2 py-1 text-primary" data-toggle="modal" data-target="#modalCanchas<?php echo e($s['id']); ?>" title="Ver canchas del complejo">
                                            <?php echo e($s['total_canchas'] ?? count($s['canchas'] ?? [])); ?> canchas
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo ($s['estado'] ?? '') === 'Activa' ? 'success' : 'secondary'; ?> px-2 py-1">
                                            <?php echo e($s['estado'] ?? 'Activa'); ?>
                                        </span>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <div class="d-inline-flex align-items-center justify-content-center" style="gap: 8px;">
                                            <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold px-2 py-1" data-toggle="modal" data-target="#modalCanchas<?php echo e($s['id']); ?>" title="Ver canchas del complejo">
                                                Cancha
                                            </button>
                                            <?php if (AuthHelper::isAdmin()): ?>
                                                <a href="<?php echo BASE_URL; ?>/sedes/<?php echo e($s['id']); ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Editar Sede">
                                                    Editar
                                                </a>
                                                <form action="<?php echo BASE_URL; ?>/sedes/<?php echo e($s['id']); ?>/eliminar" method="post" class="d-inline m-0 p-0" onsubmit="return confirm('¿Eliminar esta sede y todas sus canchas asociadas?');">
                                                    <?php echo CsrfHelper::field(); ?>
                                                    <button type="submit" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" title="Eliminar Sede">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Vista Móvil (Smartphones: < 768px) -->
            <div class="d-block d-md-none">
                <?php if (empty($sedes)): ?>
                    <div class="text-center text-muted py-4 text-sm">
                        No hay complejos deportivos registrados.
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($sedes as $s): ?>
                            <div class="list-group-item p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                                        <?php echo e($s['nombre']); ?>
                                    </h5>
                                    <span class="badge badge-<?php echo ($s['estado'] ?? '') === 'Activa' ? 'success' : 'secondary'; ?> px-2 py-1">
                                        <?php echo e($s['estado'] ?? 'Activa'); ?>
                                    </span>
                                </div>

                                <?php if (!empty($s['direccion'])): ?>
                                    <p class="text-muted text-sm mb-1">
                                        <?php echo e($s['direccion']); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($s['telefono'])): ?>
                                    <div class="text-muted text-xs mb-2">
                                        <strong>Contacto:</strong> <?php echo e($s['telefono']); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3 text-xs text-muted">
                                    <strong>Canchas disponibles:</strong> <?php echo e($s['total_canchas'] ?? count($s['canchas'] ?? [])); ?> campos
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-wrap pt-2 border-top">
                                    <div class="mb-2 mb-sm-0">
                                        <button type="button" class="btn btn-sm btn-outline-info font-weight-bold mr-1" onclick="comoLlegar(<?php echo !empty($s['latitud']) ? (float)$s['latitud'] : 'null'; ?>, <?php echo !empty($s['longitud']) ? (float)$s['longitud'] : 'null'; ?>, '<?php echo e(addslashes($s['direccion'] ?? '')); ?>', '<?php echo e(addslashes($s['nombre'])); ?>')">
                                            Cómo llegar
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" data-toggle="modal" data-target="#modalCanchas<?php echo e($s['id']); ?>">
                                            Cancha
                                        </button>
                                    </div>

                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <div>
                                            <a href="<?php echo BASE_URL; ?>/sedes/<?php echo e($s['id']); ?>/editar" class="btn btn-sm btn-info font-weight-bold mr-1">
                                                Editar
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/sedes/<?php echo e($s['id']); ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta sede y todas sus canchas asociadas?');">
                                                <?php echo CsrfHelper::field(); ?>
                                                <button type="submit" class="btn btn-sm btn-danger font-weight-bold">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<!-- Modales de Información de la Sede y Canchas -->
<?php if (!empty($sedes)): ?>
    <?php foreach ($sedes as $s): ?>
        <div class="modal fade" id="modalCanchas<?php echo e($s['id']); ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?php echo e($s['id']); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-dark" id="modalLabel<?php echo e($s['id']); ?>">
                            <?php echo e($s['nombre']); ?>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Detalles Generales del Complejo -->
                        <div class="row pb-3 mb-3 border-bottom">
                            <div class="col-md-6 mb-2">
                                <span class="text-muted d-block text-xs text-uppercase font-weight-bold mb-1">Dirección / Ubicación</span>
                                <span class="text-dark text-sm font-weight-bold">
                                    <?php echo !empty($s['direccion']) ? e($s['direccion']) : '<span class="text-muted font-italic">Sin dirección especificada</span>'; ?>
                                </span>
                            </div>
                            <div class="col-md-3 mb-2">
                                <span class="text-muted d-block text-xs text-uppercase font-weight-bold mb-1">Teléfono / Contacto</span>
                                <span class="text-dark text-sm">
                                    <?php echo !empty($s['telefono']) ? e($s['telefono']) : 'No registrado'; ?>
                                </span>
                            </div>
                            <div class="col-md-3 mb-2">
                                <span class="text-muted d-block text-xs text-uppercase font-weight-bold mb-1">Estado</span>
                                <span class="badge badge-<?php echo ($s['estado'] ?? '') === 'Activa' ? 'success' : 'secondary'; ?> px-2 py-1">
                                    <?php echo e($s['estado'] ?? 'Activa'); ?>
                                </span>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" onclick="comoLlegar(<?php echo !empty($s['latitud']) ? (float)$s['latitud'] : 'null'; ?>, <?php echo !empty($s['longitud']) ? (float)$s['longitud'] : 'null'; ?>, '<?php echo e(addslashes($s['direccion'] ?? '')); ?>', '<?php echo e(addslashes($s['nombre'])); ?>')">
                                    Obtener Ruta GPS
                                </button>
                                <?php if (!empty($s['latitud']) && !empty($s['longitud'])): ?>
                                    <small class="text-muted ml-2">Coordenadas: <?php echo e($s['latitud']); ?>, <?php echo e($s['longitud']); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Sección de Canchas Registradas -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold text-dark text-uppercase text-xs mb-0">
                                Canchas y Campos de Juego Disponibles (<?php echo count($s['canchas'] ?? []); ?>)
                            </h6>
                            <?php if (AuthHelper::isAdmin()): ?>
                                <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" data-toggle="collapse" data-target="#formNuevaCancha<?php echo e($s['id']); ?>">
                                    + Agregar Cancha
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Formulario colapsable para agregar cancha directamente desde el modal -->
                        <?php if (AuthHelper::isAdmin()): ?>
                            <div class="collapse mb-3" id="formNuevaCancha<?php echo e($s['id']); ?>">
                                <div class="card card-body bg-light border p-3">
                                    <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.9rem;">Nueva Cancha para este Complejo</h6>
                                    <form action="<?php echo BASE_URL; ?>/sedes/<?php echo e($s['id']); ?>/canchas/guardar" method="post">
                                        <?php echo CsrfHelper::field(); ?>
                                        <input type="hidden" name="redirect_to" value="/sedes">
                                        <div class="row">
                                            <div class="col-sm-5 form-group mb-2">
                                                <label class="text-xs font-weight-bold mb-1">Nombre de Cancha <span class="text-danger">*</span></label>
                                                <input type="text" name="nombre_cancha" class="form-control form-control-sm" required placeholder="Ej. Cancha 1, Coliseo Principal">
                                            </div>
                                            <div class="col-sm-4 form-group mb-2">
                                                <label class="text-xs font-weight-bold mb-1">Superficie</label>
                                                <input type="text" name="tipo_superficie" class="form-control form-control-sm" placeholder="Ej. Césped, Parquet">
                                            </div>
                                            <div class="col-sm-3 form-group mb-2">
                                                <label class="text-xs font-weight-bold mb-1">Disponibilidad</label>
                                                <select name="estado" class="form-control form-control-sm">
                                                    <option value="Disponible">Disponible</option>
                                                    <option value="Mantenimiento">Mantenimiento</option>
                                                    <option value="Inactiva">Inactiva</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text-right mt-1">
                                            <button type="button" class="btn btn-xs btn-secondary mr-1" data-toggle="collapse" data-target="#formNuevaCancha<?php echo e($s['id']); ?>">Cancelar</button>
                                            <button type="submit" class="btn btn-xs btn-primary font-weight-bold">Guardar Cancha</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Listado de canchas -->
                        <?php if (empty($s['canchas'])): ?>
                            <div class="text-center text-muted py-3 border rounded bg-light text-sm">
                                No se han registrado canchas para este complejo deportivo.
                            </div>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($s['canchas'] as $c): ?>
                                    <div class="list-group-item p-3 mb-2 border rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="font-weight-bold text-dark d-block">
                                                    <?php echo e($c['nombre_cancha']); ?>
                                                </span>
                                                <span class="text-muted text-xs">
                                                    <strong>Superficie:</strong> <?php echo !empty($c['tipo_superficie']) ? e($c['tipo_superficie']) : 'No especificada'; ?>
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge badge-<?php echo $c['estado'] === 'Disponible' ? 'success' : ($c['estado'] === 'Mantenimiento' ? 'warning' : 'secondary'); ?> px-2 py-1 mb-1 d-inline-block">
                                                    <?php echo e($c['estado']); ?>
                                                </span>
                                                <?php if (AuthHelper::isAdmin()): ?>
                                                    <div>
                                                        <button type="button" class="btn btn-xs btn-outline-info font-weight-bold px-2 py-1 mr-1" data-toggle="collapse" data-target="#editModalCancha<?php echo e($c['id']); ?>">
                                                            Editar
                                                        </button>
                                                        <form action="<?php echo BASE_URL; ?>/canchas/<?php echo e($c['id']); ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta cancha?');">
                                                            <?php echo CsrfHelper::field(); ?>
                                                            <input type="hidden" name="redirect_to" value="/sedes">
                                                            <button type="submit" class="btn btn-xs btn-danger font-weight-bold px-2 py-1">
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Formulario de edición rápida de la cancha -->
                                        <?php if (AuthHelper::isAdmin()): ?>
                                            <div class="collapse mt-2 pt-2 border-top" id="editModalCancha<?php echo e($c['id']); ?>">
                                                <form action="<?php echo BASE_URL; ?>/canchas/<?php echo e($c['id']); ?>/actualizar" method="post">
                                                    <?php echo CsrfHelper::field(); ?>
                                                    <input type="hidden" name="redirect_to" value="/sedes">
                                                    <div class="row">
                                                        <div class="col-sm-5 form-group mb-2">
                                                            <label class="text-xs font-weight-bold mb-1">Nombre Cancha <span class="text-danger">*</span></label>
                                                            <input type="text" name="nombre_cancha" class="form-control form-control-sm" value="<?php echo e($c['nombre_cancha']); ?>" required>
                                                        </div>
                                                        <div class="col-sm-4 form-group mb-2">
                                                            <label class="text-xs font-weight-bold mb-1">Superficie</label>
                                                            <input type="text" name="tipo_superficie" class="form-control form-control-sm" value="<?php echo e($c['tipo_superficie'] ?? ''); ?>" placeholder="Ej. Sintético, Parquet">
                                                        </div>
                                                        <div class="col-sm-3 form-group mb-2">
                                                            <label class="text-xs font-weight-bold mb-1">Disponibilidad</label>
                                                            <select name="estado" class="form-control form-control-sm">
                                                                <option value="Disponible" <?php echo ($c['estado'] ?? '') === 'Disponible' ? 'selected' : ''; ?>>Disponible</option>
                                                                <option value="Mantenimiento" <?php echo ($c['estado'] ?? '') === 'Mantenimiento' ? 'selected' : ''; ?>>Mantenimiento</option>
                                                                <option value="Inactiva" <?php echo ($c['estado'] ?? '') === 'Inactiva' ? 'selected' : ''; ?>>Inactiva</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="text-right mt-1">
                                                        <button type="button" class="btn btn-xs btn-secondary mr-1" data-toggle="collapse" data-target="#editModalCancha<?php echo e($c['id']); ?>">Cancelar</button>
                                                        <button type="submit" class="btn btn-xs btn-primary font-weight-bold">Guardar Cambios de Cancha</button>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-secondary btn-sm font-weight-bold" data-dismiss="modal">Cerrar</button>
                        <?php if (AuthHelper::isAdmin()): ?>
                            <a href="<?php echo BASE_URL; ?>/sedes/<?php echo e($s['id']); ?>/editar" class="btn btn-primary btn-sm font-weight-bold">
                                Editar Sede
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
/**
 * Abre la ruta de navegación GPS hacia la sede deportiva
 * usando la geolocalización actual del navegador si está disponible.
 */
function comoLlegar(lat, lng, direccion, nombre) {
    var destino = '';
    if (lat && lng) {
        destino = lat + ',' + lng;
    } else if (direccion) {
        destino = encodeURIComponent(direccion + ' ' + (nombre || ''));
    } else if (nombre) {
        destino = encodeURIComponent(nombre);
    } else {
        alert('No hay información de ubicación o coordenadas GPS registradas para este complejo.');
        return;
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                var origin = pos.coords.latitude + ',' + pos.coords.longitude;
                var url = 'https://www.google.com/maps/dir/?api=1&origin=' + origin + '&destination=' + destino;
                window.open(url, '_blank');
            },
            function(error) {
                // Fallback directo a Google Maps si no se concedió permiso de GPS o tardó demasiado
                var url = 'https://www.google.com/maps/dir/?api=1&destination=' + destino;
                window.open(url, '_blank');
            },
            { timeout: 7000 }
        );
    } else {
        var url = 'https://www.google.com/maps/dir/?api=1&destination=' + destino;
        window.open(url, '_blank');
    }
}
</script>
