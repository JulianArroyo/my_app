<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\HealthCheck;

use App\Shared\Application\UseCases\HealthCheck\CreateHealthCheckResponseUseCase;
use App\Shared\Domain\Responses\APIResponse;
use App\Shared\Domain\UUIDGenerator\UUIDGenerator;
use Symfony\Component\HttpFoundation\Request;

final class HealthCheckGetController
{
    public function __invoke(Request $request, UUIDGenerator $UUIDGenerator): APIResponse
    {
//        return (new CreateHealthCheckResponseUseCase($UUIDGenerator))->__invoke();
//        $response = new CreateHealthCheckResponseUseCase();
//        return $response();
         return (new CreateHealthCheckResponseUseCase($UUIDGenerator))();
    }
}