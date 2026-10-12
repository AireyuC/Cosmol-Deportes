<?php
/**
 * Layout Principal de AdminLTE 3
 *
 * @var string $content Contenido HTML inyectado dinámicamente desde el controlador
 * @var string|null $pageTitle Título de la página actual
 */
$content = isset($content) ? $content : '';

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/navbar.php';
require_once __DIR__ . '/sidebar.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Mensajes Flash de Sesión -->
    <div class="container-fluid pt-3 px-3">
        <?php if (Session::hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo e(Session::getFlash('success')); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo e(Session::getFlash('error')); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <?php echo e(Session::getFlash('warning')); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Contenido Principal Inyectado por la Vista -->
    <?php echo isset($content) ? $content : ''; ?>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>
