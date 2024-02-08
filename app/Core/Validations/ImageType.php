<?php

namespace App\Core\Validations;

class ImageType extends BaseRule implements Contracts\RuleInterface
{

    public function validate(string $key, $value): bool
    {
        $allowedMimes = ['png', 'jpg', 'jpeg', 'gif'];

        $mime = mime_content_type($value);

        $mime = explode('/', $mime)[1];

        return in_array($mime, $allowedMimes);

    }

    public function message(string $key): string
    {
        return "Accepted {$key} types are png, jpg, jpeg and gif! ";
    }
}