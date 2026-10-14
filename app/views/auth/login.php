<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión | <?php echo APP_NAME; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
    <!-- Theme style: AdminLTE 3.2.0 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/logoSinFondo.png">
    
    <style>
        :root {
            --cosmol-azul: #0066B2;
            --cosmol-celeste: #00A3E0;
            --cosmol-fondo: #F0F6FA;
            --cosmol-blanco: #FFFFFF;
        }
        body.login-page {
            background-color: var(--cosmol-fondo);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-box {
            width: 390px;
        }
        .card-cosmol {
            border-top: 4px solid var(--cosmol-azul);
            border-radius: 8px;
            box-shadow: 0 4px 18px rgba(0, 102, 178, 0.12);
        }
        .btn-cosmol {
            background-color: var(--cosmol-azul);
            border-color: var(--cosmol-azul);
            color: #FFFFFF;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-cosmol:hover, .btn-cosmol:focus {
            background-color: #004d87;
            border-color: #004d87;
            color: #FFFFFF;
        }
        .link-cosmol {
            color: var(--cosmol-azul);
            font-weight: 600;
        }
        .link-cosmol:hover {
            color: var(--cosmol-celeste);
            text-decoration: underline;
        }
        .logo-login {
            max-width: 140px;
            height: auto;
            display: block;
            margin: 0 auto 12px auto;
            filter: drop-shadow(0 2px 4px rgba(0, 102, 178, 0.15));
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">

    <!-- Card Principal de Login -->
    <div class="card card-cosmol bg-white">
        <div class="card-body login-card-body p-4">
            
            <!-- Logo Institucional COSMOL -->
            <div class="text-center mb-3">
                <a href="<?php echo BASE_URL; ?>/">
                    <img src="<?php echo BASE_URL; ?>/images/logoSinFondo.png" alt="COSMOL R.L." class="logo-login">
                </a>
                <h5 class="font-weight-bold text-dark mb-0" style="color: #0066B2 !important;">
                    COSMOL R.L.
                </h5>
                <span class="text-muted text-sm font-weight-normal">
                    Olimpiadas Deportivas
                </span>
            </div>

            <p class="login-box-msg text-secondary text-sm px-0 mb-3 font-weight-normal">
                Ingrese su nombre de usuario y contraseña
            </p>

            <!-- Alertas Flash limpias (sin iconos innecesarios) -->
            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-danger py-2 px-3 text-sm mb-3">
                    <?php echo e(Session::getFlash('error')); ?>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('success')): ?>
                <div class="alert alert-success py-2 px-3 text-sm mb-3">
                    <?php echo e(Session::getFlash('success')); ?>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('warning')): ?>
                <div class="alert alert-warning py-2 px-3 text-sm mb-3">
                    <?php echo e(Session::getFlash('warning')); ?>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('info')): ?>
                <div class="alert alert-info py-2 px-3 text-sm mb-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    <?php echo e(Session::getFlash('info')); ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de Acceso -->
            <form action="<?php echo BASE_URL; ?>/login" method="post">
                <?php echo CsrfHelper::field(); ?>

                <div class="form-group mb-3">
                    <label for="username" class="text-sm font-weight-normal text-secondary mb-1">Nombre de usuario</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Ej: admin, usuario" required autofocus value="<?php echo isset($oldUsername) ? e($oldUsername) : ''; ?>">
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="text-sm font-weight-normal text-secondary mb-1">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="row align-items-center mb-3 mt-4">
                    <div class="col-7">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                            <label class="custom-control-label text-sm font-weight-normal text-muted" for="remember">
                                Recordarme
                            </label>
                        </div>
                    </div>
                    <div class="col-5 text-right">
                        <button type="submit" class="btn btn-cosmol btn-block">
                            Ingresar
                        </button>
                    </div>
                </div>
            </form>

            <!-- Credenciales de prueba discretas -->
            <div class="mt-4 pt-3 border-top text-center">
                <span class="text-xs text-muted d-block mb-1">Credenciales disponibles:</span>
                <div class="p-2 rounded" style="background-color: #F7FAFC; border: 1px solid #E2E8F0; font-size: 0.78rem;">
                    <div><strong>Admin:</strong> <code>admin</code> / <code>admin123</code></div>
                    <div><strong>Usuario:</strong> <code>usuario</code> / <code>usuario123</code></div>
                </div>
            </div>

            <!-- Enlaces adicionales -->
            <div class="text-center mt-3 pt-2">
                <p class="mb-1 text-sm">
                    <a href="<?php echo BASE_URL; ?>/registro" class="link-cosmol">
                        ¿No tiene una cuenta? Registrarse
                    </a>
                </p>
                <p class="mb-0 text-xs">
                    <a href="<?php echo BASE_URL; ?>/" class="text-muted">
                        Volver a la portada
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

<!-- Scripts básicos -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
