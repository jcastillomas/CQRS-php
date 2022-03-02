<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignUp;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Shared\Exception\DateTimeException;
use App\Domain\User\Repository\UserWriteModelRepositoryInterface;
use App\Domain\User\User;

final class SignUpHandler implements CommandHandlerInterface
{
    private UserWriteModelRepositoryInterface $mysqlWriteModelUserRepository;

    public function __construct(
        UserWriteModelRepositoryInterface $mysqlWriteModelUserRepository
    ) {
        $this->mysqlWriteModelUserRepository = $mysqlWriteModelUserRepository;
    }

    /**
     * @throws DateTimeException
     */
    public function __invoke(SignUpCommand $command): void
    {
        $user = User::create($command->uuid, $command->credentials);

        $this->mysqlWriteModelUserRepository->save($user);
    }
}
