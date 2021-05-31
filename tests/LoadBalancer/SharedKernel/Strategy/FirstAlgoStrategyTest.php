<?php

declare(strict_types = 1);

namespace App\Tests\LoadBalancer\SharedKernel\Strategy;

use App\LoadBalancer\Domain\LoadBalancer;
use App\LoadBalancer\SharedKernel\Enum\LoadBalancerEnum;
use App\LoadBalancer\SharedKernel\Strategy\FirstAlgoStrategy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class FirstAlgoStrategyTest extends TestCase
{
    private const ALGORITHM = LoadBalancerEnum::SECOND_ALGO;

    public function testHandle(): void
    {
        $request = $this->createMock(Request::class);

        $hostOne = $this->createMock(LoadBalancer::class);
        $hostTwo = $this->createMock(LoadBalancer::class);

        $hostOne->expects(self::once())
            ->method('handleRequest')
            ->with(...[$request]);

        $hostTwo->expects(self::once())
            ->method('handleRequest')
            ->with(...[$request]);

        $hosts = [$hostOne, $hostTwo];

        $strategy = new FirstAlgoStrategy();

        $strategy->handle($request, $hosts, self::ALGORITHM);
    }
}