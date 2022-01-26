<?php

declare(strict_types=1);

namespace App\Application\Query\User\Auth\GetAuthUserByEmail;

use App\Application\Query\QueryHandlerInterface;
use App\Domain\User\Repository\UserReadModelRepositoryInterface;
use App\Infrastructure\User\Auth\Auth;

final class GetAuthUserByEmailHandler implements QueryHandlerInterface
{
    private UserReadModelRepositoryInterface $userCredentialsByEmail;

    public function __construct(
        UserReadModelRepositoryInterface $userCredentialsByEmail
    ) {
        $this->userCredentialsByEmail = $userCredentialsByEmail;
    }

    public function __invoke(GetAuthUserByEmailQuery $query): Auth
    {
        [$uuid, $email, $hashedPassword] = $this->userCredentialsByEmail->getCredentialsByEmail($query->email);

        return Auth::create($uuid, $email, $hashedPassword);
    }
}
