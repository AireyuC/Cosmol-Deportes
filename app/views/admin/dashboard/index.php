<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-tachometer-alt text-primary mr-2"></i> Dashboard Olímpico
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/">Inicio</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <!-- Banner de la Olimpiada Activa -->
        <div class="callout callout-info bg-white shadow-sm mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="font-weight-bold text-primary mb-1">
                        <i class="fas fa-medal text-warning mr-2"></i> <?php echo e($olimpiada['nombre']); ?>
                    </h4>
                    <p class="text-muted mb-0 font-italic">
                        "<?php echo e($olimpiada['lema']); ?>"
                    </p>
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <span class="badge badge-success px-3 py-2 text-uppercase" style="font-size: 0.85rem;">
                        <i class="fas fa-fire mr-1"></i> <?php echo e($olimpiada['estado']); ?>
                    </span>
                    <a href="<?php echo BASE_URL; ?>/partidos" class="btn btn-outline-primary btn-sm ml-2">
                        <i class="fas fa-calendar-alt mr-1"></i> Ver Fixture
                    </a>
                </div>
            </div>
        </div>

        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow-sm">
                    <div class="inner">
                        <h3><?php echo e($kpis['disciplinas']); ?></h3>
                        <p class="font-weight-bold">Disciplinas Deportivas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-volleyball-ball"></i>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/disciplinas" class="small-box-footer">
                        Administrar <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner">
                        <h3><?php echo e($kpis['delegaciones']); ?></h3>
                        <p class="font-weight-bold">Delegaciones</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/equipos" class="small-box-footer">
                        Ver Delegaciones <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning shadow-sm">
                    <div class="inner">
                        <h3><?php echo e($kpis['equipos']); ?></h3>
                        <p class="font-weight-bold">Equipos en Grupos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/torneos" class="small-box-footer">
                        Ver Grupos <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger shadow-sm">
                    <div class="inner">
                        <h3><?php echo e($kpis['sanciones_act']); ?></h3>
                        <p class="font-weight-bold">Sanciones Activas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/sanciones" class="small-box-footer">
                        Gestionar / Quitar <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <!-- Columna Izquierda: Medallero General -->
            <div class="col-lg-7">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-award text-warning mr-2"></i> Medallero Olímpico General
                        </h3>
                        <div class="card-tools">
                            <a href="<?php echo BASE_URL; ?>/medallero" class="btn btn-tool btn-sm">
                                <i class="fas fa-external-link-alt"></i> Detalle Completo
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;">Pos</th>
                                    <th>Delegación Institucional</th>
                                    <th class="text-center" title="Oro"><span class="badge badge-warning" style="background-color: #ffd700; color: #000;"><i class="fas fa-medal"></i> Oro</span></th>
                                    <th class="text-center" title="Plata"><span class="badge badge-secondary" style="background-color: #c0c0c0; color: #000;"><i class="fas fa-medal"></i> Plata</span></th>
                                    <th class="text-center" title="Bronce"><span class="badge badge-dark" style="background-color: #cd7f32; color: #fff;"><i class="fas fa-medal"></i> Bronce</span></th>
                                    <th class="text-center font-weight-bold">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($medallero_top as $m): ?>
                                <tr>
                                    <td class="font-weight-bold text-center">
                                        <?php if ($m['pos'] === 1): ?>
                                            <span class="badge badge-warning"><i class="fas fa-crown"></i> 1</span>
                                        <?php else: ?>
                                            #<?php echo e($m['pos']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge mr-2" style="background-color: <?php echo e($m['color']); ?>;">&nbsp;</span>
                                        <span class="font-weight-bold"><?php echo e($m['delegacion']); ?></span>
                                    </td>
                                    <td class="text-center font-weight-bold"><?php echo e($m['oro']); ?></td>
                                    <td class="text-center font-weight-bold"><?php echo e($m['plata']); ?></td>
                                    <td class="text-center font-weight-bold"><?php echo e($m['bronce']); ?></td>
                                    <td class="text-center font-weight-bold text-primary" style="font-size: 1.1rem;"><?php echo e($m['total']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Disciplinas y Accesos Rápidos -->
            <div class="col-lg-5">
                <div class="card card-outline card-success shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-running text-success mr-2"></i> Disciplinas en Disputa
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            <?php foreach ($disciplinas as $d): ?>
                            <li class="item">
                                <div class="product-img">
                                    <span class="badge badge-<?php echo e($d['color']); ?> p-3 rounded-circle">
                                        <i class="fas fa-<?php echo e($d['icono']); ?> fa-lg"></i>
                                    </span>
                                </div>
                                <div class="product-info ml-3">
                                    <a href="<?php echo BASE_URL; ?>/torneos" class="product-title font-weight-bold">
                                        <?php echo e($d['nombre']); ?>
                                        <span class="badge badge-<?php echo e($d['color']); ?> float-right"><?php echo e($d['torneos']); ?> Torneos</span>
                                    </a>
                                    <span class="product-description text-muted">
                                        Ramas Masculino y Femenino por Grupos
                                    </span>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="card-footer text-center bg-white">
                        <a href="<?php echo BASE_URL; ?>/torneos" class="uppercase font-weight-bold text-success">
                            Gestionar Grupos y Fases <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Card de Acciones Rápidas -->
                <div class="card card-outline card-secondary shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-bolt text-warning mr-2"></i> Acciones Directas
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <a href="<?php echo BASE_URL; ?>/partidos" class="btn btn-outline-primary btn-block text-left">
                                    <i class="fas fa-stopwatch mr-1"></i> Cargar Resultados
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="<?php echo BASE_URL; ?>/sanciones" class="btn btn-outline-danger btn-block text-left">
                                    <i class="fas fa-user-slash mr-1"></i> Quitar Sanciones
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?php echo BASE_URL; ?>/jugadores" class="btn btn-outline-info btn-block text-left">
                                    <i class="fas fa-id-card mr-1"></i> Inscribir Atleta
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?php echo BASE_URL; ?>/posiciones" class="btn btn-outline-success btn-block text-left">
                                    <i class="fas fa-chart-line mr-1"></i> Ver Posiciones
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
