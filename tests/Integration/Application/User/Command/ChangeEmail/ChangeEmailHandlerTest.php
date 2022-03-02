<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\User\Command\ChangeEmail;


use App\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use App\Application\Command\User\SignUp\SignUpCommand;
use App\Tests\Integration\Application\Shared\ApplicationTestCase;
use Assert\AssertionFailedException;
use Exception;
use Ramsey\Uuid\Uuid;
use Throwable;

class ChangeEmailHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws Exception
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function update_user_email_command(): void
    {
        $command = new SignUpCommand($uuid = Uuid::uuid4()->toString(), 'test@test.test', 'password');

        $this->handle($command);

        $email = 'new@test.test';

        $command = new ChangeEmailCommand($uuid, $email);

        $this->handle($command);
    }
}
