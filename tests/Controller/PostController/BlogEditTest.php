<?php

namespace App\Tests\Functional;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogEditTest extends WebTestCase
{
    public function testEditPostWorks(): void
    {
        $client = static::createClient();

        $categoryRepository = $client->getContainer()->get(CategoryRepository::class);
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $postRepository = $client->getContainer()->get(PostRepository::class);
        
        $category = $categoryRepository->findOneBy([]);
        $post = $postRepository->findOneBy([]);
        $user = $userRepository->find($post->getAuthor()->getId());
        
        $postId = $user->getPosts()->first()->getId();
        
        $client->loginUser($user);
        $urlGenerator = $client->getContainer()->get('router');

        $newBlogpage = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('blog_edit', ['id' => $postId])
        );

        $this->assertResponseStatusCodeSame(200);

        $form = $newBlogpage->filter('form[name=post_form]')->form([
            'post_form[title]' => 'Title Post edit',
            'post_form[content]' => 'Content post edit',
            'post_form[categories]' => $category->getId(),
            'post_form[image]' => 'https://via.placeholder.com/640x480.png/00bbbb?text=edit',
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();

        $this->assertRouteSame('blog_detail', ['id' => $postId]);
        $this->assertResponseStatusCodeSame(200);
    }
}
