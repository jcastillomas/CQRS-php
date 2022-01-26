<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\User\Repository\UserReadModelRepositoryInterface;
use App\Domain\User\Repository\UserWriteModelRepositoryInterface;

final class SignInHandler implements CommandHandlerInterface
{
    private UserWriteModelRepositoryInterface $mysqlWriteModelUserRepository;

    public function __construct(
        UserReadModelRepositoryInterface $mysqlReadModelUserRepository,
        UserWriteModelRepositoryInterface $mysqlWriteModelUserRepository
    ) {
        $this->mysqlReadModelUserRepository = $mysqlReadModelUserRepository;
        $this->mysqlWriteModelUserRepository = $mysqlWriteModelUserRepository;
    }

    public function __invoke(SignInCommand $command): void
    {
        $user = $this->mysqlReadModelUserRepository->oneByEmail($command->email);

        $user->signIn($command->plainPassword);

        $this->mysqlWriteModelUserRepository->save($user);
    }
}
