<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByUuid;

use App\Application\Query\QueryInterface;
use Assert\AssertionFailedException;
use Ramsey\Uuid\UuidInterface;

final class FindByUuidQuery implements QueryInterface
{
    /** @psalm-readonly */
    public UuidInterface $uuid;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }
}
