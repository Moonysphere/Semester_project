<?php

namespace App\Forms;

use App\Lib\Entities\AbstractEntity;

abstract class AbstractForm
{
    protected array $data = [];
    protected array $errors = [];
    protected array $fieldTypes = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function isValidJson(string $jsonString): bool
    {
        json_decode($jsonString);
        return json_last_error() === JSON_ERROR_NONE;
    }


    public function parseJsonToArray(string $jsonString): ?array
    {
        if (!$this->isValidJson($jsonString)) {
            $this->errors[] = 'Invalid JSON format';
            return null;
        }
        return json_decode($jsonString, true);
    }

    public function parseUrlEncodedToArray(string $urlEncoded): array
    {
        parse_str($urlEncoded, $data);
        return $data;
    }


    public function parseStringToArray(string $data): ?array
    {
        $parsed = $this->parseJsonToArray($data);
        if ($parsed !== null) {
            return $parsed;
        }

        $this->errors = [];
        return $this->parseUrlEncodedToArray($data);
    }

    public function validateType(string $field, $value, string $expectedType): bool
    {
        if ($value === null) {
            return true;
        }

        switch ($expectedType) {
            case 'string':
                return is_string($value);
            case 'int':
                return is_int($value) || (is_string($value) && ctype_digit($value));
            case 'float':
                return is_float($value) || is_numeric($value);
            case 'bool':
                return is_bool($value);
            case 'array':
                return is_array($value);
            default:
                return true;
        }
    }

    public function validateAllFields(): bool
    {
        $this->errors = [];

        foreach ($this->fieldTypes as $field => $type) {
            if (!isset($this->data[$field]) && $type !== 'optional') {
                $this->errors[] = "Field '$field' is required";
                continue;
            }

            if (isset($this->data[$field]) && !$this->validateType($field, $this->data[$field], $type)) {
                $this->errors[] = "Field '$field' must be of type '$type'";
            }
        }

        return empty($this->errors);
    }


    public function toJson(): string
    {
        return json_encode($this->data);
    }


    public function getErrors(): array
    {
        return $this->errors;
    }


    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }


    public function getData(): array
    {
        return $this->data;
    }
}
