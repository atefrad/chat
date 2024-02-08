<?php

namespace App\Core\Validations;

use App\Core\Validations\Contracts\RuleInterface;

class Min extends BaseRule implements RuleInterface
{
    private int $min;
    public function __construct(int $min)
    {
        $this->min = $min;
    }

    public function validate(string $key, $value): bool
    {
        return strlen($value) >= $this->min;
    }

    public function message(string $key): string
    {
        return "The {$key} attribute must have at least {$this->min} characters!";
    }
}