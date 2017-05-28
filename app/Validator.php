<?php

use Illuminate\Validation\Factory as ValidatorFactory;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\HttpFoundation\Request;

class Validator
{
    public static function validate($data, $rules)
    {
        $errors = [];
        foreach ($rules as $field => $rule){
            if(isset($data[$field])) {
                $error = false;
                if(is_array($rule)) {
                    foreach ($rule as $_rule) {
                        $error = self::$_rule($field, $data[$field]);
                    }
                } else {
                    $error = self::$rule($field, $data[$field]);
                }
                if ($error)
                    $errors[] = $error;
            }
        }

        return $errors;
    }

    private static function required($field, $value)
    {
        if(!$value) {
            return [$field => ucfirst($field) . " is required"];
        }
        return false;
    }

    private static function email($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return [$field => "Invalid email format"];
        }
        return false;
    }

    private static function min($field, $value, $minLength)
    {
        if (strlen($value) <= $minLength) {
            return [$field => ucfirst($field) . " length must be above $minLength"];
        }
        return false;
    }

    private static function max($field, $value, $maxLength)
    {
        if (strlen($value) <= $maxLength) {
            return [$field => ucfirst($field) . " length must be above $maxLength"];
        }
        return false;
    }
}