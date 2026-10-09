<?php
/**
 * Controlador Base para el patrón MVC
 * Compatible con PHP 7.3
 */

abstract class Controller {

    /**
     * Renderiza una vista dentro del layout principal de AdminLTE
     *
     * @param string $view Ruta relativa de la vista dentro de app/views/ (ej. 'admin/dashboard/index')
     * @param array $data Variables que se pasan a la vista
     * @param string $layout Nombre del layout (default: 'main')
     */
    protected function view($view, $data = [], $layout = 'main') {
        // Extraer variables para la vista
        extract($data);

        // Capturar contenido de la vista
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("Error: La vista '{$view}' no existe en " . $viewFile);
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Si se especificó un layout, renderizarlo inyectando $content
        if ($layout) {
            $layoutFile = APP_PATH . '/views/layouts/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
                return;
            }
        }

        // Si no hay layout o no existe, imprimir contenido directo
        echo $content;
    }

    /**
     * Renderiza una vista sin layout (para AJAX o modales)
     *
     * @param string $view
     * @param array $data
     */
    protected function renderPartial($view, $data = []) {
        extract($data);
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        }
    }

    /**
     * Redirecciona a una ruta interna
     *
     * @param string $path
     */
    protected function redirect($path) {
        $url = (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0)
            ? $path
            : BASE_URL . '/' . ltrim($path, '/');
        header('Location: ' . $url);
        exit;
    }

    /**
     * Envía una respuesta JSON
     *
     * @param mixed $data
     * @param int $statusCode
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
