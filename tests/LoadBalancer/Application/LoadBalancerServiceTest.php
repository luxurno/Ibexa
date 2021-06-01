<?php

declare(strict_types = 1);

namespace App\Tests\LoadBalancer\Application;

use App\LoadBalancer\Application\LoadBalancerService;
use App\LoadBalancer\Domain\LoadBalancer;
use App\LoadBalancer\SharedKernel\Enum\LoadBalancerEnum;
use App\LoadBalancer\SharedKernel\Strategy\Exception\IncorrectAlgorithmException;
use App\LoadBalancer\SharedKernel\Strategy\Exception\MissingLoadBalancerException;
use App\LoadBalancer\SharedKernel\Strategy\LoadBalancerContext;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class LoadBalancerServiceTest extends TestCase
{
    private const INCORRECT_ALGORITHM = 'incorrect-algorithm';
    private const CORRECT_ALGORITHM = LoadBalancerEnum::SECOND_ALGO;

    private LoadBalancerContext|MockObject $context;
    private array $loadBalancers;
    
    public function setUp(): void
    {
        $this->context = $this->createMock(LoadBalancerContext::class);
        $this->loadBalancers = [
            new LoadBalancer(0.8),
        ];
    }

    public function testHandleWithIncorrectAlgorithm(): void
    {
        $request = $this->createMock(Request::class);
        
        $this->context->expects(self::once())
            ->method('handle')
            ->with(...[
                $request,
                $this->loadBalancers,
                self::INCORRECT_ALGORITHM,
            ])->willThrowException(new IncorrectAlgorithmException());

        $loadBalancerService = new LoadBalancerService(
            $this->context,
            $this->loadBalancers,
            self::INCORRECT_ALGORITHM,
        );

        $this->expectException(IncorrectAlgorithmException::class);

        $loadBalancerService->handle($request);
    }

    public function testHandleWithCorrectAlgorithm(): void
    {
        $request = $this->createMock(Request::class);

        $this->context->expects(self::once())
            ->method('handle')
            ->with(...[
                $request,
                $this->loadBalancers,
                self::CORRECT_ALGORITHM,
            ]);

        $loadBalancerService = new LoadBalancerService(
            $this->context,
            $this->loadBalancers,
            self::CORRECT_ALGORITHM,
        );

        $loadBalancerService->handle($request);
    }

    public function testHandleWithMissingLoadBalancers(): void
    {
        $loadBalancers = [];
        $request = $this->createMock(Request::class);

        $this->context->expects(self::once())
            ->method('handle')
            ->with(...[
                $request,
                $loadBalancers,
                self::CORRECT_ALGORITHM,
            ])->willThrowException(new MissingLoadBalancerException());

        $loadBalancerService = new LoadBalancerService(
            $this->context,
            $loadBalancers,
            self::CORRECT_ALGORITHM,
        );

        $this->expectException(MissingLoadBalancerException::class);

        $loadBalancerService->handle($request);
    }
}
