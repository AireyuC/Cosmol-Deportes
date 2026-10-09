<?php
/**
 * Modelo de Disciplinas Deportivas
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/ReglaPuntuacion.php';

class Disciplina extends Model {

    /**
     * Obtiene todas las disciplinas junto con sus reglas de puntuación
     *
     * @return array
     */
    public function getAll() {
        $sql = "SELECT d.*, 
                       r.pts_victoria, r.pts_empate, r.pts_derrota, r.pts_derrota_ajustada, 
                       r.permite_empate, r.formato_sets, r.sets_para_ganar
                FROM disciplinas d
                LEFT JOIN reglas_puntuacion r ON d.id = r.disciplina_id
                ORDER BY d.nombre ASC";
        return $this->fetchAll($sql);
    }

    /**
     * Obtiene una disciplina por su ID con sus reglas de puntuación
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT d.*, 
                       r.pts_victoria, r.pts_empate, r.pts_derrota, r.pts_derrota_ajustada, 
                       r.permite_empate, r.formato_sets, r.sets_para_ganar
                FROM disciplinas d
                LEFT JOIN reglas_puntuacion r ON d.id = r.disciplina_id
                WHERE d.id = :id
                LIMIT 1";
        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Registra una nueva disciplina y su regla de puntuación
     *
     * @param array $data
     * @return int ID de la disciplina creada
     */
    public function create($data) {
        $this->beginTransaction();

        try {
            $disciplinaData = [
                'nombre'      => trim($data['nombre']),
                'descripcion' => isset($data['descripcion']) ? trim($data['descripcion']) : null,
                'icono'       => !empty($data['icono']) ? trim($data['icono']) : 'trophy',
                'estado'      => isset($data['estado']) ? $data['estado'] : 'Activa'
            ];

            $disciplinaId = $this->insert('disciplinas', $disciplinaData);

            // Guardar reglas de puntuación asociadas
            $reglaModel = new ReglaPuntuacion();
            $reglaModel->saveForDisciplina($disciplinaId, $data);

            $this->commit();
            return $disciplinaId;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * Actualiza una disciplina y sus reglas de puntuación
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateDisciplina($id, $data) {
        $this->beginTransaction();

        try {
            $disciplinaData = [
                'nombre'      => trim($data['nombre']),
                'descripcion' => isset($data['descripcion']) ? trim($data['descripcion']) : null,
                'icono'       => !empty($data['icono']) ? trim($data['icono']) : 'trophy',
                'estado'      => isset($data['estado']) ? $data['estado'] : 'Activa'
            ];

            $this->update('disciplinas', $disciplinaData, 'id = :where_id', ['where_id' => (int)$id]);

            // Guardar reglas de puntuación asociadas
            $reglaModel = new ReglaPuntuacion();
            $reglaModel->saveForDisciplina($id, $data);

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * Elimina una disciplina
     *
     * @param int $id
     * @return int
     */
    public function deleteDisciplina($id) {
        return $this->delete('disciplinas', 'id = :id', ['id' => (int)$id]);
    }
}
