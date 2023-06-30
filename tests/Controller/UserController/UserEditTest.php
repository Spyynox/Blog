<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserEditTest extends WebTestCase
{
    public function testUserEditWorks(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy([]);
        $client->loginUser($user);
        $urlGenerator = $client->getContainer()->get('router');

        $editUser = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('user_edit', ['id' => $user->getId()])
        );

        $this->assertResponseStatusCodeSame(200);

        $form = $editUser->filter('form[name=user_form]')->form([
            'user_form[username]' => 'Post 99 edit',
            'user_form[firstname]' => 'Post99 edit',
            'user_form[lastname]' => 'P99 edit',
            'user_form[email]' => 'post99@symblog.fr',
            'user_form[description]' => 'deknf klfnsjfnjzk jzekzdk edit',
            'user_form[logo]' => 'https://wotpack.ru/wp-content/uploads/2023/05/1-18.jpg',
            'user_form[password]' => 'Post 99 edit',
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(200);
    }
}
