<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testLoginWorks(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('app_login')
        );

        $form = $crawler->filter('form')->form([
            'username' => 'Post 99',
            'password' => 'Post 99',
            '_remember_me' => 'on'
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
    }
}
