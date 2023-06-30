<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditCommentTest extends WebTestCase
{
    public function testsumbitEditCommentWorks(): void
    {
        $client = static::createClient();
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $commentRepository = $client->getContainer()->get(CommentRepository::class);
        $urlGenerator = $client->getContainer()->get('router');

        $comment = $commentRepository->findOneBy([]);
        $user = $userRepository->find($comment->getAuthor()->getId());

        $commentId = $user->getComments()->first()->getId();
        $postId = $comment->getPost()->getId();

        $client->loginUser($user);

        $commentEditPage = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('comment_edit', ['id' => $commentId])
        );

        $this->assertResponseStatusCodeSame(200);

        $form = $commentEditPage->filter('form[name=comment_form]')->form([
            'comment_form[content]' => 'Edit content 01'
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();

        $this->assertRouteSame('blog_detail', ['id' => $postId]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
}
