<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Usuario | <?php echo APP_NAME; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
    <!-- Theme style: AdminLTE 3.2.0 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/image.png">

    <style>
        :root {
            --cosmol-azul: #0066B2;
            --cosmol-celeste: #00A3E0;
            --cosmol-fondo: #F0F6FA;
            --cosmol-blanco: #FFFFFF;
        }
        body.register-page {
            background-color: var(--cosmol-fondo);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px 0;
        }
        .register-box {
            width: 460px;
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
        .logo-register {
            max-width: 130px;
            height: auto;
            display: block;
            margin: 0 auto 10px auto;
            filter: drop-shadow(0 2px 4px rgba(0, 102, 178, 0.15));
        }
    </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">

    <div class="card card-cosmol bg-white">
        <div class="card-body register-card-body p-4">

            <!-- Logo Institucional -->
            <div class="text-center mb-3">
                <a href="<?php echo BASE_URL; ?>/">
                    <img src="<?php echo BASE_URL; ?>/images/image.png" alt="COSMOL R.L." class="logo-register">
                </a>
                <h5 class="font-weight-bold mb-0" style="color: #0066B2 !important;">
                    COSMOL R.L.
                </h5>
                <span class="text-muted text-sm font-weight-normal">
                    Crear Nueva Cuenta de Usuario
                </span>
            </div>

            <!-- Alertas Flash -->
            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-danger py-2 px-3 text-sm mb-3">
                    <?php echo e(Session::getFlash('error')); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/registro" method="post">
                <?php echo CsrfHelper::field(); ?>

                <!-- Nombre Completo -->
                <div class="form-group mb-2">
                    <label for="nombre" class="text-sm font-weight-normal text-secondary mb-1">Nombre y Apellidos</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej: Juan Carlos Pérez" required autofocus value="<?php echo isset($old['nombre']) ? e($old['nombre']) : ''; ?>">
                </div>

                <!-- Nombre de Usuario -->
                <div class="form-group mb-2">
                    <label for="username" class="text-sm font-weight-normal text-secondary mb-1">Nombre de usuario</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Ej: jperez, carlos99" required pattern="[a-zA-Z0-9_.-]+" title="Solo letras, números, puntos y guiones" value="<?php echo isset($old['username']) ? e($old['username']) : ''; ?>">
                    <small class="text-muted text-xs">Identificador único para iniciar sesión.</small>
                </div>

                <!-- Correo Electrónico -->
                <div class="form-group mb-2">
                    <label for="email" class="text-sm font-weight-normal text-secondary mb-1">Correo electrónico <span class="text-muted text-xs">(opcional)</span></label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="correo@ejemplo.com" value="<?php echo isset($old['email']) ? e($old['email']) : ''; ?>">
                </div>

                <!-- Contraseña -->
                <div class="row">
                    <div class="col-md-6 form-group mb-2">
                        <label for="password" class="text-sm font-weight-normal text-secondary mb-1">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required minlength="6">
                    </div>
                    <div class="col-md-6 form-group mb-2">
                        <label for="password_confirmation" class="text-sm font-weight-normal text-secondary mb-1">Confirmar</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repita la contraseña" required minlength="6">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-cosmol btn-block">
                        Registrarme
                    </button>
                </div>
            </form>

            <div class="text-center mt-3 pt-2">
                <p class="mb-1 text-sm">
                    <a href="<?php echo BASE_URL; ?>/login" class="link-cosmol">
                        ¿Ya tiene una cuenta? Iniciar sesión
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
