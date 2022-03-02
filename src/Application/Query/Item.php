<?php

declare(strict_types=1);

namespace App\Application\Query;


final class Item
{
    public array $resource;

    private function __construct(array $payload)
    {
        $this->resource = $payload;
    }

    public static function fromArray(array $payload): self
    {
        return new self($payload);
    }

    private function fromJson(string $payload)
    {
        $this->resource = json_decode($payload, true);
    }
}
