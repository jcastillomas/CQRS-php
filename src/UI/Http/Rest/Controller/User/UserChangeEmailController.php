<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use App\UI\Http\Rest\Controller\CommandController;
use App\UI\Http\Session;
use App\User\Domain\Exception\ForbiddenException;
use Assert\Assertion;
use Assert\AssertionFailedException;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class UserChangeEmailController extends CommandController
{
    private Session $session;

    public function __construct(Session $session, CommandBusInterface $commandBus)
    {
        parent::__construct($commandBus);

        $this->session = $session;
    }

    /**
     * @Route(
     *     "/api/user/{uuid}/email",
     *     name="user_change_email",
     *     methods={"POST"}
     * )
     *
     * @OA\Post(
     *     path="/api/user/{uuid}/email",
     *     summary="Change user email",
     *     tags={"User"},
     *     @OA\Response(
     *         response=201,
     *         description="Email changed"
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
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string"),
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         @OA\Schema(type="string")
     *     )
     * )
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $this->validateUuid($uuid);

        $email = $content['email'];

        Assertion::notNull($email, "Email can\'t be null");

        $command = new ChangeEmailCommand($uuid, $email);

        $this->handle($command);

        return new JsonResponse();
    }

    private function validateUuid(string $uuid): void
    {
        if (!$this->session->get('uuid')->uuid()->equals(Uuid::fromString($uuid))) {
            throw new ForbiddenException();
        }
    }
}
