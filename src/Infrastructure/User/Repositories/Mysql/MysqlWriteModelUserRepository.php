<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repositories\Mysql;

use App\Domain\User\Repository\UserWriteModelRepositoryInterface;
use App\Domain\User\User;
use App\Infrastructure\Shared\Persistence\ReadModel\Repository\MysqlRepository;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;

final class MysqlWriteModelUserRepository extends MysqlRepository  implements UserWriteModelRepositoryInterface
{
    protected function setEntityManager(): void
    {
        /** @var EntityRepository $objectRepository */
        $objectRepository = $this->entityManager->getRepository(User::class);
        $this->repository = $objectRepository;
    }

    public function save(User $user): void
    {
        $this->register($user);
    }

    public function get(UuidInterface $uuid): User
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.uuid = :uuid')
            ->setParameter('uuid', $uuid->getBytes())
        ;

        return $this->oneOrException($qb);
    }

}
