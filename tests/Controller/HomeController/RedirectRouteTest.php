<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedirectRouteTest extends WebTestCase
{
    public function testRedirectRouteTestWorks(): void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router');

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('app_home')
        );

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();

        $this->assertRouteSame('blog_list');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
}
