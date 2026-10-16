<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Delegaciones Institucionales
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/delegaciones/crear" class="btn btn-primary font-weight-bold">
                        Nueva Delegación
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card shadow-sm mb-4 card-cosmol">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-cosmol">
                        <tr>
                            <th>Nombre de la Delegación</th>
                            <th>Alias</th>
                            <th>Delegado Responsable</th>
                            <th>Teléfono</th>
                            <th class="text-center">Equipos</th>
                            <th class="text-center text-nowrap" style="width: 240px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($delegaciones)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hay delegaciones registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($delegaciones as $d): ?>
                                <tr>
                                    <td class="font-weight-bold text-dark">
                                        <?php echo e($d['nombre']); ?>
                                    </td>
                                    <td><span class="badge py-1 px-2 font-weight-bold" style="background-color: #EAF4FB; color: #0066B2; border: 1px solid #BFE2F7;"><?php echo e($d['alias']); ?></span></td>
                                    <td class="text-dark">
                                        <?php echo !empty($d['responsable']) ? e($d['responsable']) : '<span class="text-muted">Sin asignar</span>'; ?>
                                    </td>
                                    <td class="text-muted text-sm">
                                        <?php echo !empty($d['telefono']) ? e($d['telefono']) : '-'; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge py-1 px-2" style="background-color: #00A3E0; color: #FFFFFF;">
                                            <?php echo e($d['total_equipos']); ?> Equipos
                                        </span>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <div class="d-inline-flex align-items-center justify-content-center" style="gap: 8px;">
                                            <a href="<?php echo BASE_URL; ?>/delegaciones/<?php echo (int)$d['id']; ?>" class="btn btn-xs btn-outline-primary font-weight-bold px-2 py-1" title="Ver Historial de Partidos">
                                                <i class="fas fa-history mr-1"></i> Historial
                                            </a>
                                            <?php if (AuthHelper::isAdmin()): ?>
                                                <a href="<?php echo BASE_URL; ?>/delegaciones/<?php echo (int)$d['id']; ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Editar">
                                                    Editar
                                                </a>
                                                <form action="<?php echo BASE_URL; ?>/delegaciones/<?php echo (int)$d['id']; ?>/eliminar" method="post" class="d-inline m-0 p-0" onsubmit="return confirm('¿Eliminar esta delegación?');">
                                                    <?php echo CsrfHelper::field(); ?>
                                                    <button type="submit" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" title="Eliminar">
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
        </div>
    </div>
</div>
