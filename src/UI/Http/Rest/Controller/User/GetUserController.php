<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\Query\Item;
use App\Application\Query\User\FindByEmail\FindByEmailQuery;
use App\Application\Query\User\FindByUuid\FindByUuidQuery;
use App\Infrastructure\User\Auth\Auth;
use App\UI\Http\Rest\Controller\QueryController;
use App\UI\Http\Rest\Response\OpenApi;
use App\UI\Http\Session;
use Assert\Assertion;
use Assert\AssertionFailedException;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class GetUserController extends QueryController
{
    /**
     * @Route(
     *     "/api/user",
     *     name="find_user",
     *     methods={"GET"}
     * )
     * @OA\Get(
     *     path="/api/user",
     *     summary="Returns the current user",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="Returns the user of the given email",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string"),
     *         )
     *     )
     * )
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function __invoke(Session $session): OpenApi
    {
        $command = new FindByUuidQuery($session->get()->uuid());

        /** @var Item $user */
        $user = $this->ask($command);

        return $this->json($user);
    }
}
