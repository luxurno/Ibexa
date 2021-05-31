<?php

declare(strict_types = 1);

namespace App\LoadBalancer\Application;

use App\LoadBalancer\SharedKernel\Strategy\LoadBalancerContext;
use Symfony\Component\HttpFoundation\Request;

class LoadBalancerService
{
    public function __construct(
        private LoadBalancerContext $context,
        private array $hosts,
        private string $algorithm
    ) { }

    public function handle(Request $request): void
    {
        $this->context->handle(
            $request,
            $this->hosts,
            $this->algorithm
        );
    }
}
