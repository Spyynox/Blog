<?php

namespace App\Tests\Functional;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogDetailTest extends WebTestCase
{
    public function testBlogDetail(): void
    {
        $client = static::createClient();
        $postRepository = $client->getContainer()->get(PostRepository::class);
        
        $post = $postRepository->findOneBy([]);
        $urlGenerator = $client->getContainer()->get('router');

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('blog_detail', ['id' => $post->getId()])
        );

        $this->assertResponseStatusCodeSame(200);
    }

    public function testCommentSend(): void
    {
        $client = static::createClient();
        $postRepository = $client->getContainer()->get(PostRepository::class);
        $userRepository = $client->getContainer()->get(UserRepository::class);

        $post = $postRepository->findOneBy([]);
        $user = $userRepository->findOneBy([]);
        $urlGenerator = $client->getContainer()->get('router');

        $client->loginUser($user);

        $newComment = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('blog_detail', ['id' => $post->getId()])
        );

        $this->assertResponseStatusCodeSame(200);

        $form = $newComment->filter('form[name=comment_form]')->form([
            'comment_form[content]' => 'Content comment'
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();

        $this->assertRouteSame('blog_detail', ['id' => $post->getId()]);
    }
}
