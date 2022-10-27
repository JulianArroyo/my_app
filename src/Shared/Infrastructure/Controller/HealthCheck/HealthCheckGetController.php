<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\HealthCheck;

use App\Shared\Application\UseCases\HealthCheck\CreateHealthCheckResponseUseCase;
use App\Shared\Domain\Responses\APIResponse;
use Symfony\Component\HttpFoundation\Request;

final class HealthCheckGetController
{
    public function __invoke(Request $request): APIResponse
    {
        return (new CreateHealthCheckResponseUseCase())->__invoke();
    }
}