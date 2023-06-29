<?php

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostTest extends KernelTestCase
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

    public function testInvalidPostObject()
    {
        self::bootKernel();
        $container = static::getContainer();

        $post = $this->getPost();
        $post->setTitle('');
        $post->setContent('');
        $post->setImage('');
        $post->setPublished(false);

        $errors = $container->get('validator')->validate($post);
        $this->assertCount(3, $errors);
    }
}
