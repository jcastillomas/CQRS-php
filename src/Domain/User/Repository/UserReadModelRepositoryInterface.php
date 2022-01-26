<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\ValueObject\Email;

interface UserReadModelRepositoryInterface
{
    public function getCredentialsByEmail(Email $email): array;

    public function oneByEmail(Email $email);
}
