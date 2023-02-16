<?php

namespace _Utils;

class Utils
{
    public static function cleanCMD(): void
    {
        echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
    }

    public static function removingCharactersFromNumbers(string $var): string
    {
        return preg_replace('/[^0-9]/', '', $var);
    }

    public static function removingSingleQuotesFromString(string $var): string {
        return str_replace("'", "", $var);
    }
}