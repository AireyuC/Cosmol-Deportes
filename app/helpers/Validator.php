<?php
/**
 * Helper de Validación de Datos de Entrada
 * Compatible con PHP 7.3
 */

class Validator {

    /** @var array Lista de errores encontrados */
    private $errors = [];

    /**
     * Valida un conjunto de datos contra reglas
     *
     * @param array $data Datos a validar (ej: $_POST)
     * @param array $rules Reglas en formato ['campo' => 'required|email|min:6']
     * @return bool
     */
    public function validate($data, $rules) {
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = isset($data[$field]) ? trim($data[$field]) : '';
            $ruleList = explode('|', $ruleString);

            foreach ($ruleList as $rule) {
                $params = [];
                if (strpos($rule, ':') !== false) {
                    list($ruleName, $paramStr) = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                } else {
                    $ruleName = $rule;
                }

                $ruleName = trim($ruleName);

                switch ($ruleName) {
                    case 'required':
                        if ($value === '' || $value === null) {
                            $this->addError($field, "El campo " . $this->formatFieldName($field) . " es obligatorio.");
                        }
                        break;

                    case 'email':
                        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($field, "El campo " . $this->formatFieldName($field) . " debe ser un correo electrónico válido.");
                        }
                        break;

                    case 'min':
                        $min = isset($params[0]) ? (int)$params[0] : 0;
                        if ($value !== '' && mb_strlen($value, 'UTF-8') < $min) {
                            $this->addError($field, "El campo " . $this->formatFieldName($field) . " debe tener al menos {$min} caracteres.");
                        }
                        break;

                    case 'max':
                        $max = isset($params[0]) ? (int)$params[0] : 255;
                        if ($value !== '' && mb_strlen($value, 'UTF-8') > $max) {
                            $this->addError($field, "El campo " . $this->formatFieldName($field) . " no debe superar los {$max} caracteres.");
                        }
                        break;

                    case 'numeric':
                        if ($value !== '' && !is_numeric($value)) {
                            $this->addError($field, "El campo " . $this->formatFieldName($field) . " debe ser un número.");
                        }
                        break;

                    case 'alpha_dash':
                        if ($value !== '' && !preg_match('/^[a-zA-Z0-9_.-]+$/', $value)) {
                            $this->addError($field, "El campo " . $this->formatFieldName($field) . " solo puede contener letras, números, puntos, guiones y guiones bajos.");
                        }
                        break;

                    case 'matches':
                        $targetField = isset($params[0]) ? $params[0] : '';
                        $targetValue = isset($data[$targetField]) ? trim($data[$targetField]) : '';
                        if ($value !== $targetValue) {
                            $this->addError($field, "El campo " . $this->formatFieldName($field) . " no coincide con " . $this->formatFieldName($targetField) . ".");
                        }
                        break;
                }
            }
        }

        return count($this->errors) === 0;
    }

    /**
     * Agrega un error a la colección
     *
     * @param string $field
     * @param string $message
     */
    private function addError($field, $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    /**
     * Formatea el nombre del campo para mensajes amigables
     *
     * @param string $field
     * @return string
     */
    private function formatFieldName($field) {
        $labels = [
            'nombre'                => 'Nombre',
            'username'              => 'Nombre de Usuario',
            'email'                 => 'Correo Electrónico',
            'password'              => 'Contraseña',
            'password_confirmation' => 'Confirmación de Contraseña',
            'documento'             => 'Documento de Identidad',
            'dorsal'                => 'Número de Camiseta',
            'equipo'                => 'Equipo'
        ];

        return isset($labels[$field]) ? $labels[$field] : str_replace('_', ' ', ucfirst($field));
    }

    /**
     * Verifica si existen errores
     *
     * @return bool
     */
    public function hasErrors() {
        return count($this->errors) > 0;
    }

    /**
     * Retorna todos los errores
     *
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Retorna el primer error de un campo o el primer error global
     *
     * @param string|null $field
     * @return string|null
     */
    public function getFirstError($field = null) {
        if ($field !== null) {
            return isset($this->errors[$field][0]) ? $this->errors[$field][0] : null;
        }

        foreach ($this->errors as $fieldErrors) {
            if (!empty($fieldErrors)) {
                return $fieldErrors[0];
            }
        }

        return null;
    }
}
