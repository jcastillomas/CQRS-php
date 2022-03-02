<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\User\Command\SignIn;

use App\Application\Command\User\SignIn\SignInCommand;
use App\Application\Command\User\SignUp\SignUpCommand;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Tests\Integration\Application\Shared\ApplicationTestCase;
use Assert\AssertionFailedException;
use Exception;
use Ramsey\Uuid\Uuid;
use Throwable;

final class SignInTest extends ApplicationTestCase
{
    protected static $initialized = false;

    /**
     * @test
     *
     * @group integration
     *
     * @throws Throwable
     */
    public function user_sign_up_with_valid_credentials(): void
    {
        $command = new SignInCommand(
            'valid@users.com',
            'password'
        );

        $this->handle($command);

    }

    /**
     * @test
     *
     * @group integration
     *
     * @dataProvider invalidCredentials
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function user_sign_up_with_invalid_credentials_must_throw_domain_exception(string $email, string $pass): void
    {
        $this->expectException(InvalidCredentialsException::class);

        $command = new SignInCommand($email, $pass);

        $this->handle($command);
    }

    public function invalidCredentials(): array
    {
        return [
          [
              'email' => 'valid@users.com',
              'pass' => 'bad-password',
          ],
          [
              'email' => 'unvalid@users.com',
              'pass' => 'password',
          ],
        ];
    }

    /**
     * @throws Exception
     * @throws AssertionFailedException
     * @throws Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$initialized) {
            $command = new SignUpCommand(
                Uuid::uuid4()->toString(),
                'valid@users.com',
                'password'
            );

            $this->handle($command);
            self::$initialized = true;
        }
    }
}
