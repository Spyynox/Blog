<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProfileTest extends WebTestCase
{
    public function testUserProfile(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy([]);
        $urlGenerator = $client->getContainer()->get('router');

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('user_profile', ['id' => $user->getId()])
        );

        $this->assertResponseStatusCodeSame(200);
    }
}
