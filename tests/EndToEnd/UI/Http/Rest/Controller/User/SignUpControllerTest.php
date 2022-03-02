<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\UI\Http\Rest\Controller\User;

use App\User\Domain\Event\UserWasCreated;
use Tests\App\Shared\Infrastructure\Event\EventCollectorListener;
use App\Tests\EndToEnd\UI\Http\Rest\Controller\JsonApiTestCase;
use Broadway\Domain\DomainMessage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class SignUpControllerTest extends JsonApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Exception
     */
    public function given_a_valid_uuid_and_email_and_password_should_return_a_201_status_code(): void
    {
        $this->post('/api/signup', [
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'signupcontrollertest1@users.com',
            'password' => 'password',
        ]);

        self::assertSame(Response::HTTP_CREATED, $this->cli->getResponse()->getStatusCode());

    }

    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Exception
     */
    public function given_a_email_which_used_by_other_user_should_return_a_400_status_code(): void
    {
        $this->post('/api/signup', [
            'email' => 'signupcontrollertest1@users.com',
            'password' => 'oaisudaosudoaudo',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->cli->getResponse()->getStatusCode());

    }

    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Exception
     */
    public function invalid_input_parameters_should_return_400_status_code(): void
    {
        $this->post('/api/signup', [
            'email' => 'invalid email',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->cli->getResponse()->getStatusCode());

    }
}
