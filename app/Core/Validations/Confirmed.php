<?php

namespace App\Core\Validations;

use App\Core\Validations\Contracts\RuleInterface;
class Confirmed extends BaseRule implements RuleInterface
{

    public function validate(string $key, $value): bool
    {
        $confirmationField = substr($key, 0, strpos($key, '_confirmation'));

        return $value === $_REQUEST[$confirmationField];
    }

    public function message(string $key): string
    {
        return "Please enter the same password again!";
    }
}