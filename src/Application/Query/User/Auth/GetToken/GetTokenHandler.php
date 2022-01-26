<?php

declare(strict_types=1);

namespace App\Application\Query\User\Auth\GetToken;

use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\User\Auth\AuthenticationProvider;
use App\Domain\User\Repository\UserReadModelRepositoryInterface;

final class GetTokenHandler implements QueryHandlerInterface
{
    private UserReadModelRepositoryInterface $userCredentialsByEmail;

    private AuthenticationProvider $authenticationProvider;

    public function __construct(
        UserReadModelRepositoryInterface $userCredentialsByEmail,
        AuthenticationProvider $authenticationProvider
    ) {
        $this->authenticationProvider = $authenticationProvider;
        $this->userCredentialsByEmail = $userCredentialsByEmail;
    }

    public function __invoke(GetTokenQuery $query): string
    {
        [$uuid, $email, $hashedPassword] = $this->userCredentialsByEmail->getCredentialsByEmail($query->email);

        return $this->authenticationProvider->generateToken($uuid, $email, $hashedPassword);
    }
}
