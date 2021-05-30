<?php

namespace Leads;

class Utils
{
    public static function arrayHumanReadable(array $array): string
    {
        $output = '';
        
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                $output .= "{$key} - {$value}\n";
            }
        }

        return $output ?: 'нет данных';
    }
}