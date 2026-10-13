<?php
/**
 * Panel de Control Principal (Dashboard)
 *
 * @var array  $olimpiada
 * @var array  $kpis
 * @var array  $medallero_top
 * @var array  $disciplinas
 * @var array  $partidos_hoy
 * @var string $fecha_hoy
 */
?>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <!-- Banner de la Olimpiada Activa -->
        <?php 
            $olimpiada = isset($olimpiada) ? $olimpiada : ['nombre' => '', 'lema' => '', 'estado' => '']; 
            $kpis = isset($kpis) ? $kpis : ['disciplinas' => 0, 'delegaciones' => 0, 'equipos' => 0, 'partidos_hoy' => 0, 'sanciones_act' => 0];
            $medallero_top = isset($medallero_top) ? $medallero_top : [];
            $disciplinas = isset($disciplinas) ? $disciplinas : [];
            $partidos_hoy = isset($partidos_hoy) ? $partidos_hoy : [];
            $fecha_hoy = isset($fecha_hoy) ? $fecha_hoy : date('Y-m-d');
        ?>
        <div class="callout bg-white shadow-sm mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="font-weight-bold mb-1" style="color: #0066B2;">
                        <?php echo e($olimpiada['nombre']); ?>
                    </h4>
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <span class="badge px-3 py-2 text-uppercase text-white font-weight-bold" style="background-color: #00A3E0; font-size: 0.8rem;">
                        <?php echo e($olimpiada['estado']); ?>
                    </span>
                    <a href="<?php echo BASE_URL; ?>/partidos" class="btn btn-outline-primary btn-sm ml-2">
                        Ver Fixture
                    </a>
                </div>
            </div>
        </div>

        <!-- Indicadores Principales (Sin iconos innecesarios) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box box-cosmol-azul">
                    <div class="inner">
                        <h3><?php echo e($kpis['disciplinas']); ?></h3>
                        <p>Disciplinas Deportivas</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/disciplinas" class="small-box-footer">
                        Ver &rsaquo;
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box box-cosmol-celeste">
                    <div class="inner">
                        <h3><?php echo e($kpis['delegaciones']); ?></h3>
                        <p>Delegaciones</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/delegaciones" class="small-box-footer">
                        Ver &rsaquo;
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box box-cosmol-oscuro">
                    <div class="inner">
                        <h3><?php echo e($kpis['equipos']); ?></h3>
                        <p>Equipos en Grupos</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/torneos" class="small-box-footer">
                        Ver &rsaquo;
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box box-cosmol-alerta">
                    <div class="inner">
                        <h3><?php echo e($kpis['sanciones_act']); ?></h3>
                        <p>Sanciones Activas</p>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/sanciones" class="small-box-footer">
                        Ver &rsaquo;
                    </a>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <!-- Partidos de Hoy: Lista Simple -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0 card-title-cosmol">
                            Partidos de Hoy
                        </h3>
                        <div class="card-tools ml-auto">
                            <a href="<?php echo BASE_URL; ?>/partidos" class="btn btn-sm btn-outline-primary py-1 px-2 text-xs font-weight-bold">
                                Ver Fixture
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($partidos_hoy)): ?>
                            <div class="text-center text-muted py-3 text-sm">
                                No hay partidos programados para hoy.
                            </div>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($partidos_hoy as $p): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted font-weight-bold mr-3" style="min-width: 50px;">
                                                <?php echo !empty($p['hora_partido']) ? substr($p['hora_partido'], 0, 5) : '--:--'; ?>
                                            </span>
                                            <div>
                                                <a href="<?php echo BASE_URL; ?>/partidos/<?php echo e($p['id']); ?>" class="font-weight-bold text-dark">
                                                    <?php echo e($p['local_nombre']); ?> vs <?php echo e($p['visitante_nombre']); ?>
                                                </a>
                                                <small class="text-muted d-block">
                                                    <?php echo e($p['disciplina_nombre']); ?><?php if (!empty($p['nombre_cancha'])): ?> &bull; <?php echo e($p['nombre_cancha']); ?><?php endif; ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <?php if ($p['marcador_local'] !== null && $p['marcador_visitante'] !== null): ?>
                                                <span class="font-weight-bold text-dark mr-2">
                                                    <?php echo (int)$p['marcador_local']; ?> - <?php echo (int)$p['marcador_visitante']; ?>
                                                </span>
                                            <?php endif; ?>
                                            <span class="badge badge-light border text-secondary font-weight-normal px-2 py-1">
                                                <?php echo str_replace('_', ' ', e($p['estado'])); ?>
                                            </span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Columna Izquierda: Medallero General -->
            <div class="col-lg-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0 card-title-cosmol">
                            Medallero Olímpico Oficial
                        </h3>
                        <div class="card-tools ml-auto">
                            <a href="<?php echo BASE_URL; ?>/medallero" class="btn btn-sm btn-outline-primary py-1 px-2 text-xs font-weight-bold">
                                Detalle
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle mb-0">
                            <thead class="thead-cosmol">
                                <tr>
                                    <th style="width: 60px;" class="text-center">Pos</th>
                                    <th>Delegación</th>
                                    <th class="text-center" style="width: 75px;"><span class="badge badge-oro px-2 py-1">Oro</span></th>
                                    <th class="text-center" style="width: 75px;"><span class="badge badge-plata px-2 py-1">Plata</span></th>
                                    <th class="text-center" style="width: 75px;"><span class="badge badge-bronce px-2 py-1">Bronce</span></th>
                                    <th class="text-center" style="width: 70px;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($medallero_top)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">No hay medallas asignadas aún.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($medallero_top as $m): ?>
                                    <tr>
                                        <td class="font-weight-bold text-center text-secondary">
                                            <?php echo e($m['pos']); ?>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-dark"><?php echo e($m['delegacion']); ?></span>
                                        </td>
                                        <td class="text-center font-weight-bold"><?php echo e($m['oro']); ?></td>
                                        <td class="text-center font-weight-bold"><?php echo e($m['plata']); ?></td>
                                        <td class="text-center font-weight-bold"><?php echo e($m['bronce']); ?></td>
                                        <td class="text-center font-weight-bold" style="color: #0066B2; font-size: 1.05rem;"><?php echo e($m['total']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Disciplinas y Accesos Rápidos -->
            <div class="col-lg-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            Disciplinas en Competencia
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($disciplinas as $d): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <a href="<?php echo BASE_URL; ?>/torneos" class="font-weight-bold d-block" style="color: #0066B2;">
                                        <?php echo e($d['nombre']); ?>
                                    </a>
                                    <small class="text-muted">Masculino y Femenino</small>
                                </div>
                                <span class="badge py-1 px-2 font-weight-bold" style="background-color: #EAF4FB; color: #0066B2; border: 1px solid #BFE2F7;">
                                    <?php echo e($d['torneos']); ?> Torneos
                                </span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="card-footer text-center bg-white py-2">
                        <a href="<?php echo BASE_URL; ?>/torneos" class="font-weight-bold text-sm" style="color: #00A3E0;">
                            Gestionar Grupos y Fases &rsaquo;
                        </a>
                    </div>
                </div>

                <!-- Card de Acciones Rápidas (Sin iconos innecesarios) -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h3 class="card-title mb-0 card-title-cosmol">
                            Acciones Rápidas
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <a href="<?php echo BASE_URL; ?>/partidos" class="btn btn-outline-primary btn-block text-center font-weight-bold py-2">
                                    Cargar Resultados
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="<?php echo BASE_URL; ?>/sanciones" class="btn btn-outline-danger btn-block text-center font-weight-bold py-2">
                                    Quitar Sanciones
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?php echo BASE_URL; ?>/jugadores" class="btn btn-outline-info btn-block text-center font-weight-bold py-2">
                                    Inscribir Atleta
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?php echo BASE_URL; ?>/posiciones" class="btn btn-outline-secondary btn-block text-center font-weight-bold py-2">
                                    Ver Posiciones
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->
