<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso Administrativo | <?php echo APP_NAME; ?></title>

    <!-- Google Fonts: Outfit & Source Sans Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style: Bootstrap 4.6 & AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/image.png">
    
    <!-- Estilos específicos de la vista de Login -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/login.css">
</head>
<body class="hold-transition login-page">
<div class="login-card-container">

    <!-- Card de Acceso Administrativo -->
    <div class="card-login-admin">
        
        <!-- Encabezado con Identidad Institucional -->
        <div class="login-header">
            <a href="<?php echo BASE_URL; ?>/">
                <img src="<?php echo BASE_URL; ?>/images/image.png" alt="COSMOL R.L." class="logo-login">
            </a>
            <h1 class="login-title mb-1">COSMOL DEPORTES</h1>
            <div>
                <span class="badge-admin-portal">
                    <i class="fas fa-lock mr-1"></i> Acceso Administrativo
                </span>
            </div>
        </div>

        <div class="login-body pt-2">
            <!-- Alertas Flash -->
            <?php if (Session::hasFlash('error')): ?>
                <div class="alert alert-danger py-2 px-3 text-sm mb-3 border-0" style="background-color: #FEE2E2; color: #991B1B; border-radius: 8px;">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <?php echo e(Session::getFlash('error')); ?>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('success')): ?>
                <div class="alert alert-success py-2 px-3 text-sm mb-3 border-0" style="background-color: #DCFCE7; color: #166534; border-radius: 8px;">
                    <i class="fas fa-check-circle mr-1"></i>
                    <?php echo e(Session::getFlash('success')); ?>
                </div>
            <?php endif; ?>

            <?php if (Session::hasFlash('info')): ?>
                <div class="alert alert-info py-2 px-3 text-sm mb-3 border-0" style="background-color: #E0F2FE; color: #075985; border-radius: 8px;">
                    <i class="fas fa-info-circle mr-1"></i>
                    <?php echo e(Session::getFlash('info')); ?>
                </div>
            <?php endif; ?>

            <!-- Formulario Seguro de Acceso -->
            <form action="<?php echo BASE_URL; ?>/login" method="post" id="formLogin" autocomplete="off">
                <?php echo CsrfHelper::field(); ?>

                <!-- Campo: Usuario Administrador -->
                <div class="form-group mb-3">
                    <label for="username" class="text-xs font-weight-bold text-uppercase mb-1" style="color: #475569; letter-spacing: 0.4px;">
                        Usuario
                    </label>
                    <div class="input-group">
                        <input type="text" 
                               id="username" 
                               name="username" 
                               class="form-control form-control-login" 
                               placeholder="Ingrese su usuario" 
                               required 
                               autofocus 
                               autocomplete="username"
                               autocapitalize="none"
                               spellcheck="false"
                               maxlength="50"
                               value="<?php echo isset($oldUsername) ? e($oldUsername) : ''; ?>">
                    </div>
                </div>

                <!-- Campo: Contraseña con Toggle de Visibilidad -->
                <div class="form-group mb-3">
                    <label for="password" class="text-xs font-weight-bold text-uppercase mb-1" style="color: #475569; letter-spacing: 0.4px;">
                        Contraseña
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control form-control-login input-password-field" 
                               placeholder="••••••••" 
                               required 
                               autocomplete="current-password"
                               maxlength="100">
                        <div class="input-group-append">
                            <button type="button" 
                                    class="input-group-text input-group-text-btn" 
                                    id="btnTogglePassword" 
                                    tabindex="-1"
                                    title="Mostrar u ocultar contraseña">
                                <i class="fas fa-eye" id="iconTogglePassword"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Botón Principal de Acceso -->
                <div class="mt-4 mb-2">
                    <button type="submit" class="btn btn-login-submit btn-block shadow-none" id="btnSubmit">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <!-- Enlace Volver a Portada -->
            <div class="text-center mt-3 pt-2">
                <a href="<?php echo BASE_URL; ?>/" class="link-portal">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al Portal Informativo
                </a>
            </div>

        </div>
    </div>
</div>

<!-- Scripts básicos -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Toggle de visibilidad de contraseña
        $('#btnTogglePassword').on('click', function(e) {
            e.preventDefault();
            var input = $('#password');
            var icon = $('#iconTogglePassword');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Prevención de envíos múltiples simultáneos
        $('#formLogin').on('submit', function() {
            var btn = $('#btnSubmit');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Verificando credenciales...');
        });
    });
</script>

</body>
</html>
