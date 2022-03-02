<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\UI\Http\Rest\Controller\HealthCheck;

use App\Tests\EndToEnd\UI\Http\Rest\Controller\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class HealthCheckControllerTest extends JsonApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function events_list_must_return_404_when_no_page_found(): void
    {
        $this->get('/status');

        self::assertSame(Response::HTTP_OK, $this->cli->getResponse()->getStatusCode());
    }
}
