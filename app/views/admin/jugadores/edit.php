<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Editar Atleta: <?php echo e($jugador['nombres']); ?> <?php echo e($jugador['apellidos']); ?>
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($jugador['equipo_id']); ?>/jugadores" class="btn btn-secondary font-weight-bold">
                    Volver a Plantilla
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">Modificar Ficha del Jugador</h3>
            </div>
            <form action="<?php echo BASE_URL; ?>/jugadores/<?php echo e($jugador['id']); ?>/actualizar" method="post">
                <?php echo CsrfHelper::field(); ?>

                <div class="card-body">
                    <div class="row">
                        <!-- Equipo al que pertenece -->
                        <div class="col-md-8 form-group">
                            <label for="equipo_id">Equipo <span class="text-danger">*</span></label>
                            <select name="equipo_id" id="equipo_id" class="form-control" required>
                                <?php foreach ($equipos as $eq): ?>
                                    <option value="<?php echo e($eq['id']); ?>" <?php echo ($jugador['equipo_id'] == $eq['id']) ? 'selected' : ''; ?>>
                                        <?php echo e($eq['nombre']); ?> (<?php echo e($eq['delegacion_nombre']); ?> &bull; <?php echo e($eq['torneo_nombre']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Número de Camiseta (Dorsal) -->
                        <div class="col-md-4 form-group">
                            <label for="dorsal">
                                Número de Dorsal <span class="text-danger">*</span>
                                <span class="badge badge-warning text-dark ml-1"><i class="fas fa-lock mr-1"></i> Único</span>
                            </label>
                            <input type="number" name="dorsal" id="dorsal" class="form-control font-weight-bold" value="<?php echo e($jugador['dorsal']); ?>" min="0" max="999" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Documento de Identidad / CI -->
                        <div class="col-md-4 form-group">
                            <label for="documento">Documento de Identidad / C.I. <span class="text-danger">*</span></label>
                            <input type="text" name="documento" id="documento" class="form-control" value="<?php echo e($jugador['documento']); ?>" required>
                        </div>

                        <!-- Nombres -->
                        <div class="col-md-4 form-group">
                            <label for="nombres">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres" id="nombres" class="form-control" value="<?php echo e($jugador['nombres']); ?>" required>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-md-4 form-group">
                            <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" id="apellidos" class="form-control" value="<?php echo e($jugador['apellidos']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Posición -->
                        <div class="col-md-4 form-group">
                            <label for="posicion">Posición de Juego</label>
                            <input type="text" name="posicion" id="posicion" class="form-control" value="<?php echo e($jugador['posicion']); ?>">
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-md-4 form-group">
                            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="<?php echo e($jugador['fecha_nacimiento']); ?>">
                        </div>

                        <!-- Estado -->
                        <div class="col-md-4 form-group">
                            <label for="estado">Estado de Habilitación <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-control font-weight-bold" required>
                                <option value="Habilitado" <?php echo ($jugador['estado'] === 'Habilitado') ? 'selected' : ''; ?>>Habilitado (Puede Jugar)</option>
                                <option value="Suspendido" <?php echo ($jugador['estado'] === 'Suspendido') ? 'selected' : ''; ?>>Suspendido (Sanción activa)</option>
                                <option value="Inactivo" <?php echo ($jugador['estado'] === 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Foto -->
                        <div class="col-md-12 form-group">
                            <label for="foto_url">Fotografía / Foto de Carnet (URL o Ruta)</label>
                            <input type="text" name="foto_url" id="foto_url" class="form-control" value="<?php echo e($jugador['foto_url']); ?>">
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-right">
                    <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($jugador['equipo_id']); ?>/jugadores" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-sync mr-1"></i> Actualizar Atleta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
