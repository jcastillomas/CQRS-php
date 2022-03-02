<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repositories\Mysql;

use App\Domain\User\User;
use App\Infrastructure\Shared\Persistence\ReadModel\Repository\MysqlRepository;
use App\Domain\User\Repository\UserReadModelRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\Shared\Persistence\ReadModel\Exception\NotFoundException;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

final class MysqlReadModelUserRepository extends MysqlRepository implements UserReadModelRepositoryInterface
{
    protected function setEntityManager(): void
    {
        /** @var EntityRepository $objectRepository */
        $objectRepository = $this->entityManager->getRepository(User::class);
        $this->repository = $objectRepository;
    }

    private function getUserByEmailQueryBuilder(Email $email): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email->toString());
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByEmail(Email $email): User
    {
        return $this->oneOrException(
            $this->getUserByEmailQueryBuilder($email)
        );
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByEmailAsArray(Email $email): array
    {
        return $this->oneOrException(
            $this->getUserByEmailQueryBuilder($email),
            AbstractQuery::HYDRATE_ARRAY
        );
    }

    private function getUserByUuidQueryBuilder(UuidInterface $uuid): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder('user')
            ->where('user.uuid = :uuid')
            ->setParameter('uuid', $uuid->getBytes());
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByUuid(UuidInterface $uuid): User
    {
        return $this->oneOrException(
            $this->getUserByUuidQueryBuilder($uuid)
        );
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function oneByUuidAsArray(UuidInterface $uuid): array
    {
        return $this->oneOrException(
            $this->getUserByUuidQueryBuilder($uuid),
            AbstractQuery::HYDRATE_ARRAY
        );
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     *
     * @return array{0: \Ramsey\Uuid\UuidInterface, 1: Email, 2: \App\User\Domain\ValueObject\Auth\HashedPassword}
     */
    public function getCredentialsByEmail(Email $email): array
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email->toString());

        $user = $this->oneOrException($qb, AbstractQuery::HYDRATE_ARRAY);

        return [
            $user['uuid'],
            $user['credentials.email'],
            $user['credentials.password'],
        ];
    }
}
