<?php

namespace App\Core\Validations;

class ActiveStatus extends BaseRule implements Contracts\RuleInterface
{

    public function validate(string $key, $value): bool
    {
        return $value == 1;
    }

    public function message(string $key): string
    {
        return "The $key is not active!";
    }
}