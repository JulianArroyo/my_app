<?php

declare(strict_types=1);

namespace App\Tests\Functional\Shared\Infrastructure\Controller\HealthCheck;

use App\Shared\Domain\UUIDGenerator\UUID;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class HealthCheckGetControllerTest extends WebTestCase
{
    private const ENDPOINT = '/api/v1/health-check';
    protected ?AbstractBrowser $client = null;

    public function setUp(): void
    {
        if (null === $this->client) {
            $this->client = self::createClient();
            $this->client->setServerParameter('CONTENT_TYPE', 'application/json');
        }
    }

    public function tearDown(): void
    {
        $this->client = null;
    }

    public function testHealthCheckGetController(): void
    {
        $this->client->request(Request::METHOD_GET, self::ENDPOINT);
        $response = $this->client->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals(Response::HTTP_OK, $responseData['status']);
        self::assertEquals(Response::$statusTexts[Response::HTTP_OK], $responseData['status_text']);
        self::assertEquals('HealthCheckResponse works fine', $responseData['message']);
        self::assertTrue(UUID::is_valid($responseData['data']['uuid']));
    }

    protected function getResponseData(Response $response): array
    {
        return \json_decode($response->getContent(), true);
    }
}