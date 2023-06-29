<?php

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentTest extends KernelTestCase
{
    public function getUser(): User
    {

        return (
            new User()
        )
            ->setUsername('User 01')
            ->setFirstname('Firstname 01')
            ->setLastname('Lastname 01')
            ->setEmail('user01@gmail.com')
            ->setPassword('userPassword')
            ->setDescription('Description #1');
    }

    public function getCategory(): Category
    {

        return (
            new Category()
        )
            ->setTitle('Category 01');
    }

    public function getPost(): Post
    {
        $user = $this->getUser();
        $category = $this->getCategory();

        return (
            new Post()
        )
            ->setTitle('Post 01')
            ->setContent('Content post 01')
            ->setImage('Post 01')
            ->setPublished(true)
            ->setUserId($user)
            ->addCategory($category)
            ;
    }

    public function getComment(): Comment
    {
        $user = $this->getUser();
        $post = $this->getPost();

        return (
            new Comment()
        )
            ->setContent('Content comment 01')
            ->setPublished(true)
            ->setAuthor($user)
            ->setPost($post)
            ;
    }

    public function testInvalidPostObject()
    {
        self::bootKernel();
        $container = static::getContainer();

        $post = $this->getComment();
        $post->setContent('');
        $post->setPublished(false);

        $errors = $container->get('validator')->validate($post);
        $this->assertCount(1, $errors);
    }
}
