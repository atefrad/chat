<?php

namespace App\Core\Validations;

use App\Core\Validations\Contracts\RuleInterface;

class RegularExpression extends BaseRule implements RuleInterface
{
    private string $regex;
    public function __construct($regex)
    {
        $this->regex = $regex;
    }

    public function validate(string $key, $value): bool
    {
        return preg_match($this->regex, $value);
    }

    public function message(string $key): string
    {
        return "The characters used for the {$key} attribute are not valid!";
    }
}