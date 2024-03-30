<?php

namespace App\Core\Validations;

use App\Core\Validations\Contracts\ValidationInterface;

class Validation implements ValidationInterface
{
    private static ?array $errors = null;

    public static function make(array $request, array $rules)
    {
        //Validation::make($_request, [
        // 'title => [new Required, new Unique, new ValidCharacters, new Min, new Max]])

        foreach($request as $key => $value)
        {
            foreach($rules[$key] as $rule)
            {
                if(!$rule->validate($key, $value))
                {
                    self::$errors[$key] = $rule->message($key);
//                    $_SESSION['errors'][$key] = $rule->message($key);
                    break;
                }
            }
        }

//        return !isset($_SESSION['errors']);
        return is_null(self::$errors);


    }

    public function getErrors()
    {
//        if(isset($_SESSION['errors']))
//        {
//            return $_SESSION['errors'];
//        }

        return self::$errors;
    }
}