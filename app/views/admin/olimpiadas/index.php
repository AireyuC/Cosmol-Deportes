<!-- Content Header -->
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="color: #0066B2;">
                    Ediciones de Olimpiadas
                </h1>
                <span class="text-muted text-sm">Eventos multideportivos institucionales de COSMOL R.L.</span>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/olimpiadas/crear" class="btn btn-primary font-weight-bold">
                        Nueva Olimpiada
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card card-cosmol shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title mb-0">Historial de Eventos Olímpicos</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-cosmol">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Nombre del Evento</th>
                            <th>Año</th>
                            <th>Lema Institucional</th>
                            <th>Período</th>
                            <th class="text-center">Estado</th>
                            <?php if (AuthHelper::isAdmin()): ?>
                                <th class="text-center" style="width: 160px;">Acciones</th>
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
                                    <td class="font-weight-bold text-secondary">#<?php echo e($o['id']); ?></td>
                                    <td class="font-weight-bold text-dark">
                                        <?php echo e($o['nombre']); ?>
                                    </td>
                                    <td><span class="badge py-1 px-2 font-weight-bold" style="background-color: #EAF4FB; color: #0066B2; border: 1px solid #BFE2F7;"><?php echo e($o['anio']); ?></span></td>
                                    <td class="text-muted font-italic text-sm">"<?php echo e($o['lema']); ?>"</td>
                                    <td class="text-muted text-sm">
                                        <?php echo !empty($o['fecha_inicio']) ? e($o['fecha_inicio']) : 'Por definir'; ?> al 
                                        <?php echo !empty($o['fecha_fin']) ? e($o['fecha_fin']) : 'Por definir'; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($o['estado'] === 'En Curso'): ?>
                                            <span class="badge px-2 py-1 font-weight-bold text-white" style="background-color: #00A3E0;">En Curso</span>
                                        <?php elseif ($o['estado'] === 'Finalizada'): ?>
                                            <span class="badge badge-secondary px-2 py-1">Finalizada</span>
                                        <?php else: ?>
                                            <span class="badge badge-light border text-muted px-2 py-1">Planificada</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <td class="text-center">
                                            <a href="<?php echo BASE_URL; ?>/olimpiadas/<?php echo $o['id']; ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Editar">
                                                Editar
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/olimpiadas/<?php echo $o['id']; ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta Olimpiada?');">
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
