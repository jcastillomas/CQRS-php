<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\User;
use Ramsey\Uuid\UuidInterface;

interface UserWriteModelRepositoryInterface
{
    public function get(UuidInterface $uuid): User;

    public function save(User $user): void;
}
