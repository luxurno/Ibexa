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

        $hostOne = $this->createMock(LoadBalancer::class);
        $hostTwo = $this->createMock(LoadBalancer::class);

        $hostOne->method('getLoad')
            ->willReturn(0.8);

        $hostTwo->method('getLoad')
            ->willReturn(0.9);

        $hostOne->expects(self::once())
            ->method('handleRequest')
            ->with(...[$request]);

        $hosts = [$hostOne, $hostTwo];

        $strategy = new SecondAlgoStrategy();

        $strategy->handle($request, $hosts, self::ALGORITHM);
    }

    public function testHandleNotOverload(): void
    {
        $request = $this->createMock(Request::class);

        $hostOne = $this->createMock(LoadBalancer::class);
        $hostTwo = $this->createMock(LoadBalancer::class);

        $hostOne->method('getLoad')
            ->willReturn(0.8);

        $hostTwo->method('getLoad')
            ->willReturn(0.7);

        $hostTwo->expects(self::once())
            ->method('handleRequest')
            ->with(...[$request]);

        $hosts = [$hostOne, $hostTwo];

        $strategy = new SecondAlgoStrategy();

        $strategy->handle($request, $hosts, self::ALGORITHM);
    }
}