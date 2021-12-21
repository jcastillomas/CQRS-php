<?php

declare(strict_types=1);

namespace App\UI\Rest\Controller\HealthCheck;

use App\Infrastructure\Shared\Event\ReadModel\ElasticSearchEventRepository;
use App\Infrastructure\User\ReadModel\Mysql\MysqlReadModelUserRepository;
use App\UI\Http\Rest\Response\OpenApi;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class HealthCheckController
{
    private ElasticSearchEventRepository $elasticSearchEventRepository;
    private MysqlReadModelUserRepository $mysqlReadModelUserRepository;

    public function __construct(
        ElasticSearchEventRepository $elasticSearchEventRepository,
        MysqlReadModelUserRepository $mysqlReadModelUserRepository)
    {
        $this->elasticSearchEventRepository = $elasticSearchEventRepository;
        $this->mysqlReadModelUserRepository = $mysqlReadModelUserRepository;
    }

    /**
     * @Route(
     *     "/status",
     *     name="status",
     *     methods={"GET"}
     * )
     * @OA\Response(
     *     response=200,
     *     description="OK"
     * )
     * @OA\Response(
     *     response=500,
     *     description="Something wrong"
     * )
     *
     * @OA\Tag(name="HealthCheck")
     */
    public function __invoke(Request $request): OpenApi
    {
        $elastic = null;
        $mysql = null;

        if (
            //true === $elastic = $this->elasticSearchEventRepository->isHealthly() &&
            //true === $mysql = $this->mysqlReadModelUserRepository->isHealthy() &&
            true
        ) {
            return OpenApi::empty(200);
        }

        return OpenApi::fromPayload(
            [
                'Healthy services' => [
                    'Elastic' => $elastic,
                    'MySQL' => $mysql,
                ],
            ],
            500
        );
    }
}
