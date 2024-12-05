<?php

namespace Forge\Forms;

class FormField
{
    protected array $fields = [];

    /**
     * Ajouter un champ personnalisé
     */
    public function addField(
        string $name,
        string $type,
        string $label,
        array $rules = [],
        $value = null,
        $options = [],
        array $styles = []
    ) {
        $this->fields[$name] = [
            'type' => $type,
            'label' => $label,
            'name' => $name,
            'rules' => $rules,
            'value' => $value,
            'options' => $options,
            'styles' => $styles, // Nouvel ajout : support des styles CSS
        ];
        return $this;
    }

    /**
     * Champs texte
     */
    public function text(string $name, string $label, array $rules = [], $value = null, array $styles = [])
    {
        return $this->addField($name, 'text', $label, $rules, $value, [], $styles);
    }

    /**
     * Champs email
     */
    public function email(string $name, string $label, array $rules = [], $value = null, array $styles = [])
    {
        return $this->addField($name, 'email', $label, $rules, $value, [], $styles);
    }

    /**
     * Champs textarea
     */
    public function textarea(string $name, string $label, array $rules = [], $value = null, array $styles = [])
    {
        return $this->addField($name, 'textarea', $label, $rules, $value, [], $styles);
    }

    /**
     * Champs select (avec options)
     */
    public function select(string $name, string $label, array $options, array $rules = [], $value = null, array $styles = [])
    {
        return $this->addField($name, 'select', $label, $rules, $value, $options, $styles);
    }

    /**
     * Champs checkbox
     */
    public function checkbox(string $name, string $label, array $rules = [], $value = null, array $styles = [])
    {
        return $this->addField($name, 'checkbox', $label, $rules, $value, [], $styles);
    }

    /**
     * Champs datetime
     */
    public function datetime(string $name, string $label, array $rules = [], $value = null, array $styles = [])
    {
        return $this->addField($name, 'datetime-local', $label, $rules, $value, [], $styles);
    }

    /**
     * Champs number
     */
    public function number(string $name, string $label, array $rules = [], $value = null, array $styles = [])
    {
        return $this->addField($name, 'number', $label, $rules, $value, [], $styles);
    }

    /**
     * Champs date
     */
    public function date(string $name, string $label, array $rules = [], $value = null, array $styles = [])
    {
        return $this->addField($name, 'date', $label, $rules, $value, [], $styles);
    }

    /**
     * Champs file upload
     */
    public function file(string $name, string $label, array $rules = [], array $styles = [])
    {
        return $this->addField($name, 'file', $label, $rules, null, [], $styles);
    }

    /**
     * Obtenir tous les champs
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
