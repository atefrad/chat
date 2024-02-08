<?php

namespace App\Core\Validations\Contracts;

interface ValidationInterface
{
    public static function make(array $request,array $rules);
}