<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeEmail;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\User\Repository\UserWriteModelRepositoryInterface;

final class ChangeEmailHandler implements CommandHandlerInterface
{
    private UserWriteModelRepositoryInterface $mysqlWriteModelUserRepository;

    public function __construct(
        UserWriteModelRepositoryInterface $mysqlWriteModelUserRepository
    ) {
        $this->mysqlWriteModelUserRepository = $mysqlWriteModelUserRepository;
    }

    public function __invoke(ChangeEmailCommand $command): void
    {
        $user = $this->mysqlWriteModelUserRepository->get($command->userUuid);

        $user->setEmail($command->email);

        $this->mysqlWriteModelUserRepository->save($user);
    }
}
