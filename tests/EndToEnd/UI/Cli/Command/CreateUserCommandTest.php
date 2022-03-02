<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\UI\Cli\Command;

use App\Application\Command\CommandBusInterface;
use App\Application\Query\Item;
use App\Application\Query\User\FindByEmail\FindByEmailQuery;
use App\UI\Console\Command\CreateUserCommand;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use App\Tests\EndToEnd\UI\Cli\AbstractConsoleTestCase;
use Throwable;

class CreateUserCommandTest extends AbstractConsoleTestCase
{
    /**
     * @test
     *
     * @group e2e
     *
     * @throws Throwable
     * @throws AssertionFailedException
     */
    public function command_integration_with_bus_success(): void
    {
        $email = 'valid@users.com';

        /** @var CommandBusInterface $commandBus */
        $commandBus = $this->service(CommandBusInterface::class);
        $commandTester = $this->app($command = new CreateUserCommand($commandBus), 'app:create-user');

        $commandTester->execute([
            'command' => $command->getName(),
            'uuid' => Uuid::uuid4()->toString(),
            'email' => $email,
            'password' => 'password',
        ]);

        /** @var Item $result */
        $result = $this->ask(new FindByEmailQuery($email));

        self::assertInstanceOf(Item::class, $result);
        self::assertSame($email, $result->resource['credentials.email']->toString());
    }
}
