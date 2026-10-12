<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Usuario | <?php echo APP_NAME; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style: AdminLTE 3.2.0 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page bg-light">
<div class="register-box">
    <!-- Logo -->
    <div class="register-logo mb-3">
        <a href="<?php echo BASE_URL; ?>/" class="font-weight-bold text-dark">
            <i class="fas fa-trophy text-warning mr-1"></i>
            COSMOL <span class="text-success">DEPORTES</span>
        </a>
        <div class="text-muted" style="font-size: 0.9rem;">Registro de Nuevo Usuario</div>
    </div>

    <div class="card card-outline card-success shadow-sm">
        <div class="card-body register-card-body p-4">
            <p class="login-box-msg font-weight-bold text-secondary">
                Crear una cuenta para seguir la Olimpiada
            </p>

            <!-- Alertas Flash -->
            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-danger py-2 px-3 text-sm">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <?php echo e(Session::getFlash('error')); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/registro" method="post">
                <?php echo CsrfHelper::field(); ?>

                <!-- Nombre Completo -->
                <div class="input-group mb-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre completo (ej. Juan Pérez)" required autofocus value="<?php echo isset($old['nombre']) ? e($old['nombre']) : ''; ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user text-muted"></span>
                        </div>
                    </div>
                </div>

                <!-- Nombre de Usuario (Username) -->
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Nombre de usuario (ej. jperez, carlos99)" required pattern="[a-zA-Z0-9_.-]+" title="Solo letras, números, puntos y guiones" value="<?php echo isset($old['username']) ? e($old['username']) : ''; ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-id-badge text-muted"></span>
                        </div>
                    </div>
                </div>

                <!-- Correo Electrónico (Opcional de contacto) -->
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico (opcional)" value="<?php echo isset($old['email']) ? e($old['email']) : ''; ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope text-muted"></span>
                        </div>
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña (mínimo 6 caracteres)" required minlength="6">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock text-muted"></span>
                        </div>
                    </div>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar contraseña" required minlength="6">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock text-muted"></span>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success btn-block font-weight-bold">
                            Registrarme <i class="fas fa-check-circle ml-1"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="text-center mt-3">
                <p class="mb-1">
                    <a href="<?php echo BASE_URL; ?>/login" class="text-primary font-weight-bold">
                        <i class="fas fa-sign-in-alt mr-1"></i> Ya tengo una cuenta, iniciar sesión
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
