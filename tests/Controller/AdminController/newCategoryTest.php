<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NewCategoryTest extends WebTestCase
{
    public function testsumbitCategoryWorks(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $urlGenerator = $client->getContainer()->get('router');
        $user = $userRepository->findOneBy([]);
        $client->loginUser($user);

        $categoryPage = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('admin_new_category')
        );

        $this->assertResponseStatusCodeSame(200);

        $form = $categoryPage->filter('form[name=category_form]')->form([
            'category_form[title]' => 'category 01'
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(200);
    }
}
