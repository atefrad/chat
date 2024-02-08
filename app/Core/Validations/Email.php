<?php

namespace App\Core\Validations;

use App\Core\Validations\Contracts\RuleInterface;
class Email extends BaseRule implements RuleInterface
{

    public function validate(string $key, $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function message(string $key): string
    {
        return "{$key} is not valid!";
    }
}