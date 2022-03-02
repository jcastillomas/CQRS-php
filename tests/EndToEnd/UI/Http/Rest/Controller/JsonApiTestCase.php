<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\UI\Http\Rest\Controller;

use App\Application\Command\User\SignUp\SignUpCommand;
use App\Application\Query\User\FindByEmail\FindByEmailQuery;
use App\Infrastructure\Shared\Bus\Command\MessengerCommandBus;
use App\Infrastructure\Shared\Bus\Query\MessengerQueryBus;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

abstract class JsonApiTestCase extends WebTestCase
{
    public const DEFAULT_EMAIL = 'test@users.com';

    public const DEFAULT_PASS = 'password';

    protected ?KernelBrowser $cli;

    private ?string $token = null;


    protected function setUp(): void
    {
        $this->cli = static::createClient([
            'environment' => 'test',
            'debug'       => false
        ]);
        $this->cli->catchExceptions(false);
    }

    /**
     * @throws AssertionFailedException
     * @throws Throwable
     */
    protected function createUser(string $email = self::DEFAULT_EMAIL, string $password = self::DEFAULT_PASS): string
    {
        $this->userUuid = Uuid::uuid4();

        $signUp = new SignUpCommand(
            $this->userUuid->toString(),
            $email,
            $password
        );

        /** @var MessengerCommandBus $commandBus */
        $commandBus = self::getContainer()->get(MessengerCommandBus::class);

        $commandBus->handle($signUp);

        return $email;
    }

    protected function post(string $uri, array $params): void
    {
        $this->cli->request(
            'POST',
            $uri,
            [],
            [],
            $this->headers(),
            (string) \json_encode($params)
        );
    }

    protected function get(string $uri, array $parameters = []): void
    {
        $this->cli->request(
            'GET',
            $uri,
            $parameters,
            [],
            $this->headers()
        );
    }

    protected function auth(string $username = self::DEFAULT_EMAIL, string $password = self::DEFAULT_PASS): void
    {
        $this->post('/api/auth_check', [
            '_username' => $username ?: self::DEFAULT_EMAIL,
            '_password' => $password ?: self::DEFAULT_PASS,
        ]);

        /** @var string $content */
        $content = $this->cli->getResponse()->getContent();

        $response = \json_decode($content, true);

        $this->token = $response['token'];
    }

    protected function logout(): void
    {
        $this->token = null;
    }

    private function headers(): array
    {
        $headers = [
            'CONTENT_TYPE' => 'application/json',
        ];

        if ($this->token) {
            $headers['HTTP_Authorization'] = 'Bearer ' . $this->token;
        }

        return $headers;
    }

    protected function fireTerminateEvent(): void
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->cli->getContainer()->get('event_dispatcher');

        $dispatcher->dispatch(
            new TerminateEvent(
                static::$kernel,
                Request::create('/'),
                new Response()
            ),
            KernelEvents::TERMINATE
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cli = null;
        $this->token = null;
    }
}
