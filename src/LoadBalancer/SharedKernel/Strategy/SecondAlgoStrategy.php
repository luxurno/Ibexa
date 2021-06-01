<?php

declare(strict_types = 1);

namespace App\LoadBalancer\SharedKernel\Strategy;

use App\LoadBalancer\Domain\LoadBalancer;
use App\LoadBalancer\SharedKernel\Enum\LoadBalancerEnum;
use Symfony\Component\HttpFoundation\Request;

class SecondAlgoStrategy implements LoadBalancerStrategyInterface
{
    private const SCORE = 0.75;

    public function isValid(string $algorithm): bool
    {
        return $algorithm === LoadBalancerEnum::SECOND_ALGO;
    }

    public function handle(Request $request, array $loadBalancers, string $algorithm): void
    {
        $overload = true;
        /** @var LoadBalancer $loadBalancer */
        foreach ($loadBalancers as $loadBalancer) {
            if (self::SCORE > $loadBalancer->getLoad()) {
                $overload = false;
            }
            if (false === $overload) {
                $loadBalancer->handleRequest($request);
            }
        }

        if ($overload) {
            /** @var LoadBalancer $loadBalancer */
            $lowestLoadBalancer = null;
            foreach ($loadBalancers as $loadBalancer) {
                if (null === $lowestLoadBalancer) {
                    $lowestLoadBalancer = $loadBalancer;
                } else if ($lowestLoadBalancer->getLoad() > $loadBalancer->getLoad()) {
                    $lowestLoadBalancer = $loadBalancer;
                }
            }
            $lowestLoadBalancer->handleRequest($request);
        }
    }
}
