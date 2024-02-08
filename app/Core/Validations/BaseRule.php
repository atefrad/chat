<?php

namespace App\Core\Validations;

use App\Core\Validations\Contracts\RuleInterface;

abstract class BaseRule implements RuleInterface
{
    public function name(): string
    {
       return strtolower(get_class($this));
    }
}