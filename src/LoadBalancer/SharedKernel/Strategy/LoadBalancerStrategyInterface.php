<?php

declare(strict_types = 1);

namespace App\LoadBalancer\SharedKernel\Strategy;

use Symfony\Component\HttpFoundation\Request;

interface LoadBalancerStrategyInterface
{
    public function isValid(string $algorithm): bool;
    public function handle(Request $request, array $loadBalancers, string $algorithm): void;
}
