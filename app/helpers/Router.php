<?php
/**
 * Enrutador Ligero y Dinámico para el patrón MVC
 * Compatible con PHP 7.3
 */

class Router {

    /** @var array Rutas registradas */
    private static $routes = [];

    /**
     * Registra una ruta GET
     *
     * @param string $uri
     * @param mixed $action
     */
    public static function get($uri, $action) {
        self::addRoute('GET', $uri, $action);
    }

    /**
     * Registra una ruta POST
     *
     * @param string $uri
     * @param mixed $action
     */
    public static function post($uri, $action) {
        self::addRoute('POST', $uri, $action);
    }

    /**
     * Almacena internamente la ruta
     *
     * @param string $method
     * @param string $uri
     * @param mixed $action
     */
    private static function addRoute($method, $uri, $action) {
        $uri = trim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        } else {
            $uri = '/' . $uri;
        }

        self::$routes[] = [
            'method' => strtoupper($method),
            'uri'    => $uri,
            'action' => $action
        ];
    }

    /**
     * Obtiene la URI limpia de la solicitud actual
     *
     * @return string
     */
    public static function currentUri() {
        if (isset($_GET['url'])) {
            $uri = '/' . trim($_GET['url'], '/');
        } else {
            $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
            // Remover query string
            if (($pos = strpos($uri, '?')) !== false) {
                $uri = substr($uri, 0, $pos);
            }
            
            // Si el proyecto corre en subdirectorio, limpiar el prefijo
            $scriptName = dirname($_SERVER['SCRIPT_NAME']);
            $scriptName = str_replace('\\', '/', $scriptName);
            if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
                $uri = substr($uri, strlen($scriptName));
            }
            $uri = '/' . trim($uri, '/');
        }

        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }

        return $uri === '' ? '/' : $uri;
    }

    /**
     * Procesa la solicitud HTTP y despacha al controlador correspondiente
     */
    public static function dispatch() {
        $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
        
        // Obtener la URI solicitada
        $requestUri = self::currentUri();

        foreach (self::$routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            // Convertir /ruta/{param} a expresión regular
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['uri']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Eliminar la coincidencia completa

                return self::executeAction($route['action'], $matches);
            }
        }

        // Si no se encuentra la ruta: error 404
        self::sendNotFound();
    }

    /**
     * Ejecuta la acción asociada a la ruta (Controlador@metodo o Función anónima)
     *
     * @param mixed $action
     * @param array $params
     */
    private static function executeAction($action, $params = []) {
        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }

        if (is_string($action)) {
            $parts = explode('@', $action);
            if (count($parts) === 2) {
                $controllerName = $parts[0];
                $methodName = $parts[1];

                $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        if (method_exists($controller, $methodName)) {
                            return call_user_func_array([$controller, $methodName], $params);
                        }
                    }
                }
            }
        }

        self::sendNotFound("Acción o controlador no encontrado.");
    }

    /**
     * Renderiza respuesta de página no encontrada
     *
     * @param string $message
     */
    private static function sendNotFound($message = "Página no encontrada") {
        http_response_code(404);
        $errorFile = APP_PATH . '/views/errors/404.php';
        if (file_exists($errorFile)) {
            require $errorFile;
        } else {
            echo "<!DOCTYPE html><html><head><title>404 Not Found</title></head>";
            echo "<body style='font-family: Arial, sans-serif; text-align: center; padding: 50px;'>";
            echo "<h1>404 - No Encontrado</h1>";
            echo "<p>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<a href='" . BASE_URL . "'>Volver al Inicio</a>";
            echo "</body></html>";
        }
        exit;
    }
}
