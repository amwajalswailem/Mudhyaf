<?php

namespace App\Constants;

class Role
{
    public const USER = 'user';
    public const ADMIN = 'admin';
    public const BUSINESS_OWNER = 'business_owner';

    /**
     * Return all roles as array
     */
    public static function all(): array
    {
        return [
            self::USER,
            self::ADMIN,
            self::BUSINESS_OWNER,
        ];
    }

    /**
     * Return roles for dropdown
     */
    public static function options(): array
    {
        return [
            self::USER => 'User',
            self::ADMIN => 'Admin',
            self::BUSINESS_OWNER => 'Business Owner',
        ];
    }
}
