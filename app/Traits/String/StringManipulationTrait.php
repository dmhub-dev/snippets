<?php

namespace App\Traits\String;

trait StringManipulationTrait
{
    /**
     * Converts a string into kebab-case format.
     *
     * @param string $string The input string to convert.
     * @return string The kebab-cased version of the input string.
     */
    function kebab_case($string)
    {
        $string = strtolower($string);

        $string = preg_replace('/[^a-zA-Z0-9]+/', ' ', $string);

        $string = preg_replace_callback(
            '/([a-z])([A-Z])/',
            function ($matches) {
                return strtolower($matches[1]) . '-' . strtolower($matches[2]);
            },
            $string,
        );

        $string = str_replace(' ', '-', $string);

        $string = trim($string, '-');

        return $string;
    }

    /**
     * Converts a string into snake_case format.
     *
     * @param string $string The input string to convert.
     * @return string The snake-cased version of the input string.
     */
    function snake_case($string)
    {
        $string = strtolower($string);

        $string = preg_replace('/[^a-zA-Z0-9]+/', ' ', $string);

        $string = preg_replace_callback(
            '/([a-z])([A-Z])/',
            function ($matches) {
                return strtolower($matches[1]) . '_' . strtolower($matches[2]);
            },
            $string,
        );

        $string = str_replace(' ', '_', $string);

        $string = trim($string, '_');

        return $string;
    }

    /**
     * A map of locale-specific separators for number formatting.
     * 'nl' - Dutch: uses '.' for thousands and ',' for decimals.
     * 'en' - English: uses ',' for thousands and '.' for decimals.
     */
    private $separators_map = [
        'nl' => [
            'thousands_sep' => '.',
            'decimal_point' => ',',
        ],
        'en' => [
            'thousands_sep' => ',',
            'decimal_point' => '.',
        ],
    ];

    /**
     * Formats a number according to the current locale's conventions.
     *
     * @param float|int $number The number to format.
     * @param int $decimals The number of decimal places (default is 2).
     * @return string The formatted number as a string.
     */
    function number_format_locale($number, $decimals = 2)
    {
        $locale = app()->getLocale() ?? 'en';

        return number_format(
            $number,
            $decimals,
            $separators_map[$locale]['decimal_point'] ?? ',',
            $separators_map[$locale]['thousands_sep'] ?? '.',
        );
    }
}
