<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Comment;
use App\DataFixtures\UserFixtures;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PostRepository $postRepository
    ) {
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $this->userRepository->findAll();
        $posts = $this->postRepository->findAll();

        for ($i = 0; $i < 150; $i++) {
            $comment = new Comment();
            $comment->setContent($faker->realText(1800))
                ->setPublished(true)
                ->setAuthor($users[mt_rand(0, count($users) -1)])
                ->setPost($posts[mt_rand(0, count($posts) -1)])
            ;

            $manager->persist($comment);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PostFixtures::class
        ];
    }
}
