<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Inscribir Atleta en Nómina
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo $equipoId ? BASE_URL . '/equipos/' . $equipoId . '/jugadores' : BASE_URL . '/jugadores'; ?>" class="btn btn-secondary font-weight-bold">
                    Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">Ficha de Inscripción de Jugador</h3>
            </div>
            <form action="<?php echo BASE_URL; ?>/jugadores/guardar" method="post">
                <?php echo CsrfHelper::field(); ?>
                <?php if ($equipoId): ?>
                    <input type="hidden" name="volver_a_equipo" value="1">
                <?php endif; ?>

                <div class="card-body">
                    <div class="row">
                        <!-- Equipo al que pertenece -->
                        <div class="col-md-8 form-group">
                            <label for="equipo_id">Equipo de Destino <span class="text-danger">*</span></label>
                            <select name="equipo_id" id="equipo_id" class="form-control" required>
                                <option value="">-- Seleccionar Equipo --</option>
                                <?php foreach ($equipos as $eq): ?>
                                    <option value="<?php echo e($eq['id']); ?>" <?php echo ($equipoId == $eq['id']) ? 'selected' : ''; ?>>
                                        <?php echo e($eq['nombre']); ?> (<?php echo e($eq['delegacion_nombre']); ?> &bull; <?php echo e($eq['torneo_nombre']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Número de Camiseta (Dorsal) -->
                        <div class="col-md-4 form-group">
                            <label for="dorsal">
                                Número de Dorsal <span class="text-danger">*</span>
                                <span class="badge badge-warning text-dark ml-1" title="Regla obligatoria: No puede haber 2 jugadores con el mismo número en un mismo equipo">
                                    <i class="fas fa-lock mr-1"></i> Único por equipo
                                </span>
                            </label>
                            <input type="number" name="dorsal" id="dorsal" class="form-control font-weight-bold" placeholder="Ej. 10, 7, 1..." min="0" max="999" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Documento de Identidad / CI -->
                        <div class="col-md-4 form-group">
                            <label for="documento">Documento de Identidad / C.I. <span class="text-danger">*</span></label>
                            <input type="text" name="documento" id="documento" class="form-control" placeholder="Ej. 5432101 CB" required>
                        </div>

                        <!-- Nombres -->
                        <div class="col-md-4 form-group">
                            <label for="nombres">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Ej. Carlos Andrés" required>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-md-4 form-group">
                            <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Ej. Mendoza Rojas" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Posición -->
                        <div class="col-md-4 form-group">
                            <label for="posicion">Posición de Juego</label>
                            <input type="text" name="posicion" id="posicion" class="form-control" placeholder="Ej. Delantero, Arquero, Armador, Base, Defensa...">
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-md-4 form-group">
                            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control">
                        </div>

                        <!-- Estado Inicial -->
                        <div class="col-md-4 form-group">
                            <label for="estado">Estado Inicial de Habilitación <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-control" required>
                                <option value="Habilitado" selected>Habilitado (Apto para jugar)</option>
                                <option value="Suspendido">Suspendido (Con sanción previa)</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Foto o URL de Fotografía -->
                        <div class="col-md-12 form-group">
                            <label for="foto_url">Fotografía / Foto de Carnet (URL o Ruta)</label>
                            <input type="text" name="foto_url" id="foto_url" class="form-control" placeholder="Opcional: Enlace a imagen o foto de perfil">
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-right">
                    <a href="<?php echo $equipoId ? BASE_URL . '/equipos/' . $equipoId . '/jugadores' : BASE_URL . '/jugadores'; ?>" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Inscribir Atleta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
