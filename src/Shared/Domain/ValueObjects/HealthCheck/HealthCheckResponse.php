<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects\HealthCheck;

final class HealthCheckResponse
{
    private $data;

    public function __construct($data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Parameter must be an array');
        }
        if (!array_key_exists('message', $data)) {
            throw new \InvalidArgumentException('Parameter must contains a `message` key');
        }
        if (!array_key_exists('data', $data)) {
            throw new \InvalidArgumentException('Parameter must contains a `data` key');
        }
        if (!array_key_exists('errors', $data)) {
            throw new \InvalidArgumentException('Parameter must contains a `errors` key');
        }
        if (!array_key_exists('status', $data)) {
            throw new \InvalidArgumentException('Parameter must contains a `status` key');
        }

        $this->data = $data;
    }

    public function message(): string
    {
        return $this->data['message'];
    }

    public function data(): array
    {
        return $this->data['data'];
    }

    public function errors(): array
    {
        return $this->data['errors'];
    }

    public function status(): int
    {
        return $this->data['status'];
    }
}
