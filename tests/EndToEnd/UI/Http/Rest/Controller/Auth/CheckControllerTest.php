<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\UI\Http\Rest\Controller\Auth;

use App\Tests\EndToEnd\UI\Http\Rest\Controller\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class CheckControllerTest extends JsonApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function bad_credentials_must_fail_with_401(): void
    {
        $this->post('/api/auth_check', [
            '_username' => 'fail@users.com',
            '_password' => 'password',
        ]);

        self::assertSame(Response::HTTP_UNAUTHORIZED, $this->cli->getResponse()->getStatusCode());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function email_must_be_valid_or_fail_with_400(): void
    {
        $this->post('/api/auth_check', [
            '_username' => 'fail@',
            '_password' => 'password',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->cli->getResponse()->getStatusCode());
    }
}
