<?php

declare(strict_types = 1);

namespace App\Tests\LoadBalancer\Application;

use App\LoadBalancer\Application\LoadBalancerService;
use App\LoadBalancer\Domain\LoadBalancer;
use App\LoadBalancer\SharedKernel\Strategy\LoadBalancerContext;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class LoadBalancerServiceTest extends TestCase
{
    private const ALGORITHM = 'algorithm';
    
    private LoadBalancerContext|MockObject $context;
    private array $hosts;
    
    public function setUp(): void
    {
        $this->context = $this->createMock(LoadBalancerContext::class);
        $this->hosts = [
            new LoadBalancer(0.8),
        ];
    }

    public function testHandle(): void
    {
        $request = $this->createMock(Request::class);
        
        $this->context->expects(self::once())
            ->method('handle')
            ->with(...[
                $request,
                $this->hosts,
                self::ALGORITHM,
            ]);
        
        $loadBalancerService = new LoadBalancerService(
            $this->context,
            $this->hosts,
            self::ALGORITHM,
        );
        
        $loadBalancerService->handle($request);
    }
}
