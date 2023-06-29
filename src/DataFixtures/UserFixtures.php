<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = array();
        for ($i = 0; $i < 3; $i++) {
            $users[$i] = new User();
            $users[$i]->setUsername($faker->firstName());
            $users[$i]->setFirstname($faker->firstName());
            $users[$i]->setLastname($faker->lastName());
            $users[$i]->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            $users[$i]->setEmail($faker->email());
            $users[$i]->setPassword($faker->password());
            $users[$i]->setDescription($faker->text(50));
            $manager->persist($users[$i]);
        }
        $manager->flush();
    }
}
