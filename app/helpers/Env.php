<?php
/**
 * Cargador de Variables de Entorno (.env)
 * Compatible con PHP 7.3
 */

class Env {

    /** @var bool Indica si ya fue cargado el archivo */
    private static $loaded = false;

    /**
     * Carga el archivo .env si existe
     *
     * @param string|null $path
     */
    public static function load($path = null) {
        if (self::$loaded) {
            return;
        }

        if ($path === null) {
            $path = ROOT_PATH . DIRECTORY_SEPARATOR . '.env';
        }

        if (!file_exists($path)) {
            self::$loaded = true;
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            self::$loaded = true;
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Ignorar comentarios
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            // Separar clave y valor
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remover comillas si existen
                if (strlen($value) >= 2) {
                    $firstChar = substr($value, 0, 1);
                    $lastChar = substr($value, -1);
                    if (($firstChar === '"' && $lastChar === '"') || ($firstChar === "'" && $lastChar === "'")) {
                        $value = substr($value, 1, -1);
                    }
                }

                // Convertir booleanos y null
                $lower = strtolower($value);
                if ($lower === 'true') {
                    $value = true;
                } elseif ($lower === 'false') {
                    $value = false;
                } elseif ($lower === 'null') {
                    $value = null;
                }

                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
                putenv("{$key}={$value}");
            }
        }

        self::$loaded = true;
    }

    /**
     * Obtiene el valor de una variable de entorno
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null) {
        self::load();

        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        $val = getenv($key);
        if ($val !== false) {
            return $val;
        }

        return $default;
    }
}

/**
 * Función helper global env()
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function env($key, $default = null) {
    return Env::get($key, $default);
}
