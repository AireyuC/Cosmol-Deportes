<?php
/**
 * Modelo Base Abstracto con persistencia PDO
 * Compatible con PHP 7.3
 */

require_once APP_PATH . '/config/database.php';

abstract class Model {

    /** @var PDO Objeto de conexión */
    protected $db;

    /**
     * Constructor que obtiene la conexión PDO activa
     */
    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Ejecuta una consulta preparada con parámetros
     *
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Retorna un único registro asociativo
     *
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    protected function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    /**
     * Retorna todos los registros de una consulta
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    protected function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Inserta un registro en una tabla
     *
     * @param string $table Nombre de la tabla
     * @param array $data Pares ['columna' => $valor]
     * @return int ID insertado
     */
    protected function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();
        return (int)$this->db->lastInsertId();
    }

    /**
     * Actualiza registros en una tabla
     *
     * @param string $table
     * @param array $data Pares ['columna' => $valor]
     * @param string $where Condición WHERE (ej. "id = :where_id")
     * @param array $whereParams Parámetros para la condición WHERE
     * @return int Cantidad de filas afectadas
     */
    protected function update($table, $data, $where, $whereParams = []) {
        $setParts = [];
        $params = [];

        foreach ($data as $col => $val) {
            $paramName = 'set_' . $col;
            $setParts[] = "{$col} = :{$paramName}";
            $params[$paramName] = $val;
        }

        $setSql = implode(', ', $setParts);
        $sql = "UPDATE {$table} SET {$setSql} WHERE {$where}";

        // Combinar parámetros SET y WHERE
        $allParams = array_merge($params, $whereParams);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($allParams);

        return $stmt->rowCount();
    }

    /**
     * Elimina registros de una tabla
     *
     * @param string $table
     * @param string $where Condición WHERE (ej. "id = :id")
     * @param array $params Parámetros para el WHERE
     * @return int Filas afectadas
     */
    protected function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Inicia una transacción
     */
    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    /**
     * Confirma una transacción
     */
    public function commit() {
        return $this->db->commit();
    }

    /**
     * Revierte una transacción
     */
    public function rollBack() {
        return $this->db->rollBack();
    }
}
