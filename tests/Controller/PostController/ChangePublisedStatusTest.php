<?php

namespace App\Tests\Functional;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChangePublisedStatusTest extends WebTestCase
{
    public function testChangePublisedStatusTest(): void
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $postRepository = $client->getContainer()->get(PostRepository::class);
        
        $post = $postRepository->findOneBy([]);
        $user = $userRepository->find($post->getAuthor()->getId());
        
        $postId = $user->getComments()->first()->getId();
        
        $urlGenerator = $client->getContainer()->get('router');
        $client->loginUser($user);

        $client->request(
            Request::METHOD_POST,
            $urlGenerator->generate('blog_api_remove', ['id' => $postId])
        );

        $this->assertResponseStatusCodeSame(200);
    }
}
