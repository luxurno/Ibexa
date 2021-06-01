<?php

declare(strict_types = 1);

namespace App\Tests\LoadBalancer\SharedKernel\Strategy;

use App\LoadBalancer\Domain\LoadBalancer;
use App\LoadBalancer\SharedKernel\Enum\LoadBalancerEnum;
use App\LoadBalancer\SharedKernel\Strategy\SecondAlgoStrategy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class SecondAlgoStrategyTest extends TestCase
{
    private const ALGORITHM = LoadBalancerEnum::SECOND_ALGO;

    public function testHandleOverload(): void
    {
        $request = $this->createMock(Request::class);

        $loadBalancerOne = $this->createMock(LoadBalancer::class);
        $loadBalancerTwo = $this->createMock(LoadBalancer::class);

        $loadBalancerOne->method('getLoad')
            ->willReturn(0.8);

        $loadBalancerTwo->method('getLoad')
            ->willReturn(0.9);

        $loadBalancerOne->expects(self::once())
            ->method('handleRequest')
            ->with(...[$request]);

        $hosts = [$loadBalancerOne, $loadBalancerTwo];

        $strategy = new SecondAlgoStrategy();

        $strategy->handle($request, $hosts, self::ALGORITHM);
    }

    public function testHandleNotOverload(): void
    {
        $request = $this->createMock(Request::class);

        $loadBalancerOne = $this->createMock(LoadBalancer::class);
        $loadBalancerTwo = $this->createMock(LoadBalancer::class);

        $loadBalancerOne->method('getLoad')
            ->willReturn(0.8);

        $loadBalancerTwo->method('getLoad')
            ->willReturn(0.7);

        $loadBalancerTwo->expects(self::once())
            ->method('handleRequest')
            ->with(...[$request]);

        $hosts = [$loadBalancerOne, $loadBalancerTwo];

        $strategy = new SecondAlgoStrategy();

        $strategy->handle($request, $hosts, self::ALGORITHM);
    }
}