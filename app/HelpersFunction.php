<?php

use Illuminate\Support\Str;

if (! function_exists('account_initial')) {
    function account_initial(?string $name = null, string $fallback = 'U'): string
    {
        $value = trim((string) $name);

        if ($value === '') {
            return $fallback;
        }

        return Str::upper(Str::substr($value, 0, 1));
    }
}
