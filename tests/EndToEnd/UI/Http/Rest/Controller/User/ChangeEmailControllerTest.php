<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\UI\Http\Rest\Controller\User;

use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\EndToEnd\UI\Http\Rest\Controller\JsonApiTestCase;
use Throwable;

class ChangeEmailControllerTest extends JsonApiTestCase
{
    public static $numTest = 0;
    /**
     * @test
     *
     * @group e2e
     *
     * @throws Exception
     */
    public function given_a_valid_uuid_and_email_should_return_a_201_status_code(): void
    {
        $this->post('/api/users/' . $this->userUuid->toString() . '/email', [
            'email' => 'changeemailcontrollertest@users.com',
        ]);

        self::assertSame(Response::HTTP_OK, $this->cli->getResponse()->getStatusCode());

    }

    /**
     * @test
     *
     * @group e2e
     */
    public function given_a_valid_uuid_and_email_user_should_not_change_others_email_and_gets_401(): void
    {
        $this->post('/api/users/' . Uuid::uuid4()->toString() . '/email', [
            'email' => 'changeemailcontrollertest@users.com',
        ]);

        self::assertSame(Response::HTTP_FORBIDDEN, $this->cli->getResponse()->getStatusCode());

    }

    /**
     * @test
     *
     * @group e2e
     *
     * @throws Exception
     */
    public function given_a_invalid__email_should_return_a_400_status_code(): void
    {
        $this->post('/api/users/' . $this->userUuid->toString() . '/email', [
            'email' => 'changeemailcontrollertest.com',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->cli->getResponse()->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();
        ++self::$numTest;
        $this->createUser('changeemailcontrollertest'. self::$numTest . '@users.com');
        $this->auth();
    }
}
