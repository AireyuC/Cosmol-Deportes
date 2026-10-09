<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión | <?php echo APP_NAME; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style: AdminLTE 3.2.0 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page bg-light">
<div class="login-box">
    <!-- Logo -->
    <div class="login-logo mb-3">
        <a href="<?php echo BASE_URL; ?>/" class="font-weight-bold text-dark">
            <i class="fas fa-trophy text-warning mr-1"></i>
            COSMOL <span class="text-primary">DEPORTES</span>
        </a>
        <div class="text-muted" style="font-size: 0.9rem;">Plataforma Oficial de Olimpiadas</div>
    </div>

    <!-- Card de Login -->
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body login-card-body p-4">
            <p class="login-box-msg font-weight-bold text-secondary">
                Ingrese sus credenciales de acceso
            </p>

            <!-- Alertas Flash -->
            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-danger py-2 px-3 text-sm">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <?php echo e(Session::getFlash('error')); ?>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('success')): ?>
                <div class="alert alert-success py-2 px-3 text-sm">
                    <i class="fas fa-check-circle mr-1"></i>
                    <?php echo e(Session::getFlash('success')); ?>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('warning')): ?>
                <div class="alert alert-warning py-2 px-3 text-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <?php echo e(Session::getFlash('warning')); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/login" method="post">
                <?php echo CsrfHelper::field(); ?>

                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required value="<?php echo isset($oldEmail) ? e($oldEmail) : ''; ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope text-muted"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock text-muted"></span>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center mb-3">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember" class="text-sm font-weight-normal text-muted">
                                Recordarme
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                            Entrar <i class="fas fa-sign-in-alt ml-1"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="social-auth-links text-center mb-3 mt-4">
                <hr>
                <p class="text-sm text-muted mb-2">Credenciales de prueba:</p>
                <div class="badge badge-light p-2 border d-block text-left text-xs mb-1">
                    <strong>Admin:</strong> admin@cosmol.com / admin123
                </div>
                <div class="badge badge-light p-2 border d-block text-left text-xs">
                    <strong>Usuario:</strong> usuario@cosmol.com / usuario123
                </div>
            </div>

            <div class="text-center mt-3">
                <p class="mb-1">
                    <a href="<?php echo BASE_URL; ?>/registro" class="text-primary font-weight-bold">
                        <i class="fas fa-user-plus mr-1"></i> ¿No tienes cuenta? Regístrate
                    </a>
                </p>
                <p class="mb-0">
                    <a href="<?php echo BASE_URL; ?>/" class="text-secondary text-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Volver a la portada
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- jQuery & Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
