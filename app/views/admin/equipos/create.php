<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Registrar Nuevo Equipo
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo $torneoId ? BASE_URL . '/torneos/' . $torneoId : BASE_URL . '/equipos'; ?>" class="btn btn-secondary font-weight-bold">
                    Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline card-info shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">Ficha de Inscripción del Equipo</h3>
            </div>
            <form action="<?php echo BASE_URL; ?>/equipos/guardar" method="post">
                <?php echo CsrfHelper::field(); ?>
                <?php if ($torneoId): ?>
                    <input type="hidden" name="volver_a_torneo" value="1">
                <?php endif; ?>

                <div class="card-body">
                    <div class="row">
                        <!-- Nombre del Equipo -->
                        <div class="col-md-8 form-group">
                            <label for="nombre">Nombre Oficial del Equipo <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej. Comercial FC, Operaciones United" required autofocus>
                        </div>

                        <!-- Nombre Corto / Alias -->
                        <div class="col-md-4 form-group">
                            <label for="nombre_corto">Nombre Corto / Sigla</label>
                            <input type="text" name="nombre_corto" id="nombre_corto" class="form-control" placeholder="Ej. COM, OPE">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Delegación Institucional -->
                        <div class="col-md-6 form-group">
                            <label for="delegacion_id">Delegación Institucional que Representa <span class="text-danger">*</span></label>
                            <select name="delegacion_id" id="delegacion_id" class="form-control" required>
                                <option value="">-- Seleccionar Delegación --</option>
                                <?php foreach ($delegaciones as $del): ?>
                                    <option value="<?php echo e($del['id']); ?>">
                                        <?php echo e($del['nombre']); ?> (<?php echo e($del['alias']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Torneo de Competencia -->
                        <div class="col-md-6 form-group">
                            <label for="torneo_id">Torneo en el que Compite <span class="text-danger">*</span></label>
                            <select name="torneo_id" id="torneo_id" class="form-control" required onchange="cargarGruposPorTorneo(this.value)">
                                <option value="">-- Seleccionar Torneo --</option>
                                <?php foreach ($torneos as $tor): ?>
                                    <option value="<?php echo e($tor['id']); ?>" <?php echo ($torneoId == $tor['id']) ? 'selected' : ''; ?>>
                                        <?php echo e($tor['nombre']); ?> (<?php echo e($tor['disciplina_nombre']); ?> - <?php echo e($tor['rama']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Grupo Asignado (Opcional) -->
                        <div class="col-md-6 form-group">
                            <label for="grupo_id">Grupo Inicial (Opcional)</label>
                            <select name="grupo_id" id="grupo_id" class="form-control">
                                <option value="">-- Sin Grupo (Asignar más tarde) --</option>
                                <?php foreach ($grupos as $g): ?>
                                    <option value="<?php echo e($g['id']); ?>"><?php echo e($g['nombre_grupo']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Puedes asignarlo ahora o distribuirlo después en el tablero de grupos.</small>
                        </div>

                        <!-- Color de Indumentaria -->
                        <div class="col-md-6 form-group">
                            <label for="color_indumentaria">Color de Camiseta / Indumentaria</label>
                            <input type="text" name="color_indumentaria" id="color_indumentaria" class="form-control" placeholder="Ej. Azul y Blanco, Verde con franja negra">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Delegado Contacto -->
                        <div class="col-md-6 form-group">
                            <label for="delegado_contacto">Nombre del Delegado Responsable</label>
                            <input type="text" name="delegado_contacto" id="delegado_contacto" class="form-control" placeholder="Persona a cargo del equipo">
                        </div>

                        <!-- Teléfono Contacto -->
                        <div class="col-md-6 form-group">
                            <label for="telefono_contacto">Teléfono de Contacto</label>
                            <input type="text" name="telefono_contacto" id="telefono_contacto" class="form-control" placeholder="Número de celular o WhatsApp">
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-right">
                    <a href="<?php echo $torneoId ? BASE_URL . '/torneos/' . $torneoId : BASE_URL . '/equipos'; ?>" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-info font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Inscribir Equipo
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
        selectGrupo.innerHTML = '<option value="">-- Sin Grupo (Asignar más tarde) --</option>';
        return;
    }

    fetch('<?php echo BASE_URL; ?>/api/torneos/' + torneoId + '/grupos')
        .then(function(response) { return response.json(); })
        .then(function(grupos) {
            selectGrupo.innerHTML = '<option value="">-- Sin Grupo (Asignar más tarde) --</option>';
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
            selectGrupo.innerHTML = '<option value="">-- Sin Grupo (Asignar más tarde) --</option>';
        });
}
</script>
