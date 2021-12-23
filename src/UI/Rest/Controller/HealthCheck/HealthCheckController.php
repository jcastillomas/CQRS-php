<?php

declare(strict_types=1);

namespace App\UI\Rest\Controller\HealthCheck;

use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HealthCheckController
{

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
    public function __invoke(Request $request): Response
    {
        $elastic = null;
        $mysql = null;

        if (
            //true === $elastic = $this->elasticSearchEventRepository->isHealthly() &&
            //true === $mysql = $this->mysqlReadModelUserRepository->isHealthy() &&
            true
        ) {
            return new Response('ok', Response::HTTP_OK, ['content-type' => 'application/json; charset=utf-8']);
        }

        return new Response('error', Response::HTTP_INTERNAL_SERVER_ERROR, ['content-type' => 'application/json; charset=utf-8']);

    }
}
