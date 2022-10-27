<?php

declare(strict_types=1);

namespace App\Shared\Application\UseCases\HealthCheck;

use App\Shared\Domain\Responses\APIResponse;
use App\Shared\Domain\UUIDGenerator\UUID;
use App\Shared\Domain\ValueObjects\HealthCheck\HealthCheckResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateHealthCheckResponseUseCase
{
    public function __invoke(): APIResponse
    {
        try {
            $hcResponse = new HealthCheckResponse([
                'message' => 'HealthCheckResponse works fine',
                'data' => [],
                'errors' => [],
                'status' => Response::HTTP_OK
            ]);
            return new APIResponse(
                $hcResponse->message(),
                $hcResponse->data(),
                $hcResponse->errors(),
                $hcResponse->status()
            );
        } catch (\InvalidArgumentException $e) {
            return new APIResponse(
                $e->getMessage(),
                [],
                [],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}