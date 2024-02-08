<?php

namespace App\Core\Validations\Contracts;

interface RuleInterface
{
    public function validate(string $key, $value): bool;

    public function message(string $key): string;

    public function name(): string;
}