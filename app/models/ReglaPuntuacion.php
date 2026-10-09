<?php
/**
 * Modelo de Reglas de Puntuación por Disciplina
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class ReglaPuntuacion extends Model {

    /**
     * Obtiene la regla de puntuación asociada a una disciplina
     *
     * @param int $disciplinaId
     * @return array|null
     */
    public function getByDisciplinaId($disciplinaId) {
        $sql = "SELECT * FROM reglas_puntuacion WHERE disciplina_id = :disciplina_id LIMIT 1";
        return $this->fetch($sql, ['disciplina_id' => (int)$disciplinaId]);
    }

    /**
     * Registra o actualiza la regla de puntuación para una disciplina
     *
     * @param int $disciplinaId
     * @param array $data
     * @return int
     */
    public function saveForDisciplina($disciplinaId, $data) {
        $existing = $this->getByDisciplinaId($disciplinaId);

        $ruleData = [
            'pts_victoria'         => (int)$data['pts_victoria'],
            'pts_empate'           => (int)$data['pts_empate'],
            'pts_derrota'          => (int)$data['pts_derrota'],
            'pts_derrota_ajustada' => isset($data['pts_derrota_ajustada']) ? (int)$data['pts_derrota_ajustada'] : 0,
            'permite_empate'       => !empty($data['permite_empate']) ? 1 : 0,
            'formato_sets'         => !empty($data['formato_sets']) ? 1 : 0,
            'sets_para_ganar'      => isset($data['sets_para_ganar']) ? (int)$data['sets_para_ganar'] : 0
        ];

        if ($existing) {
            return $this->update('reglas_puntuacion', $ruleData, 'disciplina_id = :where_id', ['where_id' => (int)$disciplinaId]);
        } else {
            $ruleData['disciplina_id'] = (int)$disciplinaId;
            return $this->insert('reglas_puntuacion', $ruleData);
        }
    }
}
