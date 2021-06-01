<?php

declare(strict_types = 1);

namespace App\LoadBalancer\SharedKernel\Strategy;

use App\LoadBalancer\SharedKernel\Enum\LoadBalancerEnum;
use App\LoadBalancer\SharedKernel\Strategy\Exception\IncorrectAlgorithmException;
use App\LoadBalancer\SharedKernel\Strategy\Exception\MissingLoadBalancerException;
use Symfony\Component\HttpFoundation\Request;

class LoadBalancerContext
{
    private array $strategies = [];

    public function addStrategy(LoadBalancerStrategyInterface $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    public function handle(Request $request, array $loadBalancers, string $algorithm): void
    {
        if ([] === $loadBalancers) {
            throw new MissingLoadBalancerException('Missing LoadBalancers!');
        }

        if (false === in_array($algorithm, LoadBalancerEnum::getAll(), true)) {
            throw new IncorrectAlgorithmException('Incorrect algorithm');
        }

        /** @var LoadBalancerStrategyInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->isValid($algorithm)) {
                $strategy->handle($request, $loadBalancers, $algorithm);
            }
        }
    }
}