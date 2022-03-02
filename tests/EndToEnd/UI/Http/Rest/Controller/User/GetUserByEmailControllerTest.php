<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\UI\Http\Rest\Controller\User;

use Tests\App\Shared\Infrastructure\Event\EventCollectorListener;
use App\Tests\EndToEnd\UI\Http\Rest\Controller\JsonApiTestCase;
use Assert\AssertionFailedException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class GetUserByEmailControllerTest extends JsonApiTestCase
{

    /**
     * @test
     *
     * @group e2e
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function valid_input_parameters_should_return_200_status_code_when_exist(): void
    {
        $emailString = $this->createUser('getuserbyemailcontrollertest1@users.com');
        $this->auth();

        $this->get('/api/user/');

        self::assertSame(Response::HTTP_OK, $this->cli->getResponse()->getStatusCode());

        $response = \json_decode($this->cli->getResponse()->getContent(), true);

        self::assertArrayHasKey('data', $response);
        self::assertArrayHasKey('id', $response['data']);
        self::assertArrayHasKey('type', $response['data']);
        self::assertArrayHasKey('attributes', $response['data']);
        self::assertArrayHasKey('uuid', $response['data']['attributes']);
        self::assertArrayHasKey('credentials.email', $response['data']['attributes']);
        self::assertArrayHasKey('createdAt', $response['data']['attributes']);
        self::assertEquals($emailString, $response['data']['attributes']['credentials.email']);

    }
}
