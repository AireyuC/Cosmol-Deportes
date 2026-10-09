<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-medal text-warning mr-2"></i> Ediciones de Olimpiadas
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/olimpiadas/crear" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Nueva Olimpiada
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Historial de Eventos Olímpicos</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Nombre del Evento</th>
                            <th>Año</th>
                            <th>Lema Institucional</th>
                            <th>Período</th>
                            <th class="text-center">Estado</th>
                            <?php if (AuthHelper::isAdmin()): ?>
                                <th class="text-center" style="width: 140px;">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($olimpiadas)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No se han registrado olimpiadas aún.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($olimpiadas as $o): ?>
                                <tr>
                                    <td><strong>#<?php echo e($o['id']); ?></strong></td>
                                    <td class="font-weight-bold text-primary">
                                        <i class="fas fa-trophy mr-1 text-warning"></i>
                                        <?php echo e($o['nombre']); ?>
                                    </td>
                                    <td><span class="badge badge-secondary"><?php echo e($o['anio']); ?></span></td>
                                    <td class="text-muted font-italic">"<?php echo e($o['lema']); ?>"</td>
                                    <td>
                                        <small>
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            <?php echo !empty($o['fecha_inicio']) ? e($o['fecha_inicio']) : 'Por definir'; ?> al 
                                            <?php echo !empty($o['fecha_fin']) ? e($o['fecha_fin']) : 'Por definir'; ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($o['estado'] === 'En Curso'): ?>
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-play mr-1"></i> En Curso</span>
                                        <?php elseif ($o['estado'] === 'Finalizada'): ?>
                                            <span class="badge badge-secondary px-2 py-1"><i class="fas fa-check mr-1"></i> Finalizada</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Planificada</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <td class="text-center">
                                            <a href="<?php echo BASE_URL; ?>/olimpiadas/<?php echo $o['id']; ?>/editar" class="btn btn-sm btn-info" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/olimpiadas/<?php echo $o['id']; ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta Olimpiada?');">
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
