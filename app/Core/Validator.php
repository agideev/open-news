<?php
namespace App\Core;

class Validator
{
    protected $data;
    protected $errors = [];
    protected $labels = [];

    protected $defaultMessages = [
        'required' => 'O campo {field} é obrigatório.',
        'numeric'  => 'O campo {field} deve ser um número válido.',
        'min'      => 'O campo {field} deve ser no mínimo {param}.',
        'max'      => 'O campo {field} não pode ser maior que {param}.',
        'email'    => 'Por favor, informe um e-mail válido.',
        'match'    => 'O campo {field} não confere com o campo {param}.'
    ];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function setLabels(array $labels)
    {
        $this->labels = $labels;
        return $this;
    }

    /**
     * Valida os dados com base nas regras fornecidas.
     *
     * @param array $rules Regras de validação
     * @param array $labels Nomes amigáveis dos campos (ex: 'name' => 'Nome')
     * @param array $customMessages Mensagens de erro personalizadas
     */
    public function validate(array $rules, array $labels = [], array $customMessages = [])
    {
        if (!empty($labels)) {
            $this->labels = array_merge($this->labels, $labels);
        }

        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            $isNumeric = in_array('numeric', $rulesArray, true);

            foreach ($rulesArray as $rule) {
                $param = null;

                if (strpos($rule, ':') !== false) {
                    list($ruleName, $param) = explode(':', $rule);
                } else {
                    $ruleName = $rule;
                }

                if ($ruleName !== 'required' && ($value === null || $value === '')) {
                    continue;
                }

                $isValid = $this->checkRule($ruleName, $value, $param, $isNumeric);

                if (!$isValid) {
                    $this->addError($field, $ruleName, $param, $customMessages, $isNumeric);
                    break;
                }
            }
        }

        return empty($this->errors);
    }

    protected function checkRule($rule, $value, $param, $isNumeric = false)
    {
        $stringVal = (string)($value ?? '');

        switch ($rule) {
            case 'required':
                return $value !== null && trim($stringVal) !== '';
            case 'numeric':
                return is_numeric($value);
            case 'min':
                if ($isNumeric && is_numeric($value)) {
                    return (float)$value >= (float)$param;
                }
                return mb_strlen($stringVal, 'UTF-8') >= (int)$param;
            case 'max':
                if ($isNumeric && is_numeric($value)) {
                    return (float)$value <= (float)$param;
                }
                return mb_strlen($stringVal, 'UTF-8') <= (int)$param;
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            case 'match':
                $matchValue = $this->data[$param] ?? null;
                return $value === $matchValue;
            default:
                return true;
        }
    }

    protected function getLabel($field)
    {
        if (isset($this->labels[$field])) {
            return $this->labels[$field];
        }
        return ucfirst(str_replace('_', ' ', $field));
    }

    protected function addError($field, $rule, $param, $customMessages, $isNumeric = false)
    {
        $messageKey = "{$field}.{$rule}";
        $message = $customMessages[$messageKey] ?? $this->defaultMessages[$rule] ?? 'Campo inválido.';

        if (!$isNumeric && in_array($rule, ['min', 'max'])) {
            $message = str_replace(
                ['deve ser no mínimo', 'não pode ser maior que'],
                ['deve ter pelo menos', 'não deve exceder'],
                $message
            ) . ' caracteres.';
        }

        $fieldLabel = $this->getLabel($field);
        $paramLabel = ($rule === 'match' && $param) ? $this->getLabel($param) : $param;

        $message = str_replace(['{field}', '{param}'], [$fieldLabel, $paramLabel], $message);

        $this->errors[$field] = $message;
    }

    public function fails()
    {
        return !empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getFirstError()
    {
        return empty($this->errors) ? null : reset($this->errors);
    }
}
