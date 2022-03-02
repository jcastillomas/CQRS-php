<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\User\Query\FindByEmail;

use App\Application\Command\User\SignUp\SignUpCommand;
use App\Application\Query\Item;
use App\Application\Query\User\FindByEmail\FindByEmailQuery;
use App\Tests\Integration\Application\Shared\ApplicationTestCase;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Throwable;

class FindByEmailHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function query_command_integration(): void
    {
        $email = $this->createUserRead();

        $this->fireTerminateEvent();

        /** @var Item $result */
        $result = $this->ask(new FindByEmailQuery($email));

        self::assertInstanceOf(Item::class, $result);
        self::assertSame($email, $result->resource['credentials.email']->toString());
    }

    /**
     * @throws Throwable
     * @throws AssertionFailedException
     */
    private function createUserRead(): string
    {
        $uuid = Uuid::uuid4()->toString();
        $email = 'FindByEmailHandlerTest@users.com';

        $this->handle(new SignUpCommand($uuid, $email, 'password'));

        return $email;
    }
}
