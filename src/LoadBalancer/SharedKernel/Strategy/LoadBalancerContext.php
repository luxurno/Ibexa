<?php

declare(strict_types = 1);

namespace App\LoadBalancer\SharedKernel\Strategy;

use Symfony\Component\HttpFoundation\Request;

class LoadBalancerContext
{
    private array $strategies = [];

    public function addStrategy(LoadBalancerStrategyInterface $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    public function handle(Request $request, array $hosts, string $algorithm): void
    {
        /** @var LoadBalancerStrategyInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->isValid($algorithm)) {
                $strategy->handle($request, $hosts, $algorithm);
            }
        }
    }
}