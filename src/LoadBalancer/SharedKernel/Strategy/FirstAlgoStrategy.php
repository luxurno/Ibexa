<?php

declare(strict_types = 1);

namespace App\LoadBalancer\SharedKernel\Strategy;

use App\LoadBalancer\Domain\LoadBalancer;
use App\LoadBalancer\SharedKernel\Enum\LoadBalancerEnum;
use Symfony\Component\HttpFoundation\Request;

class FirstAlgoStrategy implements LoadBalancerStrategyInterface
{
    public function isValid(string $algorithm): bool
    {
        return $algorithm === LoadBalancerEnum::FIRST_ALGO;
    }

    public function handle(
        Request $request,
        array $loadBalancers,
        string $algorithm
    ): void
    {
        /** @var LoadBalancer $loadBalancer */
        foreach ($loadBalancers as $loadBalancer) {
            $loadBalancer->handleRequest($request);
        }

    }
}