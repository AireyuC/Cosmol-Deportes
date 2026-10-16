<?php
/**
 * Footer Informativo Público
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Compatible con PHP 7.3
 */
?>
<!-- FOOTER INFORMATIVO PÚBLICO -->
<footer class="footer-public">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <img src="<?php echo BASE_URL; ?>/images/logoSinFondo.png" alt="COSMOL R.L." style="height: 40px;" class="mr-2">
                    <div>
                        <h5 class="text-white font-weight-bold mb-0">COSMOL DEPORTES</h5>
                        <small style="color: #94A3B8; font-size: 0.78rem;">Cooperativa de Servicios Públicos COSMOL R.L.</small>
                    </div>
                </div>
                <p class="text-sm pr-lg-4" style="color: #94A3B8; line-height: 1.6;">
                    Plataforma informativa oficial de seguimiento, programación y resultados para las Olimpiadas Deportivas y Recreativas Institucionales e Inter-Cooperativas.
                </p>
            </div>
            <div class="col-lg-3 col-sm-6 mb-4 mb-lg-0">
                <h6 class="text-white font-weight-bold mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Secciones del Portal</h6>
                <ul class="list-unstyled text-sm mb-0">
                    <li class="mb-2"><a href="#fixture" class="footer-link">Partidos de Hoy</a></li>
                    <li class="mb-2"><a href="#disciplinas" class="footer-link">Disciplinas Deportivas</a></li>
                    <li class="mb-2"><a href="#posiciones" class="footer-link">Tabla de Posiciones</a></li>
                    <li class="mb-2"><a href="#sedes" class="footer-link">Sedes y Rutas GPS</a></li>
                    <li class="mb-2"><a href="#delegaciones" class="footer-link">Delegaciones</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-sm-6">
                <h6 class="text-white font-weight-bold mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">Comité Organizador</h6>
                <p class="text-sm mb-3" style="color: #94A3B8; line-height: 1.5;">
                    Acceso exclusivo para directivos, administradores y mesa técnica de control del campeonato.
                </p>
                <?php if ($isAdminLogged): ?>
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-block font-weight-bold" style="background-color: #0066B2; color: #FFFFFF; border-radius: 8px; font-size: 0.88rem; padding: 0.55rem 1rem;">
                        Ir al Panel de Control
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/login" class="btn btn-block font-weight-bold" style="background-color: #0066B2; color: #FFFFFF; border-radius: 8px; font-size: 0.88rem; padding: 0.55rem 1rem;">
                        Iniciar Sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="pt-3 mt-4 d-flex flex-column flex-sm-row justify-content-between align-items-center" style="border-top: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.8rem; color: #64748B;">
            <div class="mb-2 mb-sm-0 text-center text-sm-left">
                &copy; <?php echo date('Y'); ?> Cooperativa de Servicios Públicos COSMOL R.L. Todos los derechos reservados.
            </div>
        </div>
    </div>
</footer>
