/**
 * Scripts personalizados para Cosmol-Deportes
 * AdminLTE 3 + jQuery
 */

$(document).ready(function() {
    // Auto-ocultar alertas flash después de 5 segundos
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);

    // Resaltar elemento activo del menú lateral según URL
    var currentUrl = window.location.pathname;
    $('.nav-sidebar .nav-link').each(function() {
        var linkHref = $(this).attr('href');
        if (linkHref && currentUrl.indexOf(linkHref) !== -1 && linkHref !== '/' && linkHref.indexOf('#') === -1) {
            $('.nav-sidebar .nav-link').removeClass('active');
            $(this).addClass('active');
            $(this).parents('.nav-item').addClass('menu-open');
        }
    });
});
