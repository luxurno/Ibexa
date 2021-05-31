<?php

declare(strict_types = 1);

namespace App\LoadBalancer\Domain;

use Symfony\Component\HttpFoundation\Request;

class LoadBalancer
{
    public function __construct(
        private float $load,
    ) { }

    public function getLoad(): float
    {
        return $this->load;
    }

    public function handleRequest(Request $request): void
    {
        // TODO
    }
}
