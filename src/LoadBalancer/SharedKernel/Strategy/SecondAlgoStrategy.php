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

    public function handle(Request $request, array $hosts, string $algorithm): void
    {
        $overload = true;
        /** @var LoadBalancer $host */
        foreach ($hosts as $host) {
            if (self::SCORE > $host->getLoad()) {
                $overload = false;
            }
            if (false === $overload) {
                $host->handleRequest($request);
            }
        }

        if ($overload) {
            /** @var LoadBalancer $host */
            $lowestHost = null;
            foreach ($hosts as $host) {
                if (null === $lowestHost) {
                    $lowestHost = $host;
                } else if ($lowestHost->getLoad() > $host->getLoad()) {
                    $lowestHost = $host;
                }
            }
            $lowestHost->handleRequest($request);
        }
    }
}
