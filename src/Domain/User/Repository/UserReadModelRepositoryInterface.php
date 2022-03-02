<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\User;
use App\Domain\User\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

interface UserReadModelRepositoryInterface
{
    public function getCredentialsByEmail(Email $email): array;

    public function oneByEmail(Email $email): User;

    public function oneByEmailAsArray(Email $email): array;

    public function oneByUuid(UuidInterface $uuid): User;

    public function oneByUuidAsArray(UuidInterface $uuid): array;
}
