<?php

namespace App\Core\Validations;

use App\Core\Validations\Contracts\RuleInterface;
class Required extends BaseRule implements RuleInterface
{

    public function validate(string $key, $value): bool
    {
        return !empty($value);
    }

    public function message(string $key): string
    {
        return "The {$key} attribute is required!";
    }
}