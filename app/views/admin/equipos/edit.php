<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Editar Equipo: <?php echo e($equipo['nombre']); ?>
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($equipo['id']); ?>/jugadores" class="btn btn-outline-primary font-weight-bold mr-1">
                    Ver Nómina
                </a>
                <a href="<?php echo BASE_URL; ?>/equipos" class="btn btn-secondary font-weight-bold">
                   <i class="fas fa-arrow-left mr-1"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline card-cosmol shadow-sm">
            <form action="<?php echo BASE_URL; ?>/equipos/<?php echo e($equipo['id']); ?>/actualizar" method="post">
                <?php echo CsrfHelper::field(); ?>

                <div class="card-body">
                    <div class="row">
                        <!-- Nombre del Equipo -->
                        <div class="col-md-8 form-group">
                            <label for="nombre">Nombre Oficial del Equipo <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo e($equipo['nombre']); ?>" required>
                        </div>

                        <!-- Nombre Corto / Alias -->
                        <div class="col-md-4 form-group">
                            <label for="nombre_corto">Nombre Corto / Sigla</label>
                            <input type="text" name="nombre_corto" id="nombre_corto" class="form-control" value="<?php echo e($equipo['nombre_corto']); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Delegación Institucional -->
                        <div class="col-md-6 form-group">
                            <label for="delegacion_id">Delegación Institucional que Representa <span class="text-danger">*</span></label>
                            <select name="delegacion_id" id="delegacion_id" class="form-control" required>
                                <?php foreach ($delegaciones as $del): ?>
                                    <option value="<?php echo e($del['id']); ?>" <?php echo ($equipo['delegacion_id'] == $del['id']) ? 'selected' : ''; ?>>
                                        <?php echo e($del['nombre']); ?> (<?php echo e($del['alias']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Torneo de Competencia -->
                        <div class="col-md-6 form-group">
                            <label for="torneo_id">Torneo en el que Compite <span class="text-danger">*</span></label>
                            <select name="torneo_id" id="torneo_id" class="form-control" required onchange="cargarGruposPorTorneo(this.value)">
                                <?php foreach ($torneos as $tor): ?>
                                    <option value="<?php echo e($tor['id']); ?>" <?php echo ($equipo['torneo_id'] == $tor['id']) ? 'selected' : ''; ?>>
                                        <?php echo e($tor['nombre']); ?> (<?php echo e($tor['disciplina_nombre']); ?> - <?php echo e($tor['rama']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Grupo Asignado -->
                        <div class="col-md-6 form-group">
                            <label for="grupo_id">Grupo Asignado</label>
                            <select name="grupo_id" id="grupo_id" class="form-control">
                                <option value="">-- Sin Grupo Asignado --</option>
                                <?php foreach ($grupos as $g): ?>
                                    <option value="<?php echo e($g['id']); ?>" <?php echo ($equipo['grupo_id'] == $g['id']) ? 'selected' : ''; ?>>
                                        <?php echo e($g['nombre_grupo']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Color de Indumentaria -->
                        <div class="col-md-6 form-group">
                            <label for="color_indumentaria">Color de Camiseta / Indumentaria</label>
                            <input type="text" name="color_indumentaria" id="color_indumentaria" class="form-control" value="<?php echo e($equipo['color_indumentaria']); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Delegado Contacto -->
                        <div class="col-md-6 form-group">
                            <label for="delegado_contacto">Nombre del Delegado Responsable</label>
                            <input type="text" name="delegado_contacto" id="delegado_contacto" class="form-control" value="<?php echo e($equipo['delegado_contacto']); ?>">
                        </div>

                        <!-- Teléfono Contacto -->
                        <div class="col-md-6 form-group">
                            <label for="telefono_contacto">Teléfono de Contacto</label>
                            <input type="text" name="telefono_contacto" id="telefono_contacto" class="form-control" value="<?php echo e($equipo['telefono_contacto']); ?>">
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-right">
                    <a href="<?php echo BASE_URL; ?>/equipos" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-info font-weight-bold">
                        Actualizar Equipo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cargarGruposPorTorneo(torneoId) {
    var selectGrupo = document.getElementById('grupo_id');
    selectGrupo.innerHTML = '<option value="">-- Cargando grupos... --</option>';

    if (!torneoId) {
        selectGrupo.innerHTML = '<option value="">-- Sin Grupo Asignado --</option>';
        return;
    }

    fetch('<?php echo BASE_URL; ?>/api/torneos/' + torneoId + '/grupos')
        .then(function(response) { return response.json(); })
        .then(function(grupos) {
            selectGrupo.innerHTML = '<option value="">-- Sin Grupo Asignado --</option>';
            if (grupos && grupos.length > 0) {
                grupos.forEach(function(g) {
                    var opt = document.createElement('option');
                    opt.value = g.id;
                    opt.textContent = g.nombre_grupo;
                    selectGrupo.appendChild(opt);
                });
            }
        })
        .catch(function(err) {
            selectGrupo.innerHTML = '<option value="">-- Sin Grupo Asignado --</option>';
        });
}
</script>
