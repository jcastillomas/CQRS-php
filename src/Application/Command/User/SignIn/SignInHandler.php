<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\User\Repository\UserReadModelRepositoryInterface;
use App\Domain\User\Repository\UserWriteModelRepositoryInterface;
use Exception;

final class SignInHandler implements CommandHandlerInterface
{
    private UserWriteModelRepositoryInterface $mysqlWriteModelUserRepository;
    private UserReadModelRepositoryInterface $mysqlReadModelUserRepository;

    public function __construct(
        UserReadModelRepositoryInterface $mysqlReadModelUserRepository,
        UserWriteModelRepositoryInterface $mysqlWriteModelUserRepository
    ) {
        $this->mysqlReadModelUserRepository = $mysqlReadModelUserRepository;
        $this->mysqlWriteModelUserRepository = $mysqlWriteModelUserRepository;
    }

    public function __invoke(SignInCommand $command): void
    {
        try {
            $user = $this->mysqlReadModelUserRepository->oneByEmail($command->email);
        } catch (Exception $e) {
            throw new InvalidCredentialsException($e->getMessage());
        }

        $user->signIn($command->plainPassword);

        $this->mysqlWriteModelUserRepository->save($user);
    }
}
