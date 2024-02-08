<?php

namespace App\Core\Validations;

use App\Core\Validations\Contracts\RuleInterface;
class Max extends BaseRule implements RuleInterface
{
    private int $max;
    public function __construct(int $max)
    {
        $this->max = $max;
    }

    public function validate(string $key, $value): bool
    {
        return strlen($value) <= $this->max;
    }

    public function message(string $key): string
    {
        return "The {$key} attribute must have at least {$this->max} characters!";
    }
}