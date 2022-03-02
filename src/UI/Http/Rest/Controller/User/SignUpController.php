<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\Command\User\SignUp\SignUpCommand;
use App\UI\Http\Rest\Controller\CommandController;
use App\UI\Http\Rest\Response\OpenApi;
use Assert\Assertion;
use Assert\AssertionFailedException;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class SignUpController extends CommandController
{
    /**
     * @Route(
     *     "/api/signup",
     *     name="user_create",
     *     methods={"POST"}
     * )
     * @OA\Post(
     *     path="/api/signup",
     *     summary="Creates new user",
     *     tags={"User"},
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict"
     *     ),
     *     @OA\RequestBody(
     *         @OA\Schema(type="object"),
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     )
     * )
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function __invoke(Request $request): OpenApi
    {
        $content = json_decode($request->getContent(), true);

        $email = $content['email'];
        $plainPassword = $content['password'];
        $uuid = Uuid::uuid4()->toString();

        Assertion::notNull($email, "Email can\'t be null");
        Assertion::notNull($plainPassword, "Password can\'t be null");

        $commandRequest = new SignUpCommand($uuid, $email, $plainPassword);

        $this->handle($commandRequest);

        return OpenApi::created("/api/user");
    }
}
