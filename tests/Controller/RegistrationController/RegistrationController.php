<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationController extends WebTestCase
{
    public function testLoginWorks(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('app_register')
        );

        $form = $crawler->filter('form[name=registration_form]')->form([
            'registration_form[username]' => 'Post 99',
            'registration_form[firstname]' => 'Post99',
            'registration_form[lastname]' => 'P99',
            'registration_form[username]' => 'post99@symblog.fr',
            'registration_form[description]' => 'deknf klfnsjfnjzk jzekzdk',
            'registration_form[logo]' => 'https://wotpack.ru/wp-content/uploads/2023/05/1-18.jpg',
            'registration_form[password]' => 'Post 99',
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(200);
    }

}
