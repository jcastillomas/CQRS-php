<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\UI\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use CreateUserTrait;
    /**
     * @test
     *
     * @group e2e
     */
    public function sign_in_after_create_user(): void
    {

        $client = $this->createUser('securitycontrollertest1@users.com');

        self::ensureKernelShutdown();

        $crawler = $client->request('GET', '/sign-in');

        $form = $crawler->selectButton('Sign in')->form();

        $form->get('_email')->setValue('securitycontrollertest1@users.com');
        $form->get('_password')->setValue('password');

        $client->submit($form);

        $crawler = $client->followRedirect();

        self::assertSame('/user/profile', \parse_url($crawler->getUri(), \PHP_URL_PATH));
        self::assertSame(1, $crawler->filter('html:contains("Hi securitycontrollertest1@users.com")')->count());

    }

    /**
     * @test
     *
     * @group e2e
     */
    public function logout_should_remove_session_and_profile_redirect_sign_in(): void
    {
        $client = $this->createUser('securitycontrollertest2@users.com');

        self::ensureKernelShutdown();

        $crawler = $client->request('GET', '/sign-in');

        $form = $crawler->selectButton('Sign in')->form();

        $form->get('_email')->setValue('securitycontrollertest2@users.com');
        $form->get('_password')->setValue('password');

        $client->submit($form);

        $crawler = $client->followRedirect();
        self::assertSame('/user/profile', \parse_url($crawler->getUri(), \PHP_URL_PATH));

        $client->click($crawler->selectLink('Exit')->link());

        $crawler = $client->followRedirect();
        self::assertSame('/', \parse_url($crawler->getUri(), \PHP_URL_PATH));

        $client->request('GET', '/user/profile');

        $crawler = $client->followRedirect();
        self::assertSame('/sign-in', \parse_url($crawler->getUri(), \PHP_URL_PATH));
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function login_should_display_an_error_when_bad_credentials(): void
    {
        self::ensureKernelShutdown();
        $client = self::createClient([
            'environment' => 'test',
            'debug'       => false
        ]);
        $client->catchExceptions(false);

        $crawler = $client->request('GET', '/sign-in');

        $form = $crawler->selectButton('Sign in')->form();

        $form->get('_email')->setValue('securitycontrollertest1@users.com');
        $form->get('_password')->setValue('bad-password');

        $client->submit($form);

        $crawler = $client->followRedirect();
        self::assertSame(1, $crawler->filter('html:contains("An authentication exception occurred.")')->count());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function login_should_display_an_error_when_bad_invalid_email(): void
    {
        $client = self::createClient([
            'environment' => 'test',
            'debug'       => false
        ]);
        $client->catchExceptions(false);

        $crawler = $client->request('GET', '/sign-in');

        $form = $crawler->selectButton('Sign in')->form();

        $form->get('_email')->setValue('securitycontrollertest2@users.com');
        $form->get('_password')->setValue('bad-password');

        $client->submit($form);

        $crawler = $client->followRedirect();
        self::assertSame(1, $crawler->filter('html:contains("An authentication exception occurred.")')->count());
    }

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
