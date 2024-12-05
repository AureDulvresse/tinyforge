<?php

namespace Forge\Forms;

abstract class Form
{
    protected FormField $formField;
    protected array $fields = [];
    private array $errors = [];

    public function __construct()
    {
        $this->formField = new FormField();
    }

    abstract public function definition(FormField $field): array;

    public function build(array $override = []): array
    {
        $this->fields = array_merge($this->definition($this->formField), $override);
        return $this->render();
    }

    public function validate(array $data): bool
    {
        foreach ($this->fields as $name => $field) {
            $rules = $field['rules'];
            $value = $data[$name] ?? null;

            foreach ($rules as $rule) {
                if (!$this->applyRule($rule, $value)) {
                    $this->errors[$name][] = "Le champ '$name' ne respecte pas la règle '$rule'";
                }
            }
        }

        return empty($this->errors);
    }

    private function applyRule(string $rule, $value): bool
    {
        switch ($rule) {
            case 'required':
                return !empty($value);
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            case 'min:4':
                return strlen($value) >= 4;
            default:
                return true;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function render(): array
    {
        $fieldsHtml = [];
        foreach ($this->fields as $name => $field) {
            $fieldsHtml[$name] = [
                'label' => $field['label'],
                'name' => $name,
                'type' => $field['type'],
                'value' => $field['value'] ?? '',
                'options' => $field['options'] ?? [],
                'styles' => $field['styles'] ?? [],
            ];
        }
        return $fieldsHtml;
    }
}
