<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Ficha del Atleta: <?php echo e($jugador['nombres']); ?> <?php echo e($jugador['apellidos']); ?>
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($jugador['equipo_id']); ?>/jugadores" class="btn btn-secondary font-weight-bold mr-1">
                    Plantilla del Equipo
                </a>
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/jugadores/<?php echo e($jugador['id']); ?>/editar" class="btn btn-primary font-weight-bold">
                        Editar Ficha
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card card-outline card-primary shadow">
                    <div class="card-body box-profile text-center py-4">
                        <!-- Dorsal e Identificador Visual -->
                        <div class="mb-3">
                            <span class="badge badge-dark font-weight-bold shadow-sm px-4 py-3" style="font-size: 2.2rem; border-radius: 50px;">
                                #<?php echo e($jugador['dorsal']); ?>
                            </span>
                        </div>

                        <h3 class="profile-username font-weight-bold text-dark mb-1">
                            <?php echo e($jugador['nombres']); ?> <?php echo e($jugador['apellidos']); ?>
                        </h3>
                        <p class="text-muted mb-2 font-weight-bold">
                            <i class="fas fa-id-card mr-1"></i> CI/Documento: <?php echo e($jugador['documento']); ?>
                        </p>

                        <!-- Estado de Habilitación -->
                        <div class="mb-4">
                            <?php if ($jugador['estado'] === 'Habilitado'): ?>
                                <span class="badge badge-success px-3 py-2" style="font-size: 0.95rem;">
                                    <i class="fas fa-check-circle mr-1"></i> Habilitado para Competir
                                </span>
                            <?php elseif ($jugador['estado'] === 'Suspendido'): ?>
                                <span class="badge badge-danger px-3 py-2" style="font-size: 0.95rem;">
                                    <i class="fas fa-ban mr-1"></i> Suspendido / Inhabilitado
                                </span>
                            <?php else: ?>
                                <span class="badge badge-secondary px-3 py-2" style="font-size: 0.95rem;">
                                    <i class="fas fa-clock mr-1"></i> Inactivo
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Detalles Deportivos -->
                        <ul class="list-group list-group-unbordered text-left mb-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b><i class="fas fa-shield-alt text-info mr-2"></i> Equipo:</b>
                                <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($jugador['equipo_id']); ?>/jugadores" class="font-weight-bold text-dark">
                                    <?php echo e($jugador['equipo_nombre']); ?>
                                </a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b><i class="fas fa-building text-primary mr-2"></i> Delegación Institucional:</b>
                                <span class="badge px-2 py-1 font-weight-bold" style="background-color: <?php echo e($jugador['color_representativo']); ?>; color: #fff;">
                                    <?php echo e($jugador['delegacion_nombre']); ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b><i class="fas fa-trophy text-warning mr-2"></i> Torneo:</b>
                                <span class="font-weight-bold"><?php echo e($jugador['torneo_nombre']); ?> (<?php echo e($jugador['torneo_rama']); ?>)</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b><i class="fas fa-running text-success mr-2"></i> Posición en Campo:</b>
                                <span class="text-dark font-weight-bold"><?php echo !empty($jugador['posicion']) ? e($jugador['posicion']) : 'No asignada'; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b><i class="fas fa-birthday-cake text-danger mr-2"></i> Fecha de Nacimiento:</b>
                                <span><?php echo !empty($jugador['fecha_nacimiento']) ? e($jugador['fecha_nacimiento']) : 'No registrada'; ?></span>
                            </li>
                        </ul>

                        <div class="text-center">
                            <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($jugador['equipo_id']); ?>/jugadores" class="btn btn-outline-primary font-weight-bold mr-2">
                                <i class="fas fa-users mr-1"></i> Ver Toda la Plantilla
                            </a>
                            <a href="<?php echo BASE_URL; ?>/jugadores" class="btn btn-outline-secondary font-weight-bold">
                                <i class="fas fa-list mr-1"></i> Padrón General
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
