<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByUuid;

use App\Application\Query\Item;
use App\Application\Query\QueryHandlerInterface;
use App\Infrastructure\Shared\Persistence\ReadModel\Exception\NotFoundException;
use App\Domain\User\Repository\UserReadModelRepositoryInterface;
use Doctrine\ORM\NonUniqueResultException;

final class FindByUuidHandler implements QueryHandlerInterface
{
    private UserReadModelRepositoryInterface $repository;

    public function __construct(UserReadModelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function __invoke(FindByUuidQuery $query): Item
    {
        $response = $this->repository->oneByUuidAsArray($query->uuid);

        return Item::fromArray($response);
    }
}
