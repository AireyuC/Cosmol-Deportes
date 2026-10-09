<?php
/**
 * Gestor de Conexión a la Base de Datos PDO (Singleton)
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/config.php';

class Database {

    /** @var Database|null Instancia única */
    private static $instance = null;

    /** @var PDO Objeto de conexión PDO */
    private $connection;

    /**
     * Constructor privado para prevenir creación directa
     */
    private function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET . " COLLATE utf8mb4_unicode_ci"
        ];

        try {
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Error amigable si la base de datos o MySQL en XAMPP no están listos
            $this->handleConnectionError($e);
        }
    }

    /**
     * Obtiene la instancia única de la clase Database
     *
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtiene el objeto de conexión PDO activo
     *
     * @return PDO
     */
    public static function getConnection() {
        return self::getInstance()->connection;
    }

    /**
     * Verifica el estado de la conexión a la base de datos
     *
     * @return array ['success' => bool, 'message' => string]
     */
    public static function testConnection() {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->query("SELECT 1");
            return [
                'success' => true,
                'message' => 'Conexión a la base de datos exitosa (' . DB_NAME . ' en ' . DB_HOST . ').'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Maneja el error de conexión desplegando un aviso claro
     *
     * @param PDOException $e
     */
    private function handleConnectionError(PDOException $e) {
        $isCli = (php_sapi_name() === 'cli');
        $errorMsg = "No se pudo conectar a la base de datos MySQL de XAMPP (" . DB_NAME . " en " . DB_HOST . "). ";
        $errorMsg .= "Asegúrese de que el módulo MySQL esté iniciado en XAMPP y que el script 'database/schema.sql' haya sido importado.";

        if ($isCli) {
            fwrite(STDERR, "[ERROR PDO] " . $errorMsg . " Detalle: " . $e->getMessage() . PHP_EOL);
            exit(1);
        }

        // Pantalla amigable si ocurre en navegador web
        http_response_code(500);
        echo "<!DOCTYPE html><html lang='es'><head><meta charset='utf-8'><title>Error de Base de Datos</title>";
        echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css'>";
        echo "</head><body class='hold-transition lockscreen bg-light'><div class='container mt-5 pt-5'>";
        echo "<div class='card card-danger card-outline shadow'><div class='card-header'><h3 class='card-title font-weight-bold'>";
        echo "<i class='fas fa-database mr-2'></i> Conexión a Base de Datos Requerida</h3></div>";
        echo "<div class='card-body'><p class='lead'>" . htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<div class='alert alert-warning'><strong>Detalle técnico:</strong> " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
        echo "<p><strong>Pasos para solucionar en XAMPP:</strong></p>";
        echo "<ol><li>Abrir el Panel de Control de XAMPP e iniciar <strong>MySQL</strong>.</li>";
        echo "<li>Abrir <strong>phpMyAdmin</strong> (<a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a>).</li>";
        echo "<li>Importar los archivos <code>database/schema.sql</code> y <code>database/seeds.sql</code>.</li></ol>";
        echo "<a href='javascript:location.reload()' class='btn btn-primary'><i class='fas fa-sync mr-1'></i> Reintentar Conexión</a>";
        echo "</div></div></div></body></html>";
        exit;
    }

    /**
     * Prevenir clonación
     */
    private function __clone() {}

    /**
     * Prevenir deserialización
     */
    public function __wakeup() {
        throw new Exception("No se permite deserializar una instancia Singleton.");
    }
}
