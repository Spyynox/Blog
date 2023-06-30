<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChangePublisedStatusTest extends WebTestCase
{
    public function testChangePublisedStatusTestWorks(): void
    {
        $client = static::createClient();

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $commentRepository = $client->getContainer()->get(CommentRepository::class);
        
        $comment = $commentRepository->findOneBy([]);
        $user = $userRepository->find($comment->getAuthor()->getId());
        
        $commentId = $user->getComments()->first()->getId();
        
        $urlGenerator = $client->getContainer()->get('router');
        $client->loginUser($user);

        $client->request(
            Request::METHOD_POST,
            $urlGenerator->generate('comment_api_remove', ['id' => $commentId])
        );

        $this->assertResponseStatusCodeSame(200);
    }
}
