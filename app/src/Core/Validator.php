<?php

namespace App\Core;

class Validator
{
    public static function required($value): bool
    {
        return trim((string) $value) !== '';
    }

    public static function email($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function minLength($value, int $length): bool
    {
        return strlen(trim((string) $value)) >= $length;
    }

    public static function maxLength($value, int $length): bool
    {
        return strlen(trim((string) $value)) <= $length;
    }

    public static function integer($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    public static function nonNegativeInteger($value): bool
    {
        if (!self::integer($value)) {
            return false;
        }

        return (int) $value >= 0;
    }

    public static function price($value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        return (float) $value >= 0;
    }

    public static function sanitizeString($value): string
    {
        return trim((string) $value);
    }
}