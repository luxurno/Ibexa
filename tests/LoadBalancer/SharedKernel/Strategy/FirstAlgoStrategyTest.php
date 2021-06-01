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

        $loadBalancerOne = $this->createMock(LoadBalancer::class);
        $loadBalancerTwo = $this->createMock(LoadBalancer::class);

        $loadBalancerOne->expects(self::once())
            ->method('handleRequest')
            ->with(...[$request]);

        $loadBalancerTwo->expects(self::once())
            ->method('handleRequest')
            ->with(...[$request]);

        $loadBalancers = [$loadBalancerOne, $loadBalancerTwo];

        $strategy = new FirstAlgoStrategy();

        $strategy->handle($request, $loadBalancers, self::ALGORITHM);
    }
}