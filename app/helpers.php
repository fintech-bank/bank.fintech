<?php
if (! function_exists('random_numeric')) {
    function random_numeric($len = 10)
    {
        $chars = '0123456789';
        $var_size = strlen($chars);
        $random_str = '';
        for ($x = 0; $x < $len; $x++) {
            $random_str .= $chars[rand(0, $var_size - 1)];
        }

        return $random_str;
    }
}

if (! function_exists('eur')) {
    function eur($value): string
    {
        return number_format(floatval($value), 2, ',', ' ').' €';
    }
}
