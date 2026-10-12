<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="color: #0066B2;">
                    Delegaciones Institucionales
                </h1>
                <span class="text-muted text-sm">Áreas, departamentos y sindicatos de COSMOL R.L.</span>
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
        <div class="card card-cosmol shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title mb-0">Áreas y Secciones Participantes</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-cosmol">
                        <tr>
                            <th style="width: 50px;" class="text-center">Color</th>
                            <th>Nombre de la Delegación</th>
                            <th>Alias</th>
                            <th>Delegado Responsable</th>
                            <th>Teléfono</th>
                            <th class="text-center">Equipos</th>
                            <?php if (AuthHelper::isAdmin()): ?>
                                <th class="text-center" style="width: 160px;">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($delegaciones)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No hay delegaciones registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($delegaciones as $d): ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="d-inline-block rounded-circle border shadow-sm" style="background-color: <?php echo e($d['color_representativo']); ?>; width: 18px; height: 18px;"></span>
                                    </td>
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
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <td class="text-center">
                                            <a href="<?php echo BASE_URL; ?>/delegaciones/<?php echo $d['id']; ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Editar">
                                                Editar
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/delegaciones/<?php echo $d['id']; ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta delegación?');">
                                                <?php echo CsrfHelper::field(); ?>
                                                <button type="submit" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" title="Eliminar">
                                                    Eliminar
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
</div>
