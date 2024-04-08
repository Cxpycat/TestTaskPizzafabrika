<?php

namespace App\Kernel\Validator;

class Validator implements ValidatorInterface
{

    private array $errors = [];

    private array $data;

    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];
        $this->data = $data;

        foreach ($rules as $key => $rule) {
            $rules = $rule;

            foreach ($rules as $rule) {
                $rule = explode(':', $rule);

                $ruleName = $rule[0];
                $ruleValue = $rule[1] ?? NULL;

                $error = $this->validateRule($key, $ruleName, $ruleValue);

                if ($error) {
                    $this->errors[$key][] = $error;
                }
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    private function validateRule(string $key, string $ruleName, string $ruleValue = NULL): string|false
    {
        $value = $this->data[$key];
        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    return "Field $key is required";
                }
                break;
            case 'array':
                if (!is_array($value)) {
                    return "Field $key must be an array";
                }
                break;
            case 'string':
                if (!is_string($value)) {
                    return "Field $key must be a string";
                }
                break;
            case 'integer':
                if (!is_int($value)) {
                    return "Field $key must be an integer";
                }
                break;
        }

        return false;
    }

}