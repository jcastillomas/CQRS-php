<?php

declare(strict_types=1);

namespace App\UI\Http\Web\Controller\Swagger;

use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class DocumentationController
{

    private Environment $twigEnvironment;

    public function __construct(
        Environment $twigEnvironment
    ) {
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @Route(
     *     "/documentation",
     *     name="swagger",
     *     methods={"GET"}
     * )
     */
    public function __invoke(Request $request)
    {
        $body =  $this->twigEnvironment->render('Swagger/index.html.twig');

        return new Response(
            $body,
            Response::HTTP_OK,
            ['content-type' => 'text/html; charset=utf-8']
        );
    }
}
