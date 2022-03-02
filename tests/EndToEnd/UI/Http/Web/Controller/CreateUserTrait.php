<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\UI\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait CreateUserTrait
{
    private function createUser(string $email, string $password = 'password'): KernelBrowser
    {
        $client = self::createClient([
            'environment' => 'test',
            'debug'       => false
        ]);
        $client->catchExceptions(false);

        $crawler = $client->request('GET', '/sign-up');

        $form = $crawler->selectButton('Send')->form();

        $form->get('email')->setValue($email);
        $form->get('password')->setValue($password);

        $client->submit($form);

        return $client;
    }
}
