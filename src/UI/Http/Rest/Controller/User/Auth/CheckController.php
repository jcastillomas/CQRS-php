<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User\Auth;

use App\Application\Command\User\SignIn\SignInCommand;
use App\Application\Query\User\Auth\GetToken\GetTokenQuery;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\UI\Http\Rest\Controller\CommandQueryController;
use App\UI\Http\Rest\Response\OpenApi;
use Assert\Assertion;
use Assert\AssertionFailedException;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class CheckController extends CommandQueryController
{
    /**
     * @Route(
     *     "/api/auth_check",
     *     name="auth_check",
     *     methods={"POST"},
     *     requirements={
     *      "_username": "\w+",
     *      "_password": "\w+"
     *     }
     * )
     * @OA\Post(
     *     path="/api/auth_check",
     *     summary="Check user",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Login success",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(
     *              property="token", type="string"
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Bad credentials"
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="_password", type="string"),
     *             @OA\Property(property="_username", type="string")
     *         )
     *     )
     * )
     *
     * @throws AssertionFailedException
     * @throws InvalidCredentialsException
     * @throws Throwable
     */
    public function __invoke(Request $request): OpenApi
    {
        $content = json_decode($request->getContent(), true);
        $username = $content['_username'];
        Assertion::notNull($username, 'Username cant\'t be empty');

        $signInCommand = new SignInCommand(
            $username,
            $content['_password']
        );

        $this->handle($signInCommand);

        return OpenApi::fromPayload(
            [
                'token' => $this->ask(new GetTokenQuery($username)),
            ],
            OpenApi::HTTP_OK
        );
    }
}
