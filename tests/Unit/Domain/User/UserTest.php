<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\User;
use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Domain\User\ValueObject\Email;
use App\User\Domain\Exception\EmailAlreadyExistException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserTest extends TestCase
{
    private bool $isUniqueException = false;

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_a_valid_email_it_should_create_a_user_instance(): void
    {
        $emailString = 'lol@aso.maximo';

        $user = User::create(
            Uuid::uuid4(),
            new Credentials(
                Email::fromString($emailString),
                HashedPassword::encode('password')
            ),
            $this
        );

        self::assertSame($emailString, $user->getEmail());
        self::assertNotNull($user->getUuid());

    }

    /**
     * @throws EmailAlreadyExistException
     */
    public function isUnique(Email $email): bool
    {
        if ($this->isUniqueException) {
            throw new EmailAlreadyExistException();
        }

        return true;
    }
}
