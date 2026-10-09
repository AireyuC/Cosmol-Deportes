<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-shield-alt text-info mr-2"></i> Delegaciones Institucionales
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/delegaciones/crear" class="btn btn-info">
                        <i class="fas fa-plus mr-1"></i> Nueva Delegación
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline card-info shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Áreas, Departamentos y Secciones Participantes</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">Color</th>
                            <th>Nombre de la Delegación</th>
                            <th>Alias</th>
                            <th>Delegado Responsable</th>
                            <th>Teléfono</th>
                            <th class="text-center">Equipos Inscritos</th>
                            <?php if (AuthHelper::isAdmin()): ?>
                                <th class="text-center" style="width: 140px;">Acciones</th>
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
                                        <span class="badge border shadow-sm p-2" style="background-color: <?php echo e($d['color_representativo']); ?>; border-radius: 50%; display: inline-block; width: 24px; height: 24px;">&nbsp;</span>
                                    </td>
                                    <td class="font-weight-bold text-dark">
                                        <?php echo e($d['nombre']); ?>
                                    </td>
                                    <td><span class="badge badge-light border"><?php echo e($d['alias']); ?></span></td>
                                    <td>
                                        <i class="fas fa-user-tie text-muted mr-1"></i>
                                        <?php echo !empty($d['responsable']) ? e($d['responsable']) : 'Sin asignar'; ?>
                                    </td>
                                    <td class="text-sm">
                                        <?php echo !empty($d['telefono']) ? '<i class="fas fa-phone mr-1 text-muted"></i> ' . e($d['telefono']) : '-'; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-primary px-2 py-1">
                                            <i class="fas fa-users mr-1"></i> <?php echo e($d['total_equipos']); ?> Equipos
                                        </span>
                                    </td>
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <td class="text-center">
                                            <a href="<?php echo BASE_URL; ?>/delegaciones/<?php echo $d['id']; ?>/editar" class="btn btn-sm btn-info" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/delegaciones/<?php echo $d['id']; ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta delegación?');">
                                                <?php echo CsrfHelper::field(); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
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
</div>
