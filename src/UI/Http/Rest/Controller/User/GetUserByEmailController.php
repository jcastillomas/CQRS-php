<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\Query\Item;
use App\Application\Query\User\FindByEmail\FindByEmailQuery;
use App\UI\Http\Rest\Controller\QueryController;
use App\UI\Http\Rest\Response\OpenApi;
use Assert\Assertion;
use Assert\AssertionFailedException;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class GetUserByEmailController extends QueryController
{
    /**
     * @Route(
     *     "/api/admin/user/{email}",
     *     name="find_user",
     *     methods={"GET"}
     * )
     * @OA\Get(
     *     path="/api/admin/user/{email}",
     *     summary="Returns the user of the given email",
     *     tags={"Admin"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Returns the user of the given email",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
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
    public function __invoke(string $email): OpenApi
    {
        Assertion::email($email, "Email can\'t be empty or invalid");

        $command = new FindByEmailQuery($email);

        /** @var Item $user */
        $user = $this->ask($command);

        return $this->json($user);
    }
}
