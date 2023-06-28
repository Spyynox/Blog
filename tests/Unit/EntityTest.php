<?php

namespace App\Tests\Unit;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntityTest extends KernelTestCase
{
    public function getEntity(): User
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

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();

        $errors = $container->get('validator')->validate($user);

        $this->assertCount(0, $errors);
    }

    public function testInvalidUserObject()
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();
        $user->setUsername('');
        $user->setFirstname('');
        $user->setLastname('');
        $user->setEmail('');
        $user->setPassword('');
        $user->setDescription('');

        $errors = $container->get('validator')->validate($user);
        $this->assertCount(5, $errors);
    }
}
