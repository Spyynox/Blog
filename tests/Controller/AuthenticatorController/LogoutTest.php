<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogoutTest extends WebTestCase
{
    public function testLogoutWorks(): void
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);

        $urlGenerator = $client->getContainer()->get('router');

        $user = $userRepository->findOneBy([]);

        $client->loginUser($user);

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('app_logout')
        );

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();

        $this->assertRouteSame('app_login');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
}
