<?php

declare(strict_types = 1);

namespace App\LoadBalancer\SharedKernel\Enum;

class LoadBalancerEnum
{
    public const FIRST_ALGO = 'first_algo';
    public const SECOND_ALGO = 'second_algo';

    public static function getAll(): array
    {
        return [
            self::FIRST_ALGO,
            self::SECOND_ALGO,
        ];
    }
}
