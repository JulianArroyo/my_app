<?php

declare(strict_types=1);

namespace App\Shared\Domain\UUIDGenerator;

final class UUIDv4 implements UUIDGenerator
{
    // https://www.uuidgenerator.net/dev-corner/php
    // https://stackoverflow.com/questions/105034/how-do-i-create-a-guid-uuid -> in JS
    public static function v4(): string
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? \random_bytes(16);
        \assert(16 == \strlen($data));

        // Set version to 0100
        $data[6] = \chr(\ord($data[6]) & 0x0F | 0x40);
        // Set bits 6-7 to 10
        $data[8] = \chr(\ord($data[8]) & 0x3F | 0x80);

        // Output the 36 character UUID.
        return \vsprintf('%s%s-%s-%s-%s-%s%s%s', \str_split(\bin2hex($data), 4));
    }
}
