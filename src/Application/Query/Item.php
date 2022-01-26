<?php

declare(strict_types=1);

namespace App\Application\Query;


final class Item
{
    public string $resource;

    private function __construct(string $payload)
    {
        $this->resource = $payload;
    }

    public static function fromArray(array $payload): self
    {
        new self(json_encode($payload));
    }
}
