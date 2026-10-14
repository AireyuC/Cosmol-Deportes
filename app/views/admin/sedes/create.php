<?php
/**
 * Vista de registro de nueva sede y complejo deportivo
 *
 * @var array|null $old
 */
$nombre = isset($old['nombre']) ? $old['nombre'] : '';
$direccion = isset($old['direccion']) ? $old['direccion'] : '';
$telefono = isset($old['telefono']) ? $old['telefono'] : '';
$latitud = isset($old['latitud']) ? $old['latitud'] : '';
$longitud = isset($old['longitud']) ? $old['longitud'] : '';
$estado = isset($old['estado']) ? $old['estado'] : 'Activa';

$nombreCancha = isset($old['nombre_cancha']) ? $old['nombre_cancha'] : '';
$tipoSuperficie = isset($old['tipo_superficie']) ? $old['tipo_superficie'] : '';
$estadoCancha = isset($old['estado_cancha']) ? $old['estado_cancha'] : 'Disponible';
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Registrar Nueva Sede
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/sedes" class="btn btn-secondary btn-sm font-weight-bold">
                    Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <form action="<?php echo BASE_URL; ?>/sedes/guardar" method="post">
            <?php echo CsrfHelper::field(); ?>

            <div class="row">
                <!-- Columna Izquierda: Datos del Complejo Deportivo -->
                <div class="col-lg-6 mb-3">
                    <div class="card card-outline shadow-sm h-100 mb-0">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold text-dark">Información del Complejo Deportivo</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre">Nombre del Complejo / Sede <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required value="<?php echo e($nombre); ?>" placeholder="Ej. Complejo Deportivo Central Cosmol, Coliseo Municipal...">
                            </div>

                            <div class="form-group">
                                <label for="direccion">Dirección / Ubicación</label>
                                <input type="text" name="direccion" id="direccion" class="form-control" value="<?php echo e($direccion); ?>" placeholder="Ej. Av. Principal #450, Barrio Las Palmas">
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="telefono">Teléfono / Celular de Contacto</label>
                                    <input type="text" name="telefono" id="telefono" class="form-control" value="<?php echo e($telefono); ?>" placeholder="Ej. 70011223 o 33445566">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="estado">Estado de la Sede</label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="Activa" <?php echo $estado === 'Activa' ? 'selected' : ''; ?>>Activa</option>
                                        <option value="Inactiva" <?php echo $estado === 'Inactiva' ? 'selected' : ''; ?>>Inactiva</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="latitud">Latitud GPS (para "Cómo llegar")</label>
                                    <input type="text" name="latitud" id="latitud" class="form-control" placeholder="Ej: -17.783300" value="<?php echo e($latitud); ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="longitud">Longitud GPS (para "Cómo llegar")</label>
                                    <input type="text" name="longitud" id="longitud" class="form-control" placeholder="Ej: -63.182100" value="<?php echo e($longitud); ?>">
                                </div>
                            </div>
                            <small class="text-muted d-block">Las coordenadas GPS permiten que los delegados y deportistas obtengan la ruta de navegación interactiva en tiempo real.</small>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Cancha Inicial (Opcional) -->
                <div class="col-lg-6 mb-3">
                    <div class="card card-outline shadow-sm h-100 mb-0">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold text-dark">Cancha Inicial (Opcional)</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted text-sm mb-3">
                                Puedes registrar la primera cancha de este complejo ahora mismo, o agregarla posteriormente desde la lista de sedes.
                            </p>

                            <div class="form-group">
                                <label for="nombre_cancha">Nombre o Identificador de la Cancha</label>
                                <input type="text" name="nombre_cancha" id="nombre_cancha" class="form-control" value="<?php echo e($nombreCancha); ?>" placeholder="Ej. Cancha Principal de Césped, Coliseo Techado, Cancha 1...">
                            </div>

                            <div class="form-group">
                                <label for="tipo_superficie">Tipo de Superficie</label>
                                <input type="text" name="tipo_superficie" id="tipo_superficie" class="form-control" value="<?php echo e($tipoSuperficie); ?>" placeholder="Ej. Césped Natural, Sintético, Parquet Flotante, Cemento...">
                            </div>

                            <div class="form-group mb-0">
                                <label for="estado_cancha">Disponibilidad de la Cancha</label>
                                <select name="estado_cancha" id="estado_cancha" class="form-control">
                                    <option value="Disponible" <?php echo $estadoCancha === 'Disponible' ? 'selected' : ''; ?>>Disponible</option>
                                    <option value="Mantenimiento" <?php echo $estadoCancha === 'Mantenimiento' ? 'selected' : ''; ?>>En Mantenimiento</option>
                                    <option value="Inactiva" <?php echo $estadoCancha === 'Inactiva' ? 'selected' : ''; ?>>Inactiva</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <a href="<?php echo BASE_URL; ?>/sedes" class="btn btn-secondary font-weight-bold">Cancelar</a>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        Guardar Sede y Cancha
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
