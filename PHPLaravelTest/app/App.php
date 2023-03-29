<?php

namespace App;

use Illuminate\Support\Facades\App as BaseApp;

class App extends BaseApp
{
    /**
     * Is the app running in production mode?
     */
    public static function isProductiony(): bool
    {
        return in_array(config('app.env'), [
            'production',
            'staging',
            'qa',
        ]);
    }

    /**
     * Convert a multi-line string, where each line is one value, into an array of values
     */
    public static function convertMultiLineStringToArray(?string $input = null): array
    {
        $array = array_map(function (string $string) {
            return trim($string);
        }, explode("\r\n", $input));

        $array = array_filter($array, function (string $item) {
            return !!$item;
        });

        return array_values($array);
    }

    /**
     * Convert an array of values into a multi-line string, where each line is one value
     */
    public static function convertArrayToMultiLineString(?array $input = null): string
    {
        if (empty($input)) {
            return '';
        }

        return trim(array_reduce($input, function (string $acc, string $string) {
            if (!$string) {
                return $acc;
            }

            $acc .= $string."\n";

            return $acc;
        }, ''));
    }
}
