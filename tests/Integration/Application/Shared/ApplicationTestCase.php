<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\Shared;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\CommandInterface;
use App\Application\Query\QueryBusInterface;
use App\Application\Query\QueryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Throwable;

abstract class ApplicationTestCase extends KernelTestCase
{
    private ?CommandBusInterface $commandBus;

    private ?QueryBusInterface $queryBus;

    protected function setUp(): void
    {
        self::bootKernel([
            'environment' => 'test',
            'debug'       => true,
        ]);

        $this->commandBus = $this->service(CommandBusInterface::class);
        $this->queryBus = $this->service(QueryBusInterface::class);
    }

    /**
     * @return mixed
     *
     * @throws Throwable
     */
    protected function ask(QueryInterface $query)
    {
        return $this->queryBus->ask($query);
    }

    /**
     * @throws Throwable
     */
    protected function handle(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }

    /**
     * @return object|null
     */
    protected function service(string $serviceId)
    {
        return self::getContainer()->get($serviceId);
    }

    protected function fireTerminateEvent(): void
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->service('event_dispatcher');

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
        $this->commandBus = null;
        $this->queryBus = null;
    }


    public static function getKernel(): KernelInterface
    {
        return self::bootKernel([
            'environment' => 'test',
            'debug'       => true,
        ]);
    }
}
