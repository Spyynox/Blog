<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogNewTest extends WebTestCase
{
    public function testsumbitBlogWorks(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $categoryRepository = $client->getContainer()->get(CategoryRepository::class);
        $user = $userRepository->findOneBy([]);
        $category = $categoryRepository->findOneBy([]);
        $urlGenerator = $client->getContainer()->get('router');

        $client->loginUser($user);

        $newBlogpage = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('blog_new')
        );

        $this->assertResponseStatusCodeSame(200);

        $form = $newBlogpage->filter('form[name=post_form]')->form([
            'post_form[title]' => 'Title Post',
            'post_form[content]' => 'Content post',
            'post_form[categories]' => $category->getId(),
            'post_form[image]' => 'https://via.placeholder.com/640x480.png/00bbbb?text=omnis',
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();

        $this->assertRouteSame('blog_list');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
}
